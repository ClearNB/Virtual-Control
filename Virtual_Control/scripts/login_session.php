<?php

//変数の定義
$userid = '';
$pass = '';
$salt = '';
$username = '';
$permission = 0;
$session_time = 1500;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
$chk_post = false;
$chk_failed = false;
$chk_nouser = false;

//include
include ("dbconfig.php");
include ("common.php");

//フォーム実行時に動的実行される
if ($method == 'POST') {
    //表示フラグの変更
    $chk_post = true;

    //値の取得
    $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);

    //ソルトの取得
    $query = "
        SELECT SALT
        FROM   GSC_USERS
        WHERE  USERID = '$userid'
    ";
    $result = query($query);


    while ($row = $result->fetch_assoc()) {
        $salt = $row['SALT'];
    }

    //ない場合はログイン失敗
    if ($salt === "") {
        $chk_nouser = true;
    } else {
        //ハッシュ化
        $hash = hash('sha256', $pass . $salt);

        $query2 = "
            SELECT (PASSWORD_HASH = '$hash') AS PASSWORD_MATCHES
            FROM   GSC_USERS
            WHERE  USERID = '$userid'
        ";
        $result2 = query($query2);

        $password_matches = false;
        while ($row = $result2->fetch_assoc()) {
            $password_matches = $row['PASSWORD_MATCHES'];
        }

        //（機能変更）認証は静的SQLプレースホルダを利用すること
        if ($password_matches) {
            $query3 = "
                SELECT USERNAME, PERMISSION
                FROM   GSC_USERS
                WHERE  USERID = '$userid'
            ";
            $result3 = query($query3);
            
            while ($row = $result3->fetch_assoc()) {
                $username = $row['USERNAME'];
                $permission = $row['PERMISSION'];
            }

            /* セッション設定
             * 1. 待機セッション時間は 1,500秒（25分）とする
             * 2. セッション情報は count と呼ばれる変数に 0 を入れている
             * 3. このセッション情報が破棄されると、監視を行うことができなくなる
             * 4. セッションには、ユーザ名及び権限を格納する
             * （ユーザ名および権限がセッション情報にない場合、アクセス権を失効します）
             * 
             * [アクセス権付与ページ]
             * 
             */
            ini_set('session.gc_divisor', 1);
            ini_set('session.gc_maxlifetime', $session_time);
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['permission'] = $permission;
            http_response_code(301);
            header("Location: dash.php");
            exit;
        } else {
            $chk_failed = true;
        }
    }
}