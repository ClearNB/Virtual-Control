<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/session_chk.php';

/**
 * [FUNCTION] セッション処理
 * 
 * ログインセッション処理を行います。
 * 使用先: LOGIN
 * ['res']
 * 0. 正常終了
 * 1. 異常終了	（データベース接続不可能）
 * 2. 異常終了	（ユーザまたはパスワードが間違っている）
 */

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../403.php");
    exit;
}

//変数の定義
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);

//フォーム実行時に動的実行される
if ($method == 'POST') {
    //値の取得
    $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $code = session_auth_check($userid, $pass);
    if ($code == 0) {
	session_start_once();

	$_SESSION['gsc_userid'] = $userid;
	//最終ログイン時間の更新
	$r1 = update("GSC_USERS", "LOGINSTATE", 1, "WHERE USERID = '$userid'");
	$r2 = update("GSC_USERS", "LOGINUPTIME", date("Y-m-d H:i:s"), "WHERE USERID = '$userid'");
	if ($r1 && $r2) {
	    $code = 0;
	} else {
	    $code = 1;
	}
    } else {
	$code = 2;
    }
    //ob_get_clean();
    echo json_encode(['res' => $code]);
}