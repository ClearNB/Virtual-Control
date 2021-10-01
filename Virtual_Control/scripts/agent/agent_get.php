<?php

include_once __DIR__ . '/agent_data.php';
include_once __DIR__ . '/agent_page.php';
include_once __DIR__ . '/agent_select.php';
include_once __DIR__ . '/agent_set.php';
include_once __DIR__ . '/../mib/mib_data.php';
include_once __DIR__ . '/../mib/mib_select.php';
include_once __DIR__ . '/../general/get.php';

class AgentGet extends Get {

    private $sh_code;
    private $pre_agentid;
    private $agenthost;
    private $community;
    private $mib;
    private $a_pass;
    private $mib_group;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $request_code リクエストコードを指定します
     */
    public function __construct($request_code) {
	parent::__construct($request_code, 'vc_agent');
	$input = $this->get_input();
	$select = $this->get_select();
	$this->sh_code = post_get_data('d_tp');
	$this->pre_agentid = post_get_data('p_id');
	$this->agenthost = post_get_data('in_ag_hs');
	$this->community = post_get_data('in_ag_cm');
	$this->mib = post_get_data_array('sl_mb');
	$this->a_pass = post_get_data('in_at_ps');
	if ($select) { //SELECT
	    $this->pre_agentid = ($this->pre_agentid) ? $this->pre_agentid : $select['AGENTID'];
	    $this->agenthost = ($this->agenthost) ? $this->agenthost : $select['HOSTADDRESS'];
	    $this->community = ($this->community) ? $this->community : $select['COMMUNITY'];
	    $this->mib = ($this->mib) ? $this->mib : $select['MIB'];
	}
	if ($input) { //INPUT
	    $this->pre_agentid = $input['AGENTID'];
	    $this->agenthost = $input['HOSTADDRESS'];
	    $this->community = $input['COMMUNITY'];
	    $this->mib = $input['MIB'];
	}
    }

    /**
     * [SET] セッション初期化
     * 
     * MIBData, AuthIDをもとに初期化を行います
     */
    protected function initialize() {
	parent::initialize();
	session_unset_byid('vc_authid');
    }

    public function run(): array {
	$res_data = ['CODE' => 999, 'DATA' => '要求は受け取れませんでした。'];
	$b_req_code = 999;
	if ($this->request_code && session_chk() == 0) {
	    switch ($this->request_code) {
		case 81: //SELECT
		    $this->initialize();
		    $data = AgentData::get_agent_info();
		    if ($data) {
			$this->set_session($data);
			$a_s = new AgentSelect($data);
			$res_data = ['CODE' => 0, 'DATA' => $a_s->getSelect()];
		    }
		    break;
		case 82: //CREATE
		    if ($this->sh_code == 1) {
			$data = $this->get_session();
			$res_data = $this->set_function(0, $data, ['F_ID' => $this->request_code, 'P_ID' => $this->pre_agentid, 'HOSTADDRESS' => $this->agenthost, 'COMMUNITY' => $this->community, 'MIB' => $this->mib, 'A_PASS' => $this->a_pass]);
			$res_data['DATA'] = isset($res_data['DATA']) ? $res_data['DATA'] : '';
		    } else if ($this->sh_code == 0) {
			$this->mib_group = getMIBGroup();
			if ($this->mib_group) {
			    $select = new MIBSubSelect($this->mib_group);
			    $res_data = ['CODE' => 1, 'DATA' => $select->getSubSelectClear()];
			}
		    }
		    break;
		case 83: //EDIT SELECT
		    if ($this->sh_code == 0 && $this->pre_agentid) {
			$data = $this->set_select($this->pre_agentid, 'VALUE');
			if ($data) {
			    $res_data = ['CODE' => 2, 'DATA' => $data['SELECT']];
			}
		    } else if ($this->sh_code == 0) {
			$data = $this->get_select();
			if (isset($data['SELECT'])) {
			    $res_data = ['CODE' => 2, 'DATA' => $data['SELECT']];
			}
		    }
		    break;
		case 84: //EDIT HOST
		    $b_req_code = 3;
		case 85: //EDIT COMMUNITY
		    $b_req_code = ($b_req_code == 3) ? $b_req_code : 4;
		case 86: //EDIT OID
		    if ($b_req_code == 999) {
			$b_req_code = 5;
		    }
		    $data = $this->get_session();
		    if ($this->sh_code == 1) {
			switch($this->request_code) {
			    case 84:
				$this->community = "";
				$this->mib = "";
				break;
			    case 85:
				$this->agenthost = "";
				$this->mib = "";
				break;
			    case 86:
				$this->agenthost = "";
				$this->community = "";
				break;
			}
			$res_data = $this->set_function(1, $data);
			$res_data['DATA'] = isset($res_data['DATA']) ? $res_data['DATA'] : '';
		    } else if ($this->sh_code == 0) {
			$this->mib_group = getMIBGroup();
			if ($this->mib_group) {
			    $select = new MIBSubSelect($this->mib_group);
			    $res_data = ['CODE' => $b_req_code, 'DATA' => $data['SELECT']];
			    if ($this->request_code == 86) {
				$res_data['DATA']['MIBSELECT'] = $select->getSubSelectOnAgent($data['SELECT']['GROUPID']);
			    }
			}
		    }
		    break;
		case 87; //DELETE
		    $data = $this->get_session();
		    if ($this->sh_code == 1) {
			$this->agenthost = '';
			$this->community = '';
			$res_data = $this->set_function(2, $data);
			$res_data['DATA'] = isset($res_data['DATA']) ? $res_data['DATA'] : '';
		    } else if ($this->sh_code == 0 && $this->pre_agentid) {
			$sel_data = $this->set_select($this->pre_agentid, 'VALUE');
			$res_data = ['CODE' => 6, 'DATA' => $sel_data['SELECT']];
		    }
		    break;
	    }
	}
	return $res_data;
    }

    private function set_function($type, $data) {
	//1: 配列データを登録する
	if (!$this->get_input()) {
	    $this->set_input(['FUN_ID' => $this->request_code, 'A_PASS' => $this->a_pass, 'HOSTADDRESS' => $this->agenthost, 'COMMUNITY' => $this->community, 'AGENTID' => $this->pre_agentid, 'MIB' => $this->mib]);
	}
	$res = ['CODE' => 999, 'DATA' => '要求されたデータは受け入れられませんでした。'];
	if ($type == 0 || isset($data['SELECT'])) {
	    $set = new AgentSet($this->request_code, $this->a_pass, $this->agenthost, $this->pre_agentid, $this->community, $this->mib);
	    $res = $set->run();
	}
	return $res;
    }
}
