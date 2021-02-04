<?php

include_once __DIR__ . '/mib_set.php';

//SUB GET

class MIBSubGet extends MIBGetClass {

    private $pre_group_id;
    private $sub_id;
    private $sub_oid;
    private $sub_name;
    private $auth_pass;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです<br>
     * MIBGetClassを継承しているため、以下の引数が必要です
     * 
     * @param int $request_code リクエストコードを指定します
     * @param int $request_data_code リクエストデータコードを指定します
     */
    public function __construct($request_code, $request_data_code) {
	parent::__construct($request_code, $request_data_code);
	if ($this->request_data_code == 999) {
	    $this->reset_types(1, 0);
	    $this->reset_authid();
	}
	if ($this->is_set(1, 0)) {
	    $this->sub_id = $this->get_typesdata(1, 0, 'SUB_ID');
	    $this->sub_oid = $this->get_typesdata(1, 0, 'SUB_OID');
	    $this->sub_name = $this->get_typesdata(1, 0, 'SUB_NAME');
	}
	if ($this->is_set(1, 1)) {
	    $this->sub_id = $this->get_typesdata(1, 1, 'SUB_ID');
	}
	if ($this->is_set(0, 1)) {
	    $this->pre_group_id = $this->get_typesdata(0, 1, 'GROUP_ID');
	}
	$this->sub_id = ($this->sub_id) ? $this->sub_id : (($request_code != 30 && $request_code != 31 && $request_code != 32) ? $request_data_code : '');
	$this->sub_oid = post_get_data_convert('in_sb_id', $this->sub_oid);
	$this->sub_name = post_get_data_convert('in_sb_nm', $this->sub_name);
	$this->auth_pass = post_get_data('in_at_ps');
    }

    public function run() {
	$data = '';
	$code = 1;
	switch ($this->request_code) {
	    case 30: //SELECT-INITIAL
		if (!$this->is_set(0, 1) && !$this->pre_group_id && $this->request_data_code) {
		    $this->pre_group_id = $this->request_data_code;
		}
	    case 31: //SELECT-BACK
		//DATA REBASE
		$this->create_session();
		$this->set_data(0, 1, $this->session_data['GROUP'][$this->pre_group_id]);
		$this->set_data(1, 2, isset($this->session_data['SUB'][$this->pre_group_id]) ? $this->session_data['SUB'][$this->pre_group_id] : []);
		
		$this->reset_types(1, 0);
		$this->reset_types(1, 1);
		$this->reset_authid();
		$code = 20;
		break;
	    case 32: //CREATE
		if ($this->request_data_code !== 999 && ($this->sub_oid && $this->sub_name) || $this->auth_pass) {
		    if ($this->sub_oid && $this->sub_name) {
			$this->set_data(1, 0, ['SUB_OID' => $this->sub_oid, 'SUB_NAME' => $this->sub_name]);
		    }
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = isset($res['DATA']) ? $res['DATA'] : '';
		} else {
		    $code = 21;
		}
		break;
	    case 33: //EDIT-SELECT
		if ($this->request_data_code && $this->is_set(1, 2, $this->request_data_code)) {
		    $this->set_data(1, 1, $this->get_typesdata(1, 2, $this->request_data_code));
		    $this->set_data(1, 0, $this->get_typesdata(1, 1, 'SUB_ID'));
		    $code = 22;
		}
		break;
	    case 34: //EDIT-OID
		if ($this->request_data_code !== 999 && $this->sub_oid || $this->auth_pass) {
		    $this->set_data(1, 0, ['SUB_OID' => $this->sub_oid]);
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = isset($res['DATA']) ? $res['DATA'] : '';
		} else {
		    $code = 23;
		}
		break;
	    case 35: //EDIT-NAME
		if ($this->request_data_code !== 999 && $this->sub_name || $this->auth_pass) {
		    $this->set_data(1, 0, ['SUB_NAME' => $this->sub_name]);
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = isset($res['DATA']) ? $res['DATA'] : '';
		} else {
		    $code = 24;
		}
		break;
	    case 36: //DELETE
		if ($this->request_data_code != 999 && $this->sub_id && $this->is_set(1, 1) || $this->auth_pass) {
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = (isset($res['DATA']) ? $res['DATA'] : '');
		} else {
		    if (!$this->is_set(1, 1)) {
			$this->set_data(1, 1, $this->get_typesdata(1, 2, $this->request_data_code));
			$this->set_data(1, 0, $this->get_typesdata(1, 1, 'SUB_ID'));
		    }
		    $code = 25;
		}
		break;
	}
	if (!$data) {
	    $data = $this->session_data;
	}
	return ['CODE' => $code, 'DATA' => $data];
    }

    private function getSectionData() {
	$set = new MIBSubSet($this->request_code, $this->auth_pass, $this->sub_id, $this->pre_group_id, $this->sub_oid, $this->sub_name, $this->session_data);
	return $set->run();
    }

}
