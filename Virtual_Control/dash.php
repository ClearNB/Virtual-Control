<!DOCTYPE html>

<!--
<?php
session_start();
if (!isset($_SESSION['count'])) {
    http_response_code(301);
    header("Location: noadmin.php");
    exit();
}
?>
-->

<html>

    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="Virtual Control">
        <link rel="icon" href="images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DASHBOARD - Virtual Control</title>
        <meta name="description" content="Dashboard - Virtual Control">
        <!-- CSS -->
        <link rel="stylesheet" href="awesome.min.css" type="text/css">
        <link rel="stylesheet" href="aquamarine.css" type="text/css">
        <link rel="stylesheet" href="style/dialog.css" type="text/css">
        <!-- Javascript -->
        <script src="js/navbar-ontop.js"></script>
        <script src="js/animate-in.js"></script>
        <script src="js/loader.js"></script>
    </head>

    <body class="text-monospace">

        <!-- Navbar & Logo -->
        <div id="nav"></div>
        <div class="bg-primary pt-5">
            <div class="container">
                <div id="logo"></div>
            </div>
        </div>

        <!-- Server Status -->
        <div class="py-3">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <h2 style="" class="text-monospace text-left text-uppercase"><i class="fa fa-fw fa-server"></i>[Server_NAME]</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p class="text-monospace">[Description]</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Action Buttons -->
        <div class="py-2 bg-primary">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center">アクセス監視をしましょう</h3>
                        <p class="text-monospace text-center">行動を選択してください:</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mx-auto">
                        <div class="list-group">

                            <!-- Analysis -->
                            <a href="analy.php" class="list-group-item list-group-item-action flex-column align-items-start active list-group-item-dark mb-2">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><i class="fa fa-fw fa-bar-chart fa-lg"></i>アナリティクス</h5>
                                </div>
                                <p class="mb-1" style="">アクセス状況をリアルタイムで監視できます</p> <small>詳しくはクリック！</small>
                            </a>
                            <!-- Warnings -->
                            <a href="warn.php" class="list-group-item flex-column align-items-start list-group-item-dark list-group-item-action active mb-2">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><i class="fa fa-fw fa-exclamation-triangle"></i>警告情報</h5>
                                </div>
                                <p class="mb-1">アクセス状況の警告情報をご覧になれます</p> <small>詳しくはクリック！</small>
                            </a>

                            <!-- Options -->
                            <a href="option.php" class="list-group-item list-group-item-action flex-column align-items-start active list-group-item-dark">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1"><i class="fa fa-fw fa-wrench"></i>オプション</h5>
                                </div>
                                <p class="mb-1">アカウントまたはサーバの設定を行います</p> <small>詳しくはクリック！</small>
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center text-white py-2">
            <div class="container">
                <div class="row">
                    <div class="mx-auto col-md-6">
                        <h2 class="">現在の警告レベル</h2>
                        <h3 class="display-3 text-monospace" id="level_warn">1</h3>
                        <h6 class="m-0 mb-2">詳しくは警告情報をご覧ください。</h6> <a class="btn btn-primary btn-block btn-lg" href="warn.php" style=""><i class="fa fa-fw fa-exclamation-triangle"></i>警告情報</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center py-2 bg-primary" style="">
            <div class="container">
                <div class="row">
                    <div class="mx-auto col-md-8">
                        <h2 class="">管理者へ連絡</h2>
                        <p class="text-left">アクセス状況で異常を発見したら、本サーバの管理者へメールで連絡することができます。異常を見つけたら管理者へ連絡してください。<br></p> <a class="btn btn-dark btn-lg btn-block" href="mail:" style=""><i class="fa fa-fw fa-envelope"></i>管理者へメールで連絡</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div id="foot"></div>

        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            load(1);
        </script>
    </body>

</html>