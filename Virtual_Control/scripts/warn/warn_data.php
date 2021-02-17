<?php

include_once __DIR__ . '/../agent/agentdata.php';
include_once __DIR__ . '/../mib/mib_data.php';

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

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param string $group グループ（日付）を指定します
     * @param string $systime システム稼働時間を指定します
     * @param string $time 発生時間を指定します
     * @param string $address ホストアドレスを指定します
     * @param string $community コミュニティ名を指定します
     * @param string $interface インタフェース番号を指定します
     * @param string $alert_oid トラップOIDを指定します1
     * @param string $enterprise_oid 通知機器固有のOIDを指定します
     * @param string $source その他データを指定します
     */
    public function __construct($group, $systime, $time, $address, $community, $interface, $alert_oid, $enterprise_oid, $source) {
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
    
    /**
     * [SET] エージェント設定
     * 
     * ホストアドレス・コミュニティ名をもとにエージェントの検索し、エージェント情報を設定します<br>
     * 見つかったらその情報が、なければ「〈判別不可〉」にします
     */
    private function setAgent() {
	$agent = $this->searchAgent();
	$this->agent_info = ($agent) ? '【' . $agent['COMMUNITY'] . '】' . $agent['AGENTHOST'] . '（' . $agent['AGENTID'] . '）' : '〈判別不可〉';
    }
    
    /**
     * [SET] 日本語メッセージオブジェクト作成
     * 
     * MIBDataより取得したデータをもとに、トラップOIDから取得できる情報を判別し、メッセージ化します
     */
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

    /**
     * [GET] オブジェクトデータ取得
     * 
     * @return array GROUP, SYSTIME, TIME, ADDRESS, COMMUNITY, OID, AGENT, ENTERPRISE, INTERFACE, SOURCE, MESSAGEからなる連想配列を返します
     */
    private function getData() {
	return [
	    'GROUP' => $this->group,
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

    /**
     * [SET] データ読み込み
     * 
     * ファイルを読み込み、データを分別・加工した上でWarnDataにデータを移行します
     */
    private static function loadData() {
	$files = self::loadFile();
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
		    $set = self::setData($i);
		    if ($set['ADDRESS']) {
			new WarnData($k, $set['SYSTIME'], $set['TIME'], $set['ADDRESS'], $set['COMMUNITY'], $set['INTERFACE'], $set['ALERT_OID'], $set['ENTERPRISE'], $set['SOURCE']);
		    }
		}
	    }
	}
    }

    /**
     * [GET] データ加工
     * 
     * ログファイルから取得した1件のデータをもとに、発生時間、システム稼働時間などのデータ種別で連想配列に加工します<br>
     * データ内に同じ項目名のデータがあった場合は、上書きするかそのまま追加します
     * 
     * @param array $data 1行区切りで配列化された1件のデータ（データ区切りがされているもの）を指定します
     * @return array TIME, SYSTIME, ADDRESS, ALERT_OID, ADDRESS, COMMUNITY, ENTERPRISE, SOURCE, INTERFACEの連想配列で返されます
     */
    private static function setData($data) {
	$res = [];
	$finder = [['TIME', '(^TIME: )', 0], ['SYSTIME', '(^SNMP_SYS_TIME: )', 0], ['ADDRESS', '(^UDP: \[|\][:][0-9]{1,5}[-]\[(.+)\][:][0-9]{1,5})', 0], ['ALERT_OID', '(^ALERT_OID: )', 0], ['ADDRESS', '(^HOST: )', 0], ['COMMUNITY', '(^COMMUNITY: )', 0], ['ENTERPRISE', '(^SNMP_ENTERPRISE: )', 0], ['SOURCE', '([0-9]{1,}[.]){1,}[0-9][ ]', 1], ['INTERFACE', '(^INTERFACE: )', 0]];
	foreach ($data as $v) {
	    foreach ($finder as $f) {
		$check = preg_match_all("/$f[1]/", $v);
		if ($check) {
		    $preg_target = ($f[2] == 0) ? $f[1] : '';
		    $value = preg_replace("/$preg_target/", '', $v);
		    $res[$f[0]] = (($f[2] == 1 && isset($res[$f[0]]) && $res[$f[0]]) ? $res[$f[0]] . '<br>' : '') . $value;
		    break;
		} else {
		    if (!isset($res[$f[0]])) {
			$res[$f[0]] = '';
		    }
		}
	    }
	}
	return $res;
    }

    /**
     * [GET] ファイル読み込み
     * 
     * ログファイルごとのデータを取得します
     * 
     * @return array|null 全てのデータが取得できたら日別のログ（日別グループでの連想配列で行区切りに配列化）、それ以外はnullを返します
     */
    private static function loadFile() {
	$set_file = loadSetting();
	$dic = preg_replace('/\/{1,}$/', '', $set_file['logdirectory']) . '/*';
	$res = glob($dic);
	$res_data = ($res) ? [] : '';
	if ($res) {
	    foreach ($res as $r) {
		$date = preg_replace('/(^.*\/|.log$|trap_)/', '', $r);
		$ex_data = preg_replace('/([+][=][=][+]){1,}$/', '', file_get_contents($r));
		if (preg_match('/^([0-9]{4}[0-9]{2}[0-9]{2})$/', $date)) {
		    $key = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
		    $res_data[$key] = explode("\n", $ex_data);
		}
	    }
	}
	return $res_data;
    }
    
    /**
     * [SET] WarnData初期化
     * 
     * トラップログファイル読み込み・AgentData・MIBDataの準備を行います<br>
     * 本処理はWarnDataによる取得処理前に処理する必要があります
     * 
     * @return void 
     */
    private static function setWarn(): void {
	self::setOptionData();
	self::loadData();
    }

    /**
     * [SET] MIBData・AgentData定義
     * 
     * WarnDataで検索のために用いられるMIBData、AgentDataを警告専用に定義します
     * 
     * @return void MIBDataはフラグが3〜5のもののみを取得し、AgentDataは全てのエージェント情報を取得します
     */
    private static function setOptionData(): void {
	self::$agentdata = AGENTData::get_agent_info();
	$mib = new MIBData();
	self::$mibdata = $mib->getMIB(1, 2);
    }

    /**
     * [GET] エージェント検索
     * 
     * 現在のホストアドレス、コミュニティを元にエージェントを検索します
     * 
     * @return array|null 見つかった場合はAGENTHOST, COMMUNITYによる連想配列、見つからなかった場合はnullが返されます
     */
    private function searchAgent() {
	$res = '';
	if ($this->address && $this->community) {
	    foreach (self::$agentdata['VALUE'] as $a) {
		if ($a['AGENTHOST'] == $this->address && $a['COMMUNITY'] == $this->community) {
		    $res = $a;
		    break;
		} else if($a['AGENTHOST'] == $this->address) {
		    $res = $a;
		}
	    }
	}
	return $res;
    }
    
    /**
     * [GET] 警告情報結果取得
     * 
     * WarnDataから警告情報を配列で取得します<br>
     * 前段階として関連情報の定義・トラップログファイルの読み込みを行います
     * 
     * @return array VALUE, UPDATED_LOG, CSV, DATEによる連想配列を取得します
     */
    public static function getWarn() {
	self::setWarn();
	$res = ['VALUE' => [], 'UPDATED_LOG' => [], 'CSV' => '', 'DATE' => date("Y-m-d H:i:s")];
	foreach (self::$set as $warn) {
	    $data = $warn->getData();
	    $group = $data['GROUP'];
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

    /**
     * [GET] CSVデータ取得
     * 
     * WarnDataで取得したデータをもとにCSVデータの情報として加工します
     * 
     * @param array $data WarnDataで取得した配列データを指定します
     * @return string CSVデータを文字列として返します
     */
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
}
