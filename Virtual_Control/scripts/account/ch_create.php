<?php

/* Session Program
 * アカウントを登録します。
 * ['res'] =>
 * 0. 正常終了
 * -1. 異常終了（データベース接続不可能）
 */
$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if($request !== 'xmlhttprequest') {
    http_response_code(403);
    header('Location: ../../403.php');
    exit;
}

include_once ('../sqldata.php');
include_once ('../common.php');
include_once ('../dbconfig.php');
include_once ('checkers.php');

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    
    //1. データの取得
    $userid = filter_input(INPUT_POST, 'cr_userid', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'cr_username', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'cr_pass', FILTER_SANITIZE_STRING);
    $r_pass = filter_input(INPUT_POST, 'cr_r_pass', FILTER_SANITIZE_STRING);
    $permission = filter_input(INPUT_POST, 'permission', FILTER_SANITIZE_STRING);
    $err_arr = array();
    
    //2. データの確認
    array_push($err_arr, check_userid($userid));
    array_push($err_arr, check_username($username));
    array_push($err_arr, check_password($pass));
    array_push($err_arr, check_conf_password($pass, $r_pass));
    
    $err_text = implode('<br>', array_filter($err_arr));
    $code = 0;
    if($err_text !== "") {
        $code = 1;
    } else {
	$salt = random(20);
	$hash = hash('sha256', $pass . $salt);
	$sql01 = insert('MKTK_USERS', ['USERID', 'USERNAME', 'PASSWORDHASH', 'PERMISSION', 'SALT'], [$userid, $username, $hash, $permission, $salt]);
	if(!$sql01) {
	    $code = 1;
	}
    }
    $r = ['res' => $code, 'data' => $err_text];
    ob_get_clean();
    echo json_encode($r);
}