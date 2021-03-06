<?php

include_once __DIR__ . '/../agent/agentdata.php';
include_once __DIR__ . '/../mib/mibdata.php';

class WarnData {

    private static $set = [];
    private static $mibdata = [];
    private static $agentdata = [];
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

    public static function setData() {
	self::$agentdata = AGENTData::get_agent_info();
	$mib = new MIBData();
	self::$mibdata = $mib->getMIB(1, 2);
    }

    private function setAgent() {
	$agent = $this->search_agent();
	$this->agent_info = ($agent) ? '【' . $agent['COMMUNITY'] . '】' . $agent['AGENTHOST'] . '（' . $agent['AGENTID'] . '）' : '〈判別不可〉';
    }

    private function search_agent() {
	$res = '';
	if ($this->address && $this->community) {
	    foreach (self::$agentdata['VALUE'] as $a) {
		if ($a['AGENTHOST'] == $this->address && $a['COMMUNITY'] == $this->community) {
		    $res = $a;
		    break;
		}
	    }
	}
	return $res;
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
		$data = '';
		if (isset(self::$mibdata['NODE'])) {
		    foreach (self::$mibdata['NODE'] as $g) {
			foreach ($g as $s) {
			    $data = ($this->alert_oid == $s['OID']) ? ['JAPTLANS' => $s['JAPTLANS'], 'DESCR' => $s['DESCR']] : '';
			    if ($data) {
				break;
			    }
			}
		    }
		}
		$this->message = ($data) ? '【' . $data['DESCR'] . '】「' . $data['JAPTLANS'] . '」が発生しました。' : '【Undefined】認識不可能なトラップ内容';
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
	    ['ADDRESS', '(^UDP: \[|\][:][0-9]{1,5}[-]\[(.+)\][:][0-9]{1,5})', 0],
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
		    $preg_target = ($f[2] == 0) ? $f[1] : '';
		    $value = preg_replace("/$preg_target/", '', $v);
		    $res[$f[0]] = (($f[2] == 1 && isset($res[$f[0]]) && $res[$f[0]]) ? $res[$f[0]] . '<br>' : '') . $value;
		} else {
		    if (!isset($res[$f[0]])) {
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
	$res = ['VALUE' => [], 'UPDATED_LOG' => [], 'CSV' => '', 'DATE' => date("Y-m-d H:i:s")];
	foreach (self::$set as $warn) {
	    $group = $warn->getGroup();
	    $data = $warn->getData();
	    if (!isset($res['VALUE'][$group])) {
		$res['VALUE'][$group] = [$data];
	    } else {
		array_push($res['VALUE'][$group], $data);
	    }
	    $res['UPDATED_LOG'][$group] = $data['MESSAGE'];
	}
	$res['CSV'] = self::convertToCSV($res);
	return $res;
    }

    private static function convertToCSV($data) {
	$res = 'Virtual Control Trap Data Convertion v 1.0.0\n取得時間,' . $data['DATE'] . '\n+----- 取得データ一覧 -----+\n日別番号,システム稼働時間,発生時刻,ホストアドレス,コミュニティ,対象OID,エージェント情報,情報出力先OID,インタフェースID,その他情報,メッセージ\n';

	foreach ($data['VALUE'] as $g => $v) {
	    $res .= '【' . $g . '】（' . sizeof($v) . '）\n';
	    $i = 1;
	    foreach ($v as $c) {
		$res .= $i;
		foreach ($c as $vl) {
		    $c_data = preg_replace('/(,|\n)/', ' ', $vl);
		    $res .= ',' . $c_data;
		}
		$res .= '\n';
		$i += 1;
	    }
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
		$ex_data = preg_replace('/([+][=][=][+]){1,}$/', '', file_get_contents($r));
		if (preg_match('/^([0-9]{4}[0-9]{2}[0-9]{2})$/', $date)) {
		    $key = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
		    $res_data[$key] = explode("\n", $ex_data);
		}
	    }
	} else {
	    $res_data = '';
	}
	return $res_data;
    }
}
