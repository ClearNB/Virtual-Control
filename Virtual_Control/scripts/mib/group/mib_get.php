<?php

include_once __DIR__ . '/mib_set.php';

//GROUP GET

class MIBGroupGet extends MIBGetClass {

    private $group_id;
    private $group_oid;
    private $group_name;
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
	    $this->reset_types(0, 0);
	    $this->reset_authid();
	}
	if ($this->is_set(0, 0)) {
	    $this->group_id = $this->get_typesdata(0, 0, 'GROUP_ID');
	    $this->group_oid = $this->get_typesdata(0, 0, 'GROUP_OID');
	    $this->group_name = $this->get_typesdata(0, 0, 'GROUP_NAME');
	}
	if ($this->is_set(0, 1)) {
	    $this->group_id = $this->get_typesdata(0, 1, 'GROUP_ID');
	}
	$this->group_id = ($this->group_id) ? $this->group_id : $request_data_code;
	$this->group_oid = post_get_data_convert('in_gp_id', $this->group_oid);
	$this->group_name = post_get_data_convert('in_gp_nm', $this->group_name);
	$this->auth_pass = post_get_data('in_at_ps');
    }

    public function run() {
	$data = '';
	$code = 1;
	switch ($this->request_code) {
	    case 20: //SELECT-INITIAL
		$this->create_session();
	    case 21: //SELECT-BACK
		$this->reset_types(0, 0);
		$this->reset_types(0, 1);
		$this->reset_authid();
		$code = 10;
		break;
	    case 22: //CREATE
		if ($this->request_data_code !== 999 && $this->group_oid && $this->group_name || $this->auth_pass) {
		    if ($this->group_oid && $this->group_name) {
			$this->set_data(0, 0, ['GROUP_OID' => $this->group_oid, 'GROUP_NAME' => $this->group_name]);
		    }
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = isset($res['DATA']) ? $res['DATA'] : '';
		} else {
		    $code = 11;
		}
		break;
	    case 23: //EDIT-SELECT
		if ($this->request_data_code && isset($this->session_data['GROUP'][$this->request_data_code])) {
		    if (!$this->is_set(0, 1)) {
			$this->set_data(0, 1, $this->session_data['GROUP'][$this->request_data_code]);
			$this->set_data(0, 0, $this->get_typesdata(0, 1, 'GROUP_ID'));
		    }
		    $code = 12;
		}
		break;
	    case 24: //EDIT-OID
		if ($this->request_data_code !== 999 && $this->group_oid || $this->auth_pass) {
		    $this->set_data(0, 0, ['GROUP_ID' => $this->group_id, 'GROUP_OID' => $this->group_oid]);
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = isset($res['DATA']) ? $res['DATA'] : '';
		} else {
		    $code = 13;
		}
		break;
	    case 25: //EDIT-NAME
		if ($this->request_data_code !== 999 && $this->group_name || $this->auth_pass) {
		    $this->set_data(0, 0, ['GROUP_ID' => $this->group_id, 'GROUP_OID' => $this->group_oid]);
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = isset($res['DATA']) ? $res['DATA'] : '';
		} else {
		    $code = 14;
		}
		break;
	    case 26: //DELETE
		if ($this->request_data_code != 999 && $this->group_id && $this->is_set(0, 1) || $this->auth_pass) {
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = (isset($res['DATA']) ? $res['DATA'] : '');
		} else {
		    if (!$this->is_set(0, 1)) {
			$this->set_data(0, 1, $this->session_data['GROUP'][$this->request_data_code]);
			$this->set_data(0, 0, $this->get_typesdata(0, 1, 'GROUP_ID'));
		    }
		    $code = 15;
		}
		break;
	}
	if (!$data) {
	    $data = $this->session_data;
	}
	return ['CODE' => $code, 'DATA' => $data];
    }

    private function getSectionData() {
	$set = new MIBGroupSet($this->request_code, $this->auth_pass, $this->group_id, $this->group_oid, $this->group_name);
	return $set->run();
    }

}
