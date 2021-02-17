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
include_once __DIR__ . '/login_page.php';
include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../general/session.php';

session_action_scripts();

$funid = post_get_data('fun_id');
$userid = post_get_data('in_lg_id');
$pass = post_get_data('in_lg_ps');

$page = new LoginPage();
$res = ['CODE' => 0];

if ($funid && $funid == 12 && $userid && $pass) {
    $res['CODE'] = getState($userid, $pass);
} else if ($funid && $funid == 11) {
    $res['CODE'] = 0;
} else {
    $res['CODE'] = 999;
}

switch ($res['CODE']) {
    case 0: case 999: $res['PAGE'] = $page->getpage_bycode($res['CODE']);
	break;
    case 1: $res['PAGE'] = '<ul class="black-view"><li>ログインに失敗しました。</li><li>処理中にデータベースサーバへのクエリの処理が完了しませんでした。</li></ul>';
	break;
    case 2: $res['PAGE'] = '<ul class="black-view"><li>ログインに失敗しました。</li><li>ユーザIDまたはパスワードが間違っています。</li></ul>';
	break;
}

echo json_encode($res);

function getState($userid, $pass) {
    $code = session_auth_check($userid, $pass);
    if ($code == 0) {
	session_start_once();
	$_SESSION['gsc_userid'] = $userid;
	$r1 = update('GSC_USERS', 'LOGINSTATE', 1, 'WHERE USERID = "' . $userid . '"');
	$r2 = update('GSC_USERS', 'LOGINUPTIME', date('Y-m-d H:i:s'), 'WHERE USERID = "' . $userid . '"');
	$code = ($r1 && $r2) ? 3 : 1;
    }
    return $code;
}
