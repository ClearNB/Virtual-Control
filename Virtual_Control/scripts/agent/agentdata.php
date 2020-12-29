<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../mib/mibdata.php';

class AGENTData {

    private static $set = [];
    private $agentid;
    private $agenthost;
    private $community;
    private $agentuptime;

    public function __construct($agentid, $agenthost, $community, $agentuptime) {
	$this->agentid = $agentid;
	$this->agenthost = $agenthost;
	$this->community = $community;
	$this->agentuptime = $this->get_time($agentuptime);
	array_push(self::$set, $this);
    }

    public static function get_agent_info() {
	$q01 = select(false, 'GSC_AGENT', 'AGENTID, AGENTHOST, COMMUNITY, AGENTUPTIME');
	$q02 = select(false, 'GSC_AGENT_MIB', 'AGENTID, SUBID');
	if ($q01 && $q02) {
	    $result = [
		"COLUMN" => [
		    ["エージェントID", "ホストアドレス", "コミュニティ名", "最終更新日時"],
		    ["AGENTID", "AGENTHOST", "COMMUNITY", "AGENTUPTIME"]
		],
		"VALUE" => [],
		"SUBID" => []
	    ];
	    //エージェントデータ取り込み
	    while ($var = $q01->fetch_assoc()) {
		new AGENTData($var['AGENTID'], $var['AGENTHOST'], $var['COMMUNITY'], $var['AGENTUPTIME']);
	    }
	    //MIBデータ取り込み
	    while ($var = $q02->fetch_assoc()) {
		$agentid = $var['AGENTID'];
		$subid = $var['SUBID'];
		if(!isset($result['SUBID'][$agentid])) {
		    $result['SUBID'][$agentid] = [];
		}
		array_push($result['SUBID'][$agentid], $subid);
	    }
	    //エージェントデータを配列に格納
	    foreach (self::$set as $var) {
		if(!empty($var) && isset($result['SUBID'][$var->agentid])) {
		    array_push($result['VALUE'], $var->get_agent_data());
		}
	    }
	    return $result;
	} else {
	    return false;
	}
    }

    private function get_agent_data(): array {
	return ['AGENTID' => $this->agentid,
	    'AGENTHOST' => $this->agenthost,
	    'COMMUNITY' => $this->community,
	    'AGENTUPTIME' => $this->agentuptime];
    }

    private function get_time($date): string {
	if ($date) {
	    return $date;
	} else {
	    return '<新規>';
	}
    }
}