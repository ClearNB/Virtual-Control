<!DOCTYPE html>

<!-- ログインページ（LOGIN）
概要: Virtual Control 上でユーザとしてのログインセッションを行うページ
遷移元: INDEX, HELP
遷移方法: INDEXからログインボタンを押下したとき
遷移先: DASHBOARD (SESSION-IN), HELP, INDEX
-->

<!--
<?php
//すでにログインが完了している場合は、セッション条件によりこのページから退避させる
session_start();
if (isset($_SESSION['count'])) {
    if ($_SESSION['count'] === 0) {
        http_response_code(301);
        header("Location: dash.php");
        exit();
    }
}

//変数の定義（データベースにより、ここは機能変更）
$userid = '';
$pass = '';
$c_userid = 'clearnb';
$c_pass = 'aa';
$session_time = 1500;
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
$chk_post = false;
$chk_failed = false;

//フォーム実行時に動的実行される
if ($method == 'POST') {
    //値の取得
    $userid = filter_input(INPUT_POST, 'userid', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING);

    //表示フラグの変更
    $chk_post = true;

    //（機能変更）認証は静的SQLプレースホルダを利用すること
    if ($userid == $c_userid && $pass == $c_pass) {
        if (!isset($_SESSION['count'])) {
            /* セッション設定
             * 1. 待機セッション時間は 1,500秒（25分）とする
             * 2. セッション情報は count と呼ばれる変数に 0 を入れている
             * 3. このセッション情報が破棄されると、監視を行うことができなくなる
             */
            ini_set('session.gc_divisor', 1);
            ini_set('session.gc_maxlifetime', $session_time);
            session_start();
            $_SESSION['count'] = 0;
        }
        http_response_code(301);
        header("Location: dash.php");
        exit;
    } else {
        $chk_failed = true;
    }
}
?>
-->

<html>

    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="Virtual Control">
        <link rel="icon" href="images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LOGIN - A Controlling Network Tool.</title>
        <meta name="description" content="Virtual Control - A Controlling Network Tool.">
        <link rel="stylesheet" href="style/awesome.min.css" type="text/css">
        <link rel="stylesheet" href="style/aquamarine.css" type="text/css">
        <link rel="stylesheet" href="style/dialog.css" type="text/css">

        <script src="js/navbar-ontop.js"></script>
        <script src="js/animate-in.js"></script>
        <script src="js/loader.js"></script>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <div id="nav"></div>
        <div class="bg-primary pt-5">
            <div class="container">
                <div id="logo"></div>
            </div>
        </div>
        <div class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12" style="">
                        <h2 class="text-left text-uppercase"><i class="fa fa-fw fa-server"></i>[Server_NAME]</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-monospace">[Description]</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="bg-primary shadow-lg pt-2">
            <div class="container mt-0 pt-0">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class=""><i class="fa fa-user fa-fw"></i>ログイン</h1>
                    </div>
                </div>
                <div class="row" style="">
                    <div class="col-md-12 text-lg-left text-center align-self-center my-2">
                        <form action="" method="POST">

                            <div class="form-group" style=""> <label class="importantLabel">【必須】</label><label class="formtext">ユーザID</label>
                                <input type="text" class="form-control bg-dark my-1 form-control-lg shadow-sm text-monospace" placeholder="Enter UserID" required="required" id="userid" name="userid" value="<?php echo htmlspecialchars($userid, ENT_QUOTES, 'UTF-8'); ?>">
                                <small class="form-text text-body" style="">ユーザIDはVCServerによって振り分けられています。</small>
                            </div>

                            <div class="form-group pt-2"> <label class="importantLabel">【必須】</label><label class="formtext">パスワード</label>
                                <input type="password" class="form-control bg-dark form-control-lg shadow-sm" placeholder="Password" required="required" id="pass" name="pass" value="<?php echo htmlspecialchars($pass, ENT_QUOTES, 'UTF-8'); ?>">
                                <small class="form-text text-body">ユーザIDに割り当てられたパスワードを入力します。</small>
                            </div>

                            <?php
                            if ($chk_post && $chk_failed) {
                                echo '<div class="failedMessage">ログインに失敗しました。<br>ユーザIDもしくはパスワードが違います。<hr class="orange"></div>';
                            }
                            ?>
                            <button type="submit" class="btn btn-dark btn-block btn-lg shadow-lg">
                                <i class="fa fa-fw fa-sign-in"></i>ログイン</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Non-Available Logins -->
        <div class="py-3" style="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="" style="">ログインできない場合</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-monospace" style="">本サイトのネットワーク管理者にご相談ください。本サーバの管理者はVCServerと呼称されます。VCServerにお問い合わせする際は、以下のボタンリンクのメールアドレスでお願いいたします。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-1">
            <div class="container">
                <div class="row">
                    <div class="col-md-12"><a class="btn btn-primary btn-block mb-2 shadow-lg" href="#"><i class="fa fa-fw fa-envelope-o"></i>&nbsp;VCServerにお問い合わせ<br></a></div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div id="foot"></div>

        <!-- JavaScript dependencies -->
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">load(2); load(0);</script>
    </body>

</html>