<!DOCTYPE html>

<!--
<?php
include ('./scripts/session_chk.php');
include ('./scripts/loader.php');
if(session_chk()) {
    http_response_code(301);
    header('location: dash.php');
    exit();
}
$loader = new loader();
?>
-->

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'INDEX') ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <div id="nav"></div>

        <div class="pt-5 bg-primary shadow-lg">
            <div class="container mt-0 pt-0">
                <div class="row">
                    <div class="col-md-12 text-lg-left text-center align-self-center my-2">
                        <div class="card-body my-1 bg-dark" style="border-top-left-radius: 20px; border-bottom-right-radius: 20px;">
                            <h5 class="card-title bg-primary shadow-none p-2" style="border-bottom-right-radius: 10px; border-top-left-radius: 10px;">
                                <i class="fas fa-users"></i>ログインが必要です</h5>
                            <p class="card-text">本サーバはユーザ登録制です。<br>管理者権限により作成されたアカウントでログインしてください。</p>
                        </div>
                    </div>
                </div>
                <div id="logo"></div>
            </div>
        </div>
        <div class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3>アクセス監視は新たな挑戦へ</h3>
                        <p class="text-monospace break">Virtual Control は、SNMPを利用したネットワークアクセス監視を実現できる監視ツールです。<br><br>アクセス監視は運用・保守の専門職を問わず、誰でも監視できる環境を整えなければならない時代に差し掛かっています。その状況の中で、私たちは「標準化」を目的に、Webアプリケーションで監視が可能なアプリケーションを開発しました。</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-2 bg-primary">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 align-self-center order-1 order-md-2 text-md-left text-center">
                        <h3 class="text-left text-body">使いやすく<br>そしてわかりやすく</h3>
                        <p class="text-left text-monospace">Virtual Control は、できるだけ利用しやすい環境として HTML5 (+ CSS, JavaScript), PHP の2言語を使用しております。</p>
                        <a class="btn btn-dark btn-lg btn-block active" href="https://github.com/ClearNB/Virtual-Control" target="_blank">
                            <i class="fab fa-fw fa-github-square fa-lg"></i>GitHubを開く
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Features -->
        <div class="container py-3">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-left text-monospace">SNMPを使った監視をもっと気軽に</h3>
                    <div class="col-md-12">
                        <div class="card mt-1 rounded">
                            <div class="card-header bg-secondary border-bottom border-dark">標準MIBに準拠した監視を</div>
                            <div class="card-body bg-primary">
                                <h5 class="text-left text-monospace"><i class="fas fa-server"></i>OID識別を日本語メッセージに変換！</h5>
                                <p class="text-left">参照しなければ何の項目かわからないOIDを、標準MIBに準拠した日本語メッセージを搭載！安心して気になった項目を監視できます。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Call to action -->
        <div class="bg-primary">
            <div class="container">
                <div class="row py-3">
                    <div class="col-md-12 align-self-center text-center text-md-left">
                        <h4 class="text-center">ログインが必要です</h4>
                        <p class="text-center">ボタンをクリックして、ログインを行ってください</p>
                        <div class="row mt-4">
                            <div class="col-md-12 col-12">
                                <a class="btn btn-block btn-lg btn-dark active" href="login.php">
                                    <i class="fas fa-sign-in-alt"></i>ログイン
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div id="foot"></div>

        <!-- JavaScript dependencies -->
        <script src="js/jquery.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">load(2);</script>
    </body>

</html>