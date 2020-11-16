<!DOCTYPE html>

<!--
<?php
/*
 * ログインページ（LOGIN）
 * 概要: Virtual Control 上でユーザとしてのログインセッションを行うページ
 * 遷移元: INDEX, HELP
 * 遷移方法: INDEXからログインボタンを押下したとき
 * 遷移先: DASHBOARD (SESSION-IN), HELP, INDEX
 */

//include
include_once ('./scripts/sqldata.php');
include_once ('./scripts/common.php');
include_once ('./scripts/dbconfig.php');
include_once ('./scripts/former.php');
include_once ('./scripts/loader.php');
include_once ('./scripts/session_chk.php');

if(session_chk()) {
    http_response_code(301);
    header('location: dash.php');
    exit();
}

$loader = new loader();

$form = new form_generator('login_form', '', 2);
$form->Title('ログイン', 'sign-in');
$form->Input('userid', 'ユーザID', 'ユーザIDは、VCServerによって指定されています。', 'user-circle-o', true);
$form->Password('password', 'パスワード', '指定のパスワードを入力します。', 'key', true);
$form->Button('form_submit', 'ログイン', 'submit', 'sign-in', 'primary');

$form_wait = new form_generator('failed_form_01', '', 2);
$form_wait->SubTitle("セッション中です。", "そのままお待ちください...", "spinner");

$form_failed_01 = new form_generator('failed_form_01', '', 2);
$form_failed_01->SubTitle("ログインに失敗しました。", "ユーザIDまたはパスワードが違います", "exclamation-triangle");
$form_failed_01->Caption("<h3 class=\"py-2\">【警告】</h3><hr class=\"orange\"><ul class=\"orange-view\"><li>各項目の入力事項をご確認ください。</li><li>ユーザID・パスワードを忘れたら、管理者に相談してください。</li></ul>");
$form_failed_01->Button('form_back_form_01', '入力に戻る', 'button', 'caret-square-o-left', 'primary');

$form_failed_02 = new form_generator('failed_form_02', '', 2);
$form_failed_02->SubTitle("ログインに失敗しました。", "データベースの状態を確認してください。", "exclamation-triangle");
$form_failed_02->Caption("<h3 class=\"py-1 md-0\">【警告】</h3><ul class=\"orange-view\"><li>データベースの設定を見直してください。</li><li>この件は管理者に必ず相談してください。</li></ul>");
$form_failed_02->Button('form_back_form_01', '入力に戻る', 'button', 'caret-square-o-left', 'primary');
?>
-->

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'LOGIN') ?>
        <script type="text/javascript">
            var fdata1 = '<?php echo $form->Export() ?>';
            var fdata2 = '<?php echo $form_failed_01->Export() ?>';
            var fdata3 = '<?php echo $form_failed_02->Export() ?>';
            var fdataw = '<?php echo $form_wait->Export() ?>';
        </script>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <div id="nav"></div>
        
        <?php ?>
        
        <!-- Login Form -->
        <div >

        <!-- Non-Available Logins -->
        <div class="py-3 bg-primary">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-monospace text-left text-uppercase">ログインできない場合</h3>
                        <p class="text-monospace">
                            本サイトのネットワーク管理者にご相談ください。
                            本サーバの管理者はVCServerと呼称されます。
                            VCServerにお問い合わせする際は、
                            以下のボタンリンクのメールアドレスでお願いいたします。
                    <div class="col-md-12">
                        <a class="btn btn-dark btn-block mb-2 shadow-lg" href="#">
                            <i class="fa fa-fw fa-envelope-o"></i>VCServerにお問い合わせ
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div id="foot"></div>

        <!-- JavaScript dependencies -->
        <script src="js/now_loading.js"></script>
        <script src="js/jquery.js"></script>
        <script src="jquery/jquery-ui.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            load(2);

            $(document).ready(function () {
                animation('data_output', 0, fdata1);
            });

            //Page1: Login Form
            $(document).on('submit', '#login_form', function (event) {
                event.preventDefault();
                var form = $('#login_form');
                var d = form.serializeArray();
                fdata1 = document.getElementById('data_output').innerHTML;
                animation('data_output', 400, fdataw);
                $.ajax({
                    type: 'POST',
                    url: './scripts/session.php',
                    data: d,
                    crossDomain: false,
                    dataType: 'json'
                }).done(function (res) {
                    removeLoading();
                    if (res["res"] === 0) {
                        window.location.href = 'dash.php';
                    } else {
                        animation('data_output', 400, fdata2);
                    }
                }).fail(function () {
                    animation('data_output', 400, fdata3);
                });
            });
            
            //Page2: Failed Form 01
            $(document).on('click', '#form_back_form_01', function () {
                animation('data_output', 400, fdata1);
            });
            
            //Page3: Failed Form 02
            $(document).on('click', '#form_back_form_02', function () {
                animation('data_output', 400, fdata1);
            });
        </script>
    </body>
</html>