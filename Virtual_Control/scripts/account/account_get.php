<?php

include_once __DIR__ . '/../general/get.php';
include_once __DIR__ . '/account_data.php';
include_once __DIR__ . '/account_table.php';
include_once __DIR__ . '/account_set.php';

session_action_scripts();

class AccountGet extends Get {

    private $data_type;
    private $pre_userid;
    private $userid;
    private $username;
    private $pass;
    private $r_pass;
    private $permission;
    private $a_pass;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $request_code リクエストコードを指定します
     */
    public function __construct($request_code) {
	parent::__construct($request_code, 'vc_account');
	$this->data_type = post_get_data('d_tp');
	$this->pre_userid = post_get_data('p_id');
	$this->userid = post_get_data('in_ac_id');
	$this->username = post_get_data('in_ac_nm');
	$this->permission = post_get_data('in_ac_pr');
	$this->pass = post_get_data('in_ac_ps');
	$this->r_pass = post_get_data('in_ac_ps_rp');
	$this->a_pass = post_get_data('in_at_ps');
    }

    public function run(): array {
	$response_data = ['CODE' => 999, 'DATA' => '要求されたデータは受け取れませんでした。'];
	if ($this->request_code && session_chk() == 0) {
	    switch ($this->request_code) {
		case 71: //SELECT
		    $this->initialize();
		    $account_data = AccountData::get_all_users();
		    if ($account_data) {
			$this->set_session($account_data);
			$table = new AccountTable($account_data);
			$response_data = ['CODE' => 1, 'DATA' => $table->generate_table()];
		    }
		    break;
		case 72: //CREATE
		case 74: //EDIT ID
		case 75; //EDIT NAME
		case 76: //EDIT PASS
		case 77: //DELETE
		    $type_code = ($this->request_code == 2) ? 0 : (($this->request_code == 7) ? 2 : 1);
		    if ($this->data_type == 1) {
			$s_data = $this->get_session();
			$res_data = $this->set_function($type_code, $s_data, ['F_ID' => $this->request_code, 'P_ID' => $this->pre_userid, 'USERID' => $this->userid, 'USERNAME' => $this->username, 'A_PASS' => $this->a_pass, 'PASS' => $this->pass, 'R_PASS' => $this->r_pass, 'PERMISSION' => $this->permission]);
			$response_data = ['CODE' => $res_data['CODE'], 'DATA' => isset($res_data['DATA']) ? $res_data['DATA'] : ''];
		    } else if ($this->data_type == 0) {
			$s_data = ($this->pre_userid) ? $this->set_selectdata($type_code, $this->pre_userid) : $this->get_session();
			$response_data = ['CODE' => (isset($s_data['SELECT']) || $this->request_code == 72) ? $this->request_code % 10 : $response_data['CODE'],
			    'DATA' => (isset($s_data['SELECT'])) ? $s_data['SELECT'] : (($this->request_code == 72) ? '' : $response_data['DATA'])];
		    }
		    break;
		case 73: //EDIT-SELECT
		    $s_data = ($this->data_type == 0 && $this->pre_userid) ? $this->set_selectdata(1, $this->pre_userid) : $this->get_session();
		    $response_data = ['CODE' => (isset($s_data['SELECT'])) ? 3 : $response_data['CODE'], 'DATA' => (isset($s_data['SELECT'])) ? $s_data['SELECT'] : $response_data['DATA']];
		    break;
	    }
	}
	return $response_data;
    }

    protected function initialize() {
	parent::initialize();
	session_unset_byid('vc_authid');
    }

    private function get_datatype($type) {
	$type_text = '';
	switch ($type) {
	    case 0:
		$type_text = 'CREATE';
		break;
	    case 1:
		$type_text = 'EDIT';
		break;
	    case 2:
		$type_text = 'DELETE';
		break;
	}
	return $type_text;
    }

    private function set_selectdata($type, $id) {
	$type_text = $this->get_datatype($type);
	$data = $this->get_session();
	$data['SELECT'] = (isset($data['VALUE'][$id])) ? $data['VALUE'][$id] : '';
	if (isset($data['SELECT']) && $type_text) {
	    $data['SELECT'][$type_text]['P_ID'] = $id;
	}
	$this->reset_session($data);
	return $data;
    }

    function set_accountdata($type, $value) {
	$type_text = $this->get_datatype($type);
	$data = '';
	if ($type_text) {
	    $data = $this->get_session();
	    foreach ($value as $k => $v) {
		if (!isset($data['SELECT'][$type_text][$k]) || $v) {
		    $data['SELECT'][$type_text][$k] = $v;
		}
	    }
	    $this->reset_session($data);
	}
	return $data;
    }

    function set_function($type, $data, $values) {
	$res = ['CODE' => 11, 'DATA' => '例外が発生しました'];
	$type_text = $this->get_datatype($type);
	if (($type == 0 || isset($data['SELECT'])) && $type_text) {
	    $si_data = $this->set_accountdata($type, $values);
	    if ($si_data) {
		$s_data = $si_data['SELECT'][$type_text];
		$set = new AccountSet($s_data['F_ID'], $s_data['P_ID'], $s_data['USERID'], $s_data['USERNAME'], $s_data['A_PASS'], $s_data['PASS'], $s_data['R_PASS'], $s_data['PERMISSION']);
		$run = $set->run();
		switch ($run['CODE']) {
		    case 0: $res = ['CODE' => 10];
			break;
		    case 1: $res = ['CODE' => 11, 'DATA' => $run['DATA']];
			break;
		    case 2: $res = ['CODE' => 12, 'DATA' => $run['DATA']];
			break;
		    case 3: $res = ['CODE' => 13];
			break;
		    case 4: $res = ['CODE' => 14];
			break;
		    case 5: $res = ['CODE' => 15, 'DATA' => $run['DATA']];
			break;
		    case 6: $res = ['CODE' => 16, 'DATA' => $run['CONFIRM_DATA']];
			break;
		}
	    }
	}
	return $res;
    }
}