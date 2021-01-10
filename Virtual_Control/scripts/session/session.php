<?php

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
include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/session_chk.php';

session_action_scripts();

//値の取得
$userid = post_get_data('userid');
$pass = post_get_data('password');

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
