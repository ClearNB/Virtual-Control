<?php

/* Session Program
 * ログインセッション処理を行います。
 * ['res']
 * -1. 異常終了（ユーザまたはパスワードが間違っている）
 * 0. 正常終了
 * 1. 異常終了（データベース接続不可能）
 */

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg)
        ? strtolower($requestmg) : '';
if($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../403.php");
    exit;
}

//変数の定義
$session_time = 1500;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);

include_once ('../scripts/sqldata.php');
include_once ('../scripts/common.php');
include_once ('../scripts/dbconfig.php');

//フォーム実行時に動的実行される
if ($method == 'POST') {
    //値の取得
    $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    //ソルトの取得
    $result = select(true, "GSC_USERS", "SALT", "WHERE USERID = '$userid'");
    if(!$result) {
        $r_text = 1;
        echo json_encode(['res' => $r_text]);
    }
    $salt = $result['SALT'];

    //ない場合はログイン失敗
    if ($salt === "") {
        $r_text = -1;
        echo json_encode(['res' => $r_text]);
    } else {
        //ハッシュ化
        $hash = hash('sha256', $pass . $salt);

        //パスワード認証
        $result = select(true, "GSC_USERS", "(PASSWORDHASH = '$hash') AS PASSWORD_MATCHES", "WHERE USERID = '$userid'");
        $password_matches = $result['PASSWORD_MATCHES'];

        //パスワードがマッチしていたらセッション情報登録
        if ($password_matches) {
            $result = select(true, "GSC_USERS", "USERINDEX", "WHERE USERID = '$userid'");
            $userindex = $result['USERINDEX'];
            
            //セッションタイム（1500秒）およびセッション情報を出力する
            ini_set('session.gc_divisor', 1);
            ini_set('session.gc_maxlifetime', $session_time);
            session_start();
            $_SESSION['gsc_userindex'] = $userindex;
            //最終ログイン時間の更新
            $r = update("GSC_USERS", "LOGINUPTIME", "'" . date("Y-m-d H:i:s") . "'", "WHERE USERINDEX='$userindex'");
            if($r) {
                $r_text = 0;
            } else {
                $r_text = 1;
            }
            echo json_encode(['res' => $r_text]);
        } else {
            $r_text = -1;
            echo json_encode(['res' => $r_text]);
        }
    }
}