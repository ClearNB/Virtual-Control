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

$funid = post_get_data('f_id');
$userid = post_get_data('in_lg_id');
$pass = post_get_data('in_lg_ps');

$res = ['CODE' => 4, 'DATA' => '要求された内容は受け取れませんでした。'];

if ($funid && $funid == 12 && $userid && $pass) {
    $res['CODE'] = getState($userid, $pass);
    switch($res['CODE']) {
	case 1: //Database Error
	    $res['DATA'] = '<ul class="black-view"><li>ログインに失敗しました。</li><li>処理中にデータベースサーバへのクエリの処理が完了しませんでした。</li></ul>';
	    break;
	case 2: //Login Failed
	    $res['DATA'] = '<ul class="black-view"><li>ログインに失敗しました。</li><li>ユーザIDまたはパスワードが間違っています。</li></ul>';
	    break;
	case 3: //Correct
	    $res['DATA'] = '';
    }
} else if ($funid && $funid == 11) {
    $res['CODE'] = 0;
}

$page = new LoginPage($res['CODE'], $res['DATA']);
$res['PAGE'] = $page->getPage();

switch($res['CODE']) {
    case 0: case 4:
	unset($res['CODE']);
	unset($res['DATA']);
	break;
    case 1: case 2:
	$res['CODE'] = 1;
	break;
    case 3:
	unset($res['PAGE']);
	unset($res['DATA']);
	break;
}

echo json_encode($res);

/**
 * [GET] ログインセッション
 * 
 * ログイン認証を行い、ユーザがログインできるかどうか、ログイン状態をONにできるかどうかをチェックします
 * 
 * @param string $userid ユーザIDを指定します
 * @param string $pass パスワードを指定します
 * @return int (0..成功, 1..データベースエラー, 2..認証失敗, 3..)
 */
function getState($userid, $pass) {
    $code = session_auth_check($userid, $pass);
    if ($code == 0) {
	session_start_once();
	session_create('vc_userid', $userid);
	$r1 = update('VC_USERS', 'LOGINSTATE', 1, 'WHERE USERID = "' . $userid . '"');
	$r2 = update('VC_USERS', 'LOGINUPTIME', date('Y-m-d H:i:s'), 'WHERE USERID = "' . $userid . '"');
	$code = ($r1 && $r2) ? 3 : 1;
    }
    return $code;
}
