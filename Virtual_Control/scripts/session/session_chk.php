<?php

/**
 * [FUNCTIONS] セッション系処理ファンクション群
 * 
 * ここでは、セッションに関する全てのファンクションがあります。<br>
 * セッション処理に関する呼び出しは、ここから行ってください。
 * 
 * @package VirtualControl_scripts_session
 * @author ClearNB<clear.navy.blue.star@gmail.com>
 */

/**
 * [FUNCTION] セッション開始（既実行確認）
 * 
 * セッションを開始します<br>
 * （すでに開始している場合は開始されません）
 */
function session_start_once(): void {
    if (session_status() == PHP_SESSION_NONE) {
	session_start();
    }
}

/**
 * [FUNCTION] セッション・権限確認
 * 
 * セッションおよび権限情報を判定します<br>
 * （セッションがある かつ 照合したユーザ情報が一致する かつ VCServerである）
 * @return bool
 */
function session_per_chk(): bool {
    if (session_chk() == 0) {
	$id = $_SESSION['gsc_userid'];
	$sql = select(true, 'GSC_USERS', 'PERMISSION', 'WHERE USERID = "' . $id . '"');
	return $sql && ($sql['PERMISSION'] == 0);
    } else {
	return false;
    }
}

/**
 * [FUNCTION] VCServerページセッションアクション
 * 
 * セッション行動についてユーザ確認を行い、適切な処理を行います
 * @param type $isdirectory
 * @return void セッションにおいて、VCServerのアクセスでない場合、403.phpへセッションされます
 */
function session_action_vcserver(): void {
    if (!session_per_chk()) {
	http_response_code(301);
	header('location: ../403.php');
	exit();
    }
}

/**
 * 【ユーザ専用】
 * セッション行動についてユーザ確認を行い、適切な処理を行います
 * 1.. 403.php へ
 * 2.. logout.php へ
 * @param bool	$isdirectory	ディレクトリ内のページかどうか（Default: false）
 */
function session_action_user(): void {
    switch (session_chk()) {
	case 1:
	    http_response_code(301);
	    header('location: ./403.php');
	    exit();
	    break;
	case 2:
	    http_response_code(301);
	    header('location: ./logout.php');
	    exit();
	    break;
    }
}

/**
 * 【ゲスト専用】
 * セッション行動についてユーザ確認を行い、適切な処理を行います
 * 0.. dash.php へ
 * 2.. logout.php へ
 */
function session_action_guest(): void {
    switch (session_chk()) {
	case 0:
	    http_response_code(301);
	    header('location: ./dash.php');
	    exit();
	    break;
	case 2:
	    http_response_code(301);
	    header('location: ./logout.php');
	    exit();
	    break;
    }
}

/**
 * セッション情報があるかどうかを判定します。
 * (0..ユーザ, 1..ゲスト, 2..ログアウト)
 * @return bool
 */
function session_chk() {
    session_start_once();
    $chk = 1;
    if (isset($_SESSION['gsc_userid'])) {
	$userid = $_SESSION['gsc_userid'];
	$res = select(true, "GSC_USERS", "LOGINSTATE", "WHERE USERID = '$userid'");
	if ($res && $res['LOGINSTATE'] == 1) {
	    $chk = 0;
	} else {
	    $chk = 2;
	}
    }
    return $chk;
}

/**
 * セッション情報に認証情報があるかどうかを判定します
 * AuthID判定: AuthID存在確認・権限確認・AuthID一致確認
 * （すべての確認でtrueでtrue, そうでない場合はfalse）
 * @return bool
 */
function session_auth(): bool {
    session_start_once();
    return isset($_SESSION['gsc_authid']) && ($_SESSION['gsc_authid'] == $_SESSION['gsc_userid']) && session_per_chk();
}

/**
 * 現在のセッションからユーザ情報を取得します。
 * ユーザID, ユーザ名, 権限, ログイン状態を取得可能です。
 * 【重要】必ずセッションチェックを行ってからやりましょう
 * [USERID, USERNAME, PERMISSION, PERMISSION_TEXT, LOGINSTATE, LOGINSTATE_TEXT]
 * @return array
 */
function session_get_userdata(): array {
    $userid = $_SESSION['gsc_userid'];
    $sql = select(true, "GSC_USERS", "USERID, USERNAME, PERMISSION, LOGINSTATE", "WHERE USERID = '$userid'");
    session_set_data($sql);
    return $sql;
}

/**
 * 権限・ログイン状態をテキストとして設定します。
 * もとの値は保持され、PERMISSION_TEXT, LOGINSTATE_TEXT が追加されます。
 * @param array $sql
 * @return void
 */
function session_set_data(&$sql): void {
    switch($sql['PERMISSION']) {
	case 0:
	    $sql['PERMISSION_TEXT'] = 'VCServer';
	    break;
	case 1:
	    $sql['PERMISSION_TEXT'] = 'VCHost';
    }
    switch($sql['PERMISSION']) {
	case 0:
	    $sql['LOGINSTATE_TEXT'] = 'ログインしていません';
	    break;
	case 1:
	    $sql['LOGINSTATE_TEXT'] = 'ログイン中';
	    break;
    }
}