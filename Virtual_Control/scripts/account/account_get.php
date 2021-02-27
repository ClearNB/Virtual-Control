<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../general/table.php';
include_once __DIR__ . '/account_data.php';
include_once __DIR__ . '/account_page.php';
include_once __DIR__ . '/account_table.php';
include_once __DIR__ . '/account_set.php';
include_once __DIR__ . '/general/session.php';

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

$code = 999;
$data = '要求されたデータはサーバ側で処理されませんでした';

$page = new AccountPage();

if ($f_id && session_chk() == 0) {
    switch ($f_id) {
	case 1: //SELECT
	    initialize();
	    $data = ACCOUNTData::get_all_users();
	    if ($data) {
		session_create('gsc_account', $data);
		$code = 1;
		$table = new AccountTable($data);
		$data = $table->generate_table();
	    }
	    break;
	case 3: //EDIT-SELECT
	    $s_data = ($d_tp == 0 && $p_id) ? set_selectdata(1, $p_id) : get_sdata();
	    $data = (isset($s_data['SELECT'])) ? $s_data['SELECT'] : $data;
	    $code = (isset($s_data['SELECT'])) ? 3 : $code;
	    break;
	case 2: case 4: case 5: case 6: case 7:
	    $type_code = ($f_id == 2) ? 0 : (($f_id == 7) ? 2 : 1);
	    if ($d_tp == 1) {
		$s_data = get_sdata();
		$res_data = set_function($type_code, $s_data, ['F_ID' => $f_id, 'P_ID' => $p_id, 'USERID' => $userid, 'USERNAME' => $username, 'A_PASS' => $a_pass, 'PASS' => $pass, 'R_PASS' => $r_pass, 'PERMISSION' => $permission]);
		$code = $res_data['CODE'];
		$data = isset($res_data['DATA']) ? $res_data['DATA'] : '';
	    } else if ($d_tp == 0) {
		$s_data = ($p_id) ? set_selectdata($type_code, $p_id) : get_sdata();
		$data = (isset($s_data['SELECT'])) ? $s_data['SELECT'] : (($f_id == 2) ? '' : $data);
		$code = (isset($s_data['SELECT']) || $f_id == 2) ? $f_id : $code;
	    }
	    break;
    }
}

$res = ['PAGE' => ''];

//FORM WARN
if ($code == 12 || $code == 15) {
    $res = ['CODE' => 2, 'PAGE' => $data];
} else {
    $res['PAGE'] = $page->get_page_byid($code, $data);
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
    $res = ['CODE' => 11, 'DATA' => '例外が発生しました'];
    $type_text = get_datatype($type);
    if (($type == 0 || isset($data['SELECT'])) && $type_text) {
	$si_data = set_accountdata($type, $values);
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
