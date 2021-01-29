<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../general/table.php';
include_once __DIR__ . '/accountdata.php';
include_once __DIR__ . '/account_page.php';
include_once __DIR__ . '/accounttable.php';
include_once __DIR__ . '/accountfunction.php';
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$f_id = post_get_data('f_id');
$d_tp = post_get_data('d_tp');
$p_id = post_get_data('p_id');
$userid = post_get_data('in_ac_id');
$username = post_get_data('in_ac_nm');
$permission = post_get_data('in_ac_pr');
$pass = post_get_data('in_ac_ps');
$r_pass = post_get_data('in_ac_ps_rp');
$a_pass = post_get_data('in_at_ps');

$res = ['SELECTED' => 0, 'PAGE' => ''];

$page = new AccountPage();
$result_page = $page->getFail();

if ($f_id && session_chk() == 0) {
    $code = 0;
    switch ($f_id) {
	case 1: //SELECT
	    initialize();
	    $data = ACCOUNTData::get_all_users();
	    if ($data) {
		session_create('gsc_account', $data);
		$table = new AccountTable($data);
		$table_data = $table->generate_table();
		$result_page = $page->getSelect($table_data);
	    }
	    break;
	case 2: //CREATE
	    if ($d_tp == 1) {
		$data = get_sdata();
		$res = set_function(0, $data, ['F_ID' => $f_id, 'P_ID' => $p_id, 'USERID' => $userid, 'USERNAME' => $username, 'A_PASS' => $a_pass, 'PASS' => $pass, 'R_PASS' => $r_pass, 'PERMISSION' => $permission]);
	    } else if ($d_tp == 0) {
		$result_page = $page->getCreate();
	    }
	    break;
	case 3: //EDIT SELECT
	    if ($d_tp == 0 && $p_id) {
		$data = set_selectdata(1, $p_id);
		if ($data) {
		    $result_page = $page->getEditSelect($data['SELECT']);
		}
	    } else if ($d_tp == 0) {
		$data = get_sdata();
		if (isset($data['SELECT'])) {
		    $result_page = $page->getEditSelect($data['SELECT']);
		}
	    }
	    break;
	case 4: //EDIT ID
	case 5: //EDIT NAME
	case 6: //EDIT PASS
	    $data = get_sdata();
	    if ($d_tp == 1) { //ACCOUNT_SET
		$res = set_function(1, $data, ['F_ID' => $f_id, 'P_ID' => $p_id, 'USERID' => $userid, 'USERNAME' => $username, 'A_PASS' => $a_pass, 'PASS' => $pass, 'R_PASS' => $r_pass, 'PERMISSION' => $permission]);
	    } else if ($d_tp == 0) {
		$result_page = $page->getEdit($f_id, $data['SELECT']);
	    }
	    break;
	case 7; //DELETE
	    $data = get_sdata();
	    if ($d_tp == 1) {
		$res = set_function(2, $data, ['F_ID' => $f_id, 'P_ID' => $p_id, 'USERID' => $userid, 'USERNAME' => $username, 'A_PASS' => $a_pass, 'PASS' => $pass, 'R_PASS' => $r_pass, 'PERMISSION' => $permission]);
	    } else if ($d_tp == 0 && $p_id) {
		$data = set_selectdata(2, $p_id);
		$result_page = $page->getDelete($data['SELECT']);
	    }
	    break;
    }
}
if ($res['SELECTED'] != 1) {
    $res['PAGE'] = $result_page;
}

if (isset($res['SELECTED'])) {
    unset($res['SELECTED']);
}

//ob_get_clean();
echo json_encode($res);

function initialize() {
    session_unset_byid('gsc_account');
    session_unset_byid('gsc_authid');
}

function get_sdata() {
    return session_get('gsc_account');
}

function get_datatype($type) {
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

function set_selectdata($type, $id) {
    $type_text = get_datatype($type);
    $data = get_sdata();
    $data['SELECT'] = (isset($data['VALUE'][$id])) ? $data['VALUE'][$id] : '';
    if (isset($data['SELECT']) && $type_text) {
	$data['SELECT'][$type_text]['P_ID'] = $id;
    }
    session_unset_byid('gsc_account');
    session_create('gsc_account', $data);
    return $data;
}

function set_accountdata($type, $value) {
    $type_text = get_datatype($type);
    $data = '';
    if ($type_text) {
	$data = get_sdata();
	foreach ($value as $k => $v) {
	    if (!isset($data['SELECT'][$type_text][$k]) || $v) {
		$data['SELECT'][$type_text][$k] = $v;
	    }
	}
	session_unset_byid('gsc_account');
	session_create('gsc_account', $data);
    }
    return $data;
}

function set_function($type, $data, $values) {
    $res = '';
    $type_text = get_datatype($type);
    $page = new AccountPage();
    if (($type == 0 || isset($data['SELECT'])) && $type_text) {
	$si_data = set_accountdata($type, $values);
	if ($si_data) {
	    $s_data = $si_data['SELECT'][$type_text];
	    $set = new AccountSet($s_data['F_ID'], $s_data['P_ID'], $s_data['USERID'], $s_data['USERNAME'], $s_data['A_PASS'], $s_data['PASS'], $s_data['R_PASS'], $s_data['PERMISSION']);
	    $set->check_functionid();
	    $runner = $set->run();
	    switch ($runner['CODE']) {
		case 0: $res = ['SELECTED' => 1, 'PAGE' => $page->getCorrect()];
		    break;
		case 1: $res = ['SELECTED' => 1, 'PAGE' => $page->getFail('データベースサーバへの接続に失敗しました。')];
		    break;
		case 2: $res = ['SELECTED' => 1, 'ID' => 'fm_warn', 'CODE' => 2, 'PAGE' => $runner['ERR_TEXT']];
		    break;
		case 3: $res = ['SELECTED' => 1, 'PAGE' => $page->getUserFail()];
		    break;
		case 4: $res = ['SELECTED' => 1, 'PAGE' => $page->fm_at()];
		    break;
		case 5: $res = ['SELECTED' => 1, 'ID' => 'fm_warn', 'CODE' => 2, 'PAGE' => '認証エラーが発生しました。'];
		    break;
		case 6: $res = ['SELECTED' => 1, 'PAGE' => $page->getConfirm($runner['CONFIRM_DATA'])];
		    break;
	    }
	}
    }
    return $res;
}
