<?php

/* Authentication Program
 * パスワード認証を行います。
 * ['res']
 * -1. 異常終了（データベース接続不可能）
 * 0. 正常終了
 * 1. 異常終了（ユーザまたはパスワードが間違っている）
 * 2. セッションエラー
 */

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../../403.php");
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);

include_once ('../sqldata.php');
include_once ('../common.php');
include_once ('../dbconfig.php');
include_once ('../session_chk.php');

if ($method == 'POST') {
    $pass = filter_input(INPUT_POST, 'at_pass', FILTER_SANITIZE_STRING);
    
    $r = 0;
    session_start_once();
    if (session_chk() != 0) {
	$r = 1;
    } else {
	$index = $_SESSION['mktk_userindex'];
	$result = select(true, "MKTK_USERS", "SALT", "WHERE USERINDEX = $index");
	if (!$result) {
	    $r = 2;
	} else {
	    $salt = $result['SALT'];
	    $hash = hash('sha256', $pass . $salt);
	    $result = select(true, "MKTK_USERS", "(PASSWORDHASH = '$hash') AS PASSWORD_MATCHES", "WHERE USERINDEX = $index");
	    $password_matches = $result['PASSWORD_MATCHES'];
	    if (!$password_matches) {
		$r = 1;
	    }
	}
    }
    //ob_get_clean();
    echo json_encode(['res' => $r]);
}