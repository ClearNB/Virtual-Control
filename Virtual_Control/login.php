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
include_once ('./scripts/general/sqldata.php');
include_once ('./scripts/former.php');
include_once ('./scripts/loader.php');
include_once ('./scripts/session_chk.php');

if (session_chk()) {
    http_response_code(301);
    header('location: dash.php');
    exit();
}

$loader = new loader();

$fm_lg = new form_generator('fm_lg');
$fm_lg->Input('userid', 'ユーザID', 'ユーザIDは、VCServerによって指定されています。', 'id-card-alt', true);
$fm_lg->Password('password', 'パスワード', '指定のパスワードを入力します。', 'key', true);
$fm_lg->Button('fm_sb', 'ログイン', 'submit', 'sign-in-alt');

$form_wait = new form_generator('fm_wt');
$form_wait->SubTitle("セッション中です。", "そのままお待ちください...", "spinner fa-spin");

$form_failed_01 = new form_generator('failed_form_01');
$form_failed_01->SubTitle("ログインに失敗しました。", "ユーザIDまたはパスワードが違います", "exclamation-triangle");
$form_failed_01->Caption("<h3 class=\"py-2\">【警告】</h3><hr class=\"orange\"><ul class=\"orange-view\"><li>各項目の入力事項をご確認ください。</li><li>ユーザID・パスワードを忘れたら、管理者に相談してください。</li></ul>");
$form_failed_01->Button('form_back_form_01', '入力に戻る', 'button', 'caret-square-o-left');

$form_failed_02 = new form_generator('failed_form_02');
$form_failed_02->SubTitle("ログインに失敗しました。", "データベースの状態を確認してください。", "exclamation-triangle");
$form_failed_02->Caption("<h3 class=\"py-1 md-0\">【警告】</h3><ul class=\"orange-view\"><li>データベースの設定を見直してください。</li><li>この件は管理者に必ず相談してください。</li></ul>");
$form_failed_02->Button('form_back_form_01', '入力に戻る', 'button', 'caret-square-o-left');
?>
-->

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'LOGIN') ?>
        <script type="text/javascript">
            var fdata1 = '<?php echo $fm_lg->Export() ?>';
            var fdata2 = '<?php echo $form_failed_01->Export() ?>';
            var fdata3 = '<?php echo $form_failed_02->Export() ?>';
            var fdataw = '<?php echo $form_wait->Export() ?>';
        </script>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <?php echo $loader->navigation(0) ?>
        
        <?php echo $loader->load_Logo() ?>

        <div class="bg-dark">
            <div class="container">
                <?php echo $loader->Title('LOGIN', 'fas fa-sign-in-alt') ?>
            </div>
        </div>
        
        <!-- Login Form -->
        <div id="data_output"></div>

        <!-- Footer -->
        <?php echo $loader->footer() ?>

        <!-- JavaScript dependencies -->
        <?php echo $loader->footerS() ?>
        <script type="text/javascript">

            $(document).ready(function () {
                animation('data_output', 0, fdata1);
            });

            //Page1: Login Form
            $(document).on('submit', '#fm_lg', function (event) {
                event.preventDefault();
                var d = $(this).serializeArray();
                fdata1 = document.getElementById('data_output').innerHTML;
                animation('data_output', 400, fdataw);
                ajax_dynamic_post('./scripts/session.php', d).then(function (data) {
                    switch (data['res']) {
                        case 0:
                            animation_to_sites('data_output', 400, './dash.php');
                            break;
                        case 1:
                            animation('data_output', 400, fdata3);
                            break;
                        case 2:
                            animation('data_output', 400, fdata2);
                            break;
                    }
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