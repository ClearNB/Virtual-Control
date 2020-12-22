<?php

/**
 * ログインセッション処理を行います。
 * 使用先: LOGIN
 * ['res']
 * 0. 正常終了
 * 1. 異常終了	（データベース接続不可能）
 * 2. 異常終了	（ユーザまたはパスワードが間違っている）
 */
class MySessionHandler implements SessionHandlerInterface {

    private $savePath;

    public function open($savePath, $sessionName) {
	$this->savePath = $savePath;
	if (!is_dir($this->savePath)) {
	    mkdir($this->savePath, 0777);
	}

	return true;
    }

    public function close() {
	return true;
    }

    public function read($id) {
	return (string) file_get_contents("$this->savePath/sess_$id");
    }

    public function write($id, $data) {
	return file_put_contents("$this->savePath/sess_$id", $data) === false ? false : true;
    }

    public function destroy($id) {
	$file = "$this->savePath/sess_$id";
	if (file_exists($file)) {
	    unlink($file);
	}

	return true;
    }

    public function gc($maxlifetime) {
	foreach (glob("$this->savePath/sess_*") as $file) {
	    if (filemtime($file) + $maxlifetime < time() && file_exists($file)) {
		unlink($file);
	    }
	}
	$userid = $_SESSION['gsc_userid'];
	update("GSC_USERS", "LOGINSTATE", 0, "WHERE USERID='$userid'");
	return true;
    }

}

include_once ('../general/sqldata.php');

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

    //ソルトの取得
    $result = select(true, "GSC_USERS", "SALT", "WHERE USERID = '$userid'");
    if (!$result) {
	$r_text = 1;
	echo json_encode(['res' => $r_text]);
    }
    $salt = $result['SALT'];

    //ない場合はログイン失敗
    if ($salt === "") {
	$r_text = 2;
    } else {
	//ハッシュ化
	$hash = hash('sha256', $pass . $salt);

	//パスワード認証
	$result = select(true, "GSC_USERS", "(PASSWORDHASH = '$hash') AS PASSWORD_MATCHES", "WHERE USERID = '$userid'");
	$password_matches = $result['PASSWORD_MATCHES'];

	//パスワードがマッチしていたらセッション情報登録
	if ($password_matches) {
	    $handler = new MySessionHandler();
	    session_set_save_handler($handler, true);
	    session_start();


	    $_SESSION['gsc_userid'] = $userid;
	    //最終ログイン時間の更新
	    $r1 = update("GSC_USERS", "LOGINSTATE", 1, "WHERE USERID='$userid'");
	    $r2 = update("GSC_USERS", "LOGINUPTIME", date("Y-m-d H:i:s"), "WHERE USERID='$userid'");
	    if ($r1 && $r2) {
		$r_text = 0;
	    } else {
		$r_text = 1;
	    }
	} else {
	    $r_text = 2;
	}
    }
    //ob_get_clean();
    echo json_encode(['res' => $r_text]);
}