<?php

include_once __DIR__ . '/../general/sqldata.php';

class WarnData {

    private static $set = [];
    private $group;
    private $agent_info;
    private $time;
    private $address;
    private $community;
    private $interface;
    private $systime;
    private $alert_oid;
    private $message;
    private $source;
    private $enterprise_oid;

    public function __construct($group, $time, $address, $community, $interface, $systime, $alert_oid, $source, $enterprise_oid) {
	$this->group = $group;
	$this->time = $time;
	$this->address = $address;
	$this->community = $community;
	$this->interface = $interface;
	$this->systime = $systime;
	$this->alert_oid = $alert_oid;
	$this->source = $source;
	$this->enterprise_oid = $enterprise_oid;
	$this->setAgent();
	$this->setMessage();
	array_push(self::$set, $this);
    }

    private function setAgent() {
	if ($this->address && $this->community) {
	    $host = gethostbyaddr($this->address);
	    $sel = select(true, "GSC_AGENT", "AGENTID, AGENTHOST, COMMUNITY", "WHERE AGENTHOST IN ('$this->address', '$host') AND COMMUNITY = '$this->community'");
	    if ($sel) {
		$this->agent_info = '【' . $sel['AGENTID'] . '】' . $sel['AGENTHOST'] . '（' . $sel['COMMUNITY'] . '）';
	    } else {
		$this->agent_info = '〈判別不可〉';
	    }
	}
    }

    private function setMessage() {
	switch ($this->alert_oid) {
	    case '1.3.6.1.6.3.1.1.5.1': $this->message = '【coldStart】前回より変更がないまま再起動が行われました';
		break;
	    case '1.3.6.1.6.3.1.1.5.2': $this->message = '【warmStart】前回より変更がある状態で再起動が行われました';
		break;
	    case '1.3.6.1.6.3.1.1.5.3': $this->message = '【linkDown】SNMPエージェントが監視するネットワークが切断されました';
		break;
	    case '1.3.6.1.6.3.1.1.5.4': $this->message = '【linkUp】SNMPエージェントが監視するネットワークが接続（再接続）されました';
		break;
	    case '1.3.6.1.6.3.1.1.5.5': $this->message = '【authenticationFailure】認証に失敗しました';
		break;
	    case '1.3.6.1.6.3.1.1.5.6': $this->message = '【egpNeighborLoss】EGP接続のスパニングツリー状態が失われました';
		break;
	    default:
		$select = select(true, "GSC_MIB_NODE", "DESCR, JAPTLANS", "WHERE NODEOBJECTID = '$this->alert_oid' AND NODETYPE = 1");
		if ($select) {
		    $this->message = '【' . $select['DESCR'] . '】「' . $select['JAPTLANS'] . '」が発生しました。';
		} else {
		    $this->message = '【Undefined】トラップ状態の判別ができません';
		}
		break;
	}
    }

    public static function load_data() {
	$files = self::load();
	$splitter = "+==+";
	$logs = [];
	$i = 1;
	foreach ($files as $k => $v) {
	    if (!isset($logs[$k])) {
		$logs[$k] = [];
		$i = 1;
	    }
	    foreach ($v as $var) {
		if ($var == $splitter) {
		    $i += 1;
		} else if (!isset($logs[$k][$i])) {
		    $logs[$k][$i] = [];
		}
		if ($var != $splitter) {
		    array_push($logs[$k][$i], $var);
		}
	    }
	}
	foreach ($logs as $k => $v) {
	    foreach ($v as $i) {
		if (!empty($i)) {
		    $set = self::set_data($i);
		    if ($set['ADDRESS']) {
			new WarnData($k, $set['TIME'], $set['ADDRESS'], $set['COMMUNITY'], $set['INTERFACE'], $set['SYSTIME'], $set['ALERT_OID'], $set['SOURCE'], $set['ENTERPRISE']);
		    }
		}
	    }
	}
    }

    private static function set_data($data) {
	$res = [];
	$finder = [
	    ['TIME', '(^TIME: )', 0],
	    ['ADDRESS', '(^UDP: \[|\][:][0-9]{1,5}[-]\[|\][:][0-9]{1,5})', 0],
	    ['SYSTIME', '(^SNMP_SYS_TIME: )', 0],
	    ['ALERT_OID', '(^ALERT_OID: )', 0],
	    ['ADDRESS', '(^HOST: )', 0],
	    ['COMMUNITY', '(^COMMUNITY: )', 0],
	    ['ENTERPRISE', '(^SNMP_ENTERPRISE: )', 0],
	    ['SOURCE', '([0-9]{1,}[.]){1,}[0-9][ ]', 1],
	    ['INTERFACE', '(^INTERFACE: )', 0]
	];
	foreach ($data as $v) {
	    foreach ($finder as $f) {
		$check = preg_match_all("/$f[1]/", $v);
		if ($check) {
		    $preg_target = '';
		    if ($f[2] == 0) {
			$preg_target = $f[1];
		    }
		    $value = preg_replace("/$preg_target/", '', $v);
		    $res[$f[0]] = $value;
		} else {
		    if(!isset($res[$f[0]])) {
			$res[$f[0]] = '';
		    }
		}
	    }
	}
	return $res;
    }

    private function getGroup() {
	return $this->group;
    }

    private function getData() {
	return [
	    'SYSTIME' => $this->systime,
	    'TIME' => $this->time,
	    'ADDRESS' => $this->address,
	    'COMMUNITY' => $this->community,
	    'OID' => $this->alert_oid,
	    'AGENT' => $this->agent_info,
	    'ENTERPRISE' => $this->enterprise_oid,
	    'INTERFACE' => $this->interface,
	    'SOURCE' => $this->source,
	    'MESSAGE' => $this->message
	];
    }

    public static function getArray() {
	$res = [
	    'VALUE' => []
	];
	foreach (self::$set as $warn) {
	    $group = $warn->getGroup();
	    if (!isset($res['VALUE'][$group])) {
		$res['VALUE'][$group] = [];
	    }
	    array_push($res['VALUE'][$group], $warn->getData());
	}
	return $res;
    }

    private static function load() {
	$set_file = loadSetting();
	$dic = preg_replace('/\/$/', '', $set_file['logdirectory']) . '/*';
	$res = glob($dic);
	$res_data = [];
	if ($res) {
	    foreach ($res as $r) {
		$date = preg_replace('/(^.*\/|.log$|trap_)/', '', $r);
		$res_data[$date] = explode("\n", file_get_contents($r));
	    }
	} else {
	    $res_data = '';
	}
	return $res_data;
    }

}
