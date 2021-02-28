<?php

include_once __DIR__ . '/../snmp/snmp_walk.php';
include_once __DIR__ . '/../agent/agent_data.php';
include_once __DIR__ . '/../agent/agent_select.php';
include_once __DIR__ . '/../general/get.php';

class AnalyGet extends Get {

    private $agent_data;
    private $sub_data;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $request_code リクエストコードを指定します
     */
    public function __construct($request_code) {
	parent::__construct($request_code, 'vc_analy');
	$this->agent_data = post_get_data('sl_agt');
	$this->sub_data = post_get_data('sl_sub');
    }

    /**
     * [GET] ANALY処理
     * 
     * クラス内のデータをもとにデータ処理を行います
     * 
     * @return array CODE, DATAによる連想配列で返します（CODEはレスポンスコード、DATAはレスポンスデータとなります）
     */
    public function run(): array {
	$res = ['CODE' => 1, 'DATA' => '要求データを受け取れませんでした'];
	if (session_chk() == 0) {
	    switch ($this->request_code) {
		case 31: //AGENT_SELECT
		    $this->initialize();
		    $agentdata = AGENTData::get_agent_info();
		    $sl = new AgentSelect($agentdata);
		    $res['DATA'] = $sl->getSelect();
		    $res['CODE'] = 0;
		    break;
		case 32: case 35: //SNMP_WALK
		    if ($this->request_code == 35) {
			$res_data = $this->get_session();
			$this->agent_data = $res_data['AGENTID'];
		    }
		    if ($this->agent_data) {
			$data = get_walk_result($this->agent_data);
			$this->set_session($data);
			$res['DATA'] = $data;
			$res['CODE'] = $data['CODE'];
		    }
		    
		    break;
		case 33: //SUB GET
		    if ($this->sub_data) {
			$data = $this->get_session();
			if (isset($data['SUBDATA'][$this->sub_data])) {
			    $data['SUBDATA']['SELECT'] = $data['SUBDATA'][$this->sub_data];
			    $res['DATA'] = $data;
			    $res['CODE'] = 2;
			}
		    }
		    break;
		case 34: //BACK_RESULT
		    $data = $this->get_session();
		    if ($data) {
			$res['DATA'] = $data;
			$res['CODE'] = 1;
		    }
		    break;
	    }
	    if (ob_get_contents()) {
		$res['CODE'] = 3;
		$res['DATA'] = ob_get_contents();
		ob_clean();
	    }
	}
	return $res;
    }
}
