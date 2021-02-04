<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../mib/mibdata.php';

class AGENTData {

    private static $set = [];
    private $agentid;
    private $agenthost;
    private $community;
    private $agentuptime;

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
	$this->agentuptime = $this->get_time($agentuptime);
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
	$q01 = select(false, 'GSC_AGENT', 'AGENTID, AGENTHOST, COMMUNITY, AGENTUPTIME');
	$q02 = select(false, 'GSC_AGENT_MIB', 'AGENTID, SID');
	if ($q01 && $q02) {
	    $result = [
		'VALUE' => [],
	    ];
	    //エージェントデータ取り込み
	    while ($var = $q01->fetch_assoc()) {
		new AGENTData($var['AGENTID'], $var['AGENTHOST'], $var['COMMUNITY'], $var['AGENTUPTIME']);
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
		$subid = $var['SID'];
		if (!isset($result['VALUE'][$agentid]['SUBID'])) {
		    $result['VALUE'][$agentid]['SUBID'] = [];
		}
		array_push($result['VALUE'][$agentid]['SUBID'], $subid);
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
     * @return array AGENTID, AGENTHOST, COMMUNITY, UPDATETIMEをそれぞれ返します
     */
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
