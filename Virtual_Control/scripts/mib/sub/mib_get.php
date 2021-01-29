<?php

include_once __DIR__ . '/mib_set.php';

//SUB GET

class MIBSubGet extends MIBGetClass {

    private $parent_group_id;
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
	if (isset($this->session_data['SUB']['STORE'])) {
	    $this->sub_id = $this->get_storedata(0, 'SUB_ID');
	    $this->sub_oid = $this->get_storedata(0, 'SUB_OID');
	    $this->sub_name = $this->get_storedata(0, 'SUB_NAME');
	}
	if(isset($this->session_data['GROUP']['STORE'])) {
	    $this->parent_group_id = $this->session_data['GROUP']['STORE']['GROUP_ID'];
	}
	$this->sub_id = $request_data_code;
	$this->sub_oid = post_get_data_convert('in_sb_id', $this->sub_oid);
	$this->sub_name = post_get_data_convert('in_sb_nm', $this->sub_name);
	$this->auth_pass = post_get_data_convert('in_at_ps', $this->auth_pass);
    }

    public function run() {
	$data = '';
	$code = 1;
	switch ($this->request_code) {
	    case 30: //SELECT-INITIAL
		if($this->request_data_code) {
		    $this->set_storedata(0, $this->session_data['GROUP'][$this->request_data_code]);
		}
	    case 31: //SELECT-BACK
		$this->reset_store(1);
		$code = 20;
		break;
	    case 32: //CREATE
		if (($this->sub_oid && $this->sub_name) || $this->auth_pass) {
		    $code = $this->getSectionData();
		} else {
		    $code = 21;
		}
		break;
	    case 33: //EDIT-SELECT
		if ($this->request_data_code && isset($this->session_data['SUB'][$this->parent_group_id][$this->request_data_code])) {
		    if (!isset($this->session_data['SUB']['STORE'])) {
			$this->set_storedata(1, $this->session_data['SUB'][$this->parent_group_id][$this->request_data_code]);
		    }
		    $code = 22;
		}
		break;
	    case 34: //EDIT-OID
		if ($this->sub_id && $this->sub_oid || $this->auth_pass) {
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = isset($res['DATA']) ? $res['DATA'] : '';
		} else {
		    $code = 23;
		}
		break;
	    case 35: //EDIT-NAME
		if ($this->sub_id && $this->sub_name || $this->auth_pass) {
		    $res = $this->getSectionData();
		    $code = $res['CODE'];
		    $data = isset($res['DATA']) ? $res['DATA'] : '';
		} else {
		    $code = 24;
		}
		break;
	    case 36: //DELETE
		if ($this->parent_group_id && $this->request_data_code && isset($this->session_data['SUB'][$this->parent_group_id][$this->request_data_code])) {
		    if (!isset($this->session_data['SUB']['STORE'])) {
			$this->set_storedata(1, $this->session_data['SUB'][$this->parent_group_id][$this->request_data_code]);
			$code = 25;
		    } else if ($this->sub_id || $this->auth_pass) {
			$res = $this->getSectionData();
			$code = $res['CODE'];
			$data = (isset($res['DATA']) ? $res['DATA'] : '');
		    }
		}
		break;
	}
	if (!$data) {
	    $data = $this->session_data;
	}
	return ['CODE' => $code, 'DATA' => $data];
    }

    private function getSectionData() {
	$set = new MIBSubSet($this->request_code, $this->auth_pass, $this->group_id, $this->group_oid, $this->group_name);
	return $set->run();
    }

}
