<?php

include_once __DIR__ . '/../general/sqldata.php';

/**
 * [FUNCTIONS] セッション系処理ファンクション群
 * 
 * ここでは、セッションに関する全てのファンクションがあります<br>
 * セッション処理に関する呼び出しは、ここから行ってください
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
	$user = session_get_userdata();
	return $user && ($user['PERMISSION'] == 0);
    } else {
	return false;
    }
}

/**
 * [FUNCTION] VCServerページセッションアクション
 * 
 * セッション行動についてユーザ確認を行い、適切な処理を行います
 * 
 * @return void セッションにおいて、VCServerのアクセスでない場合、403.phpへセッションされます
 */
function session_action_vcserver(): void {
    if (!session_per_chk()) {
	http_response_code(403);
	header('location: /error.php');
	exit();
    }
}

/**
 * [FUNCTION] ページリクエスト判定ファンクション
 * 
 * ディレクトリ内における挙動について調べ、以下のアクセスの場合は拒否します<br>
 * ・XHTTPリクエスト通信でない場合<br>
 * ・リクエストメソッドがPOSTでない場合
 * 
 * @return void リクエスト内容において、サーバ自身のアクセスでない場合、403.phpへセッションされます
 */
function session_action_scripts(): void {
    $requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

    $request = isset($requestmg) ? strtolower($requestmg) : '';
    $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
    if ($request !== 'xmlhttprequest' && $method !== 'POST') {
	http_response_code(403);
	header('location: /error.php');
	exit();
    }
    ini_set('memory_limit', '200M');
}

/**
 * [FUNCTION] POST通信データ取得
 * 
 * POST通信のリクエストデータをIDにより取得します
 * 
 * @param string $post_id リクエスト通信で得られるIDを指定します
 * @param int $filter フィルタするメソッド定数を指定します（Default: FILTER_SANITIZE_STRING）
 * @return string データを文字列として返します
 */
function post_get_data($post_id, $filter = FILTER_SANITIZE_STRING) {
    $data = filter_input(INPUT_POST, $post_id, $filter);
    return $data;
}

/**
 * [FUNCTION] POST通信データ取得（データ比較）
 * 
 * POST通信のリクエストデータをIDにより取得します<br>
 * ここでは、POST通信のリクエストデータを取得できなかった場合に指定された変数のデータを返すことができます
 * 
 * @param string $post_id リクエスト通信で得られるIDを指定します
 * @param string|int $s_data nullの場合の埋め合わせ変数を指定します
 * @param int $filter フィルタするメソッド定数を指定します（Default: FILTER_SANITIZE_STRING）
 * @return string データが取得できた場合はそのデータを、できなかった場合は$s_dataのデータを返します
 */
function post_get_data_convert($post_id, $s_data, $filter = FILTER_SANITIZE_STRING) {
    $data = filter_input(INPUT_POST, $post_id, $filter);
    return ($data) ? $data : $s_data;
}

/**
 * [FUNCTION] POST通信データ取得
 * 
 * POST通信のリクエストデータ（配列）をIDにより取得します
 * 
 * @param string $post_id リクエスト通信で得られるIDを指定します
 * @param int $filter フィルタするメソッド定数を指定します（Default: FILTER_SANITIZE_STRING）
 * @return array フィルタされた値が配列として返されます
 */
function post_get_data_array($post_id) {
    $data = filter_input(INPUT_POST, $post_id, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    return $data;
}

/**
 * [FUNCTION] ユーザ判定ファンクション
 * 
 * セッション行動についてユーザ確認を行い、適切な処理を行います<br>
 * ユーザでない場合: /error.php へ<br>
 * データベースエラーの場合: /logout へ
 */
function session_action_user(): void {
    switch (session_chk()) {
	case 1:
	    http_response_code(403);
	    header('location: /error.php');
	    exit();
	    break;
	case 2: case 3:
	    http_response_code(301);
	    header('location: /logout');
	    exit();
	    break;
    }
}

/**
 * [FUNCTION] ゲスト判定ファンクション
 * 
 * セッション行動についてゲスト確認を行い、適切な処理を行います<br>
 * ユーザである場合: /dash へ<br>
 * データベースエラーの場合: /init へ
 */
function session_action_guest(): void {
    switch (session_chk()) {
	case 0:
	    http_response_code(301);
	    header('location: /dash');
	    exit();
	    break;
	case 3:
	    http_response_code(301);
	    header('location: /init.php');	    
	    exit();
	    break;
    }
}

/**
 * [FUNCTION] ユーザ判定ファンクション
 * 
 * ユーザ判定を行います
 * 
 * return int 0..正常（ユーザである）, 1..異常（ユーザではない）, 2..異常（データベースまたはログイン状態）, 3..整合性エラー（ユーザ未登録状態）
 */
function session_chk(): int {
    session_start_once();
    $chk = 1;
    if (session_exists('gsc_userid')) {
	$userid = session_get_userid();
	$res = select(true, "GSC_USERS", "LOGINSTATE", "WHERE USERID = '$userid'");
	$chk = ($res && $res['LOGINSTATE'] == 1) ? 0 : 2;
    }
    if($chk == 2) {
	$res = select(false, 'GSC_USERS', 'USERID');
	$chk = ($res) ? 2 : 3;
    }
    return $chk;
}

/**
 * [FUNCTION] AuthID認証
 * 
 * セッション情報に認証情報があるかどうかを判定します<br>
 * AuthID判定: AuthID存在確認・権限確認・AuthID一致確認
 * 
 * @return bool すべての確認でtrueでtrue, そうでない場合はfalse
 */
function session_auth(): bool {
    session_start_once();
    return isset($_SESSION['gsc_authid']) && ($_SESSION['gsc_authid'] == $_SESSION['gsc_userid']) && session_per_chk();
}

/**
 * [FUNCTION] セッション認証チェック
 * 
 * セッションに必要なユーザIDとパスワードを用意し、それで認証を行います
 * 
 * @param string $userid ユーザIDを指定します
 * @param string $pass $useridに対してのパスワードを指定します
 * @return int 成功した場合は0、データベースが原因で失敗した場合は1、ユーザIDまたはパスワードが違う場合は2を返します
 */
function session_auth_check($userid, $pass, $isauthid = false): int {
    $res = 0;

    $q01 = select(true, "GSC_USERS", "SALT", "WHERE USERID = '$userid'");
    $salt = '';
    if (!$q01) {
	$res = 1;
    } else {
	$salt = $q01['SALT'];
    }

    if (!$salt) {
	$res = 2;
    } else {
	$hash = hash('sha256', $pass . $salt);

	$result = select(true, "GSC_USERS", "(PASSWORDHASH = '$hash') AS PASSWORD_MATCHES", "WHERE USERID = '$userid'");
	$password_matches = $result['PASSWORD_MATCHES'];

	if ($password_matches) {
	    if ($isauthid) {
		session_create('gsc_authid', $userid);
	    }
	} else {
	    $res = 2;
	}
    }
    return $res;
}

/**
 * [FUNCTION] セッションユーザ取得
 * 
 * 現在のセッションからユーザ情報を取得します<br>
 * ユーザID, ユーザ名, 権限, ログイン状態を取得可能です<br>
 * 【重要】必ずセッションチェックを行ってから
 * 
 * @return array [USERID, USERNAME, PERMISSION, PERMISSION_TEXT, LOGINSTATE, LOGINSTATE_TEXT]を構成する配列を返します
 */
function session_get_userdata(): array {
    session_start_once();
    $userid = session_get_userid();
    $sql = select(true, "GSC_USERS", "USERID, USERNAME, PERMISSION, LOGINSTATE", "WHERE USERID = '$userid'");
    session_set_data($sql);
    return $sql;
}

/**
 * [FUNCTION] セッションユーザID取得
 * 
 * セッションがある場合、セッションのユーザIDを取得します
 * 
 * @return string|null セッションがある場合はそのユーザIDを返します
 */
function session_get_userid(): string {
    return session_get('gsc_userid');
}

/**
 * [FUNCTION] セッションアカウントデータ設定
 * 
 * 権限・ログイン状態をテキストとして設定します<br>
 * もとの値は保持され、PERMISSION_TEXT, LOGINSTATE_TEXT が追加されます
 * 
 * @param array $sql アカウントデータを指定します
 * @return void もとの値は保持され、PERMISSION_TEXT, LOGINSTATE_TEXT が追加されます
 */
function session_set_data(&$sql): void {
    switch ($sql['PERMISSION']) {
	case 0:
	    $sql['PERMISSION_TEXT'] = 'VCServer';
	    break;
	case 1:
	    $sql['PERMISSION_TEXT'] = 'VCHost';
    }
    switch ($sql['PERMISSION']) {
	case 0:
	    $sql['LOGINSTATE_TEXT'] = 'ログインしていません';
	    break;
	case 1:
	    $sql['LOGINSTATE_TEXT'] = 'ログイン中';
	    break;
    }
}

/**
 * [FUNCTION] セッション登録
 * 
 * セッションIDとその値を指定して、セッションが加えられることを確認します<br>
 * 
 * @param string $sessionid セッションID
 * @param string $value セッション値
 * @return bool セッション登録に成功した場合はtrue、そうでない場合はfalseを返します
 */
function session_create($sessionid, $value): bool {
    session_start_once();
    $_SESSION[$sessionid] = $value;
    session_regenerate_id();
    return (isset($_SESSION[$sessionid]));
}

/**
 * [FUNCTION] セッション破棄
 * 
 * 対象セッションIDのセッションを破棄します
 * 
 * @return bool セッションの破棄に成功した場合はtrue、そうでない場合はfalseを返します
 */
function session_unset_byid($sessionid): bool {
    session_start_once();
    unset($_SESSION[$sessionid]);
    return !isset($_SESSION[$sessionid]);
}

/**
 * [FUNCTION] セッション情報取得
 * 
 * 指定したセッションIDで情報を取得します
 * 
 * @param string $sessionid セッションID
 * @return int|string|array|null セッション情報が取得できたら対象の情報を、そうでない場合はnullを返します
 */
function session_get($sessionid) {
    session_start_once();
    return (isset($_SESSION[$sessionid]) ? $_SESSION[$sessionid] : '');
}

/**
 * [FUNCTION] セッション情報確認
 * 
 * 指定したセッションIDが存在するかどうかを確認します
 * 
 * @param string $sessionid セッションID
 * @return bool セッション情報がある場合はtrue、そうでない場合はfalseを返します
 */
function session_exists($sessionid): bool {
    session_start_once();
    return isset($_SESSION[$sessionid]);
}