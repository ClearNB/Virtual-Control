<?php

include_once __DIR__ . '/../snmp/snmp_data.php';
include_once __DIR__ . '/../agent/agent_data.php';
include_once __DIR__ . '/../agent/agent_select.php';
include_once __DIR__ . '/../general/get.php';

class AnalyGet extends Get {

    private $agent_data;
    private $sub_data;
    private $ps_get_data;

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
	$this->ps_get_data = post_get_data('sl_gt_ps');
    }

    /**
     * [GET] ANALY処理
     * 
     * クラス内のデータをもとにデータ処理を行います
     * 
     * @return array CODE, DATAによる連想配列で返します（CODEはレスポンスコード、DATAはレスポンスデータとなります）
     */
    public function run(): array {
	$res = ['CODE' => 999, 'DATA' => '要求データを受け取れませんでした'];
	if (session_chk() == 0) {
	    switch ($this->request_code) {
		case 31: //AGENT_SELECT
		    $res = $this->getAgentSelectData();
		    break;
		case 32: case 35: //SNMP_WALK (32: START, 35: RESTART)
		    if ($this->request_code == 35) { //35: 選んだエージェントデータを取り出す
			$res_data = $this->get_session();
			$this->agent_data = $res_data['AGENTID'];
		    }
		    if ($this->agent_data) { //エージェントデータがない場合はWALKできない
			$data = getSNMPData($this->agent_data); //snmp_data.phpの関数に移動（SNMPWALKの結果を返す）
			if ($data['CODE'] == 1) { //1のときだけセッションに登録する
			    unset($data['DATA']);
			    $this->set_session($data, false);
			}
			$res['DATA'] = $data;
			$res['CODE'] = $data['CODE'];
		    }
		    break;
		case 33: //SUB GET
		    $data = $this->get_session();
		    if ($this->sub_data && $data && isset($data['SUB'][$this->sub_data])) {
			$res['DATA'] = $data['SUB'][$this->sub_data];
			$res['CODE'] = 2;
		    }
		    break;
		case 34: //BACK_RESULT
		    $data = $this->get_session();
		    if ($data) {
			$res['DATA'] = $data;
			$res['CODE'] = 1;
		    }
		    break;
		case 37: //GET_PAST
		    $res_data = $this->get_session();
		    $this->agent_data = $res_data['AGENTID'];
		    if ($this->ps_get_data && isset($res_data['PAST_DATA'][$this->ps_get_data])) {
			$past = getSNMPData($this->agent_data, $this->ps_get_data);
			if ($past['CODE'] == 1) {
			    unset($past['DATA']);
			    $this->set_session($past, false);
			}
			$res['DATA'] = $past;
			$res['CODE'] = $past['CODE'];
		    } else {
			$res['CODE'] = 3;
			$res['DATA'] = 'そのような過去履歴データは存在しません。';
		    }
		    break;
		case 38: //GET_PAST_SELECT
		    $data = ['AGENTID' => $this->agent_data];
		    $this->set_session($data, false);
		    if ($this->agent_data) {
			$past = getPastSelect($this->agent_data);
			$res['DATA'] = $past['DATA'];
			$res['CODE'] = $past['CODE'];
		    }
		    break;
		case 39: //GET_PAST (GET)
		    $data = $this->get_session();
		    if ($this->ps_get_data && isset($data['AGENTID'])) {
			$past = getSNMPData($data['AGENTID'], $this->ps_get_data);
			if ($past['CODE'] == 1) { //1のときだけセッションに登録する
			    $this->set_session($past, false);
			}
			$res['DATA'] = $past;
			$res['CODE'] = $past['CODE'];
		    } else {
			$res['CODE'] = 3;
			$res['DATA'] = 'そのような過去履歴データは存在しません。';
		    }
		    break;
	    }
	    if (ob_get_contents()) {
		$res['CODE'] = 3;
		$res['DATA'] = ob_get_contents();
		ob_get_clean();
	    }
	}
	return $res;
    }

    function getAgentSelectData() {
	$this->initialize();
	$agentdata = AGENTData::get_agent_info();
	$sl = new AgentSelect($agentdata);
	return ['DATA' => $sl->getSelect(), 'CODE' => 0];
    }

}
