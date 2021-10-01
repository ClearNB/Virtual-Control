<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../mib/mib_data.php';

/* [GET] エージェントID検索
 * ホストアドレスとコミュニティ名から、エージェントIDを検索します
 * @param string $hostaddress ホストアドレスを指定します
 * @param string $community コミュニティ名を指定します(コミュニティ名が不明の場合、アドレスだけで検索されます)
 * @return int エージェントIDが見つかったら、該当する番号を、それ以外は0を返します
 */

function searchAgentId($hostaddress, $community) {
    $res = 0;
    $sel = '';
    if ($community == '') {
	$sel = select(true, 'VC_AGENT', 'AGENTID', 'WHERE HOSTADDRESS = \'' . $hostaddress . '\'');
    } else {
	$sel = select(true, 'VC_AGENT', 'AGENTID', 'WHERE HOSTADDRESS = \'' . $hostaddress . '\' AND COMMUNITY = \'' . $community . '\'');
    }
    if ($sel) {
	$res = $sel['AGENTID'];
    }
    return $res;
}

/**
 * [GET] エージェントデータ取得
 * エージェントIDから、エージェントデータを抽出します
 * @param int $agentid エージェントIDを指定します
 * @return array|null 取得できたらHOSTADDRESS, COMMUNITYの連想配列、できなかったらnullを返します
 */
function getAgent($agentid) {
    $sel = select(true, 'VC_AGENT', 'HOSTADDRESS, COMMUNITY', 'WHERE AGENTID = ' . $agentid);
    return $sel;
}

class AgentData {

    private static $set = [];
    private $agentid;
    private $agenthost;
    private $community;
    private $uptime;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $agentid エージェントIDを指定します
     * @param string $agenthost エージェントホストアドレスを指定します
     * @param string $community コミュニティ名を指定します
     * @param string $agentuptime エージェント更新時間です
     */
    public function __construct($agentid, $agenthost, $community, $agentuptime) {
	$this->agentid = $agentid;
	$this->agenthost = $agenthost;
	$this->community = $community;
	$this->uptime = $this->get_time($agentuptime);
	array_push(self::$set, $this);
    }

    /**
     * [GET] エージェント情報取得
     * 
     * データベースに保存している全ての情報を取得します
     * 
     * @return array|null エージェントの全ての情報が取得できればその情報を配列で、それ以外はnullを返します
     */
    public static function get_agent_info() {
	$q01 = select(false, 'VC_AGENT', 'AGENTID, HOSTADDRESS, COMMUNITY, UPTIME');
	$q02 = select(false, 'VC_AGENT_MIB', 'AGENTID, GROUPID');
	if ($q01 && $q02) {
	    $result = [
		'VALUE' => [],
	    ];
	    //エージェントデータ取り込み
	    while ($var = $q01->fetch_assoc()) {
		new AGENTData($var['AGENTID'], $var['HOSTADDRESS'], $var['COMMUNITY'], $var['UPTIME']);
	    }
	    //エージェントデータを配列に格納
	    foreach (self::$set as $var) {
		if (!empty($var)) {
		    $result['VALUE'][$var->agentid] = $var->get_agent_data();
		}
	    }
	    //MIBデータ取り込み
	    while ($var = $q02->fetch_assoc()) {
		$agentid = $var['AGENTID'];
		$groupid = $var['GROUPID'];
		if (!isset($result['VALUE'][$agentid]['GROUPID'])) {
		    $result['VALUE'][$agentid]['GROUPID'] = [];
		}
		array_push($result['VALUE'][$agentid]['GROUPID'], $groupid);
	    }
	    return $result;
	} else {
	    return '';
	}
    }

    /**
     * [GET] オブジェクトデータ配列取得
     * 
     * オブジェクトのデータを配列で取得します
     * 
     * @return array AGENTID, HOSTADDRESS, COMMUNITY, UPDATETIMEをそれぞれ返します
     */
    private function get_agent_data(): array {
	return ['AGENTID' => $this->agentid,
	    'HOSTADDRESS' => $this->agenthost,
	    'COMMUNITY' => $this->community,
	    'UPTIME' => $this->uptime];
    }

    private function get_time($date): string {
	return isset($date) ? $date : '<新規>';
    }

}
