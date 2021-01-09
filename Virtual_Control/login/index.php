<?php
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/session/session_chk.php';
include_once __DIR__ . '/../scripts/general/sqldata.php';
include_once __DIR__ . '/../scripts/general/former.php';

session_action_guest();

$loader = new loader();

$fm_lg = new form_generator('fm_lg');
$fm_lg->Input('userid', 'ユーザID', 'ユーザIDは、VCServerによって指定されています。', 'id-card-alt', true);
$fm_lg->Password('password', 'パスワード', '指定のパスワードを入力します。', 'key', true);
$fm_lg->Button('fm_sb', 'ログイン', 'submit', 'sign-in-alt');

$fm_lg_ld = new form_generator('fm_lg_ld');
$fm_lg_ld->SubTitle("セッション中です。", "そのままお待ちください...", "spinner fa-spin");

$fm_up_fl = new form_generator('fm_up_fl');
$fm_up_fl->SubTitle("ログインに失敗しました。", "ユーザIDまたはパスワードが違います", "exclamation-triangle");
$fm_up_fl->openList();
$fm_up_fl->addList('各項目の入力事項をご確認ください');
$fm_up_fl->addList('ユーザID・パスワードを忘れたら、管理者に相談してください');
$fm_up_fl->closeList();
$fm_up_fl->Button('bt_lg_bk', '入力に戻る', 'button', 'chevron-circle-left');

$fm_dt_fl = new form_generator('fm_dt_fl');
$fm_dt_fl->SubTitle("ログインに失敗しました。", "データベースの状態を確認してください。", "exclamation-triangle");
$fm_dt_fl->openList();
$fm_dt_fl->addList('データベースの設定を見直してください。');
$fm_dt_fl->addList('この件は管理者に必ず相談してください。');
$fm_dt_fl->closeList();
$fm_dt_fl->Button('bt_lg_bk', '入力に戻る', 'button', 'chevron-circle-left');
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'LOGIN', true) ?>
        <?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <?php echo $loader->navigation(2) ?>
        
        <?php echo $loader->load_Logo() ?>

        <?php echo $loader->Title('LOGIN', 'sign-in-alt') ?>
        
        <div id="data_output"></div>

        <?php echo $loader->footer() ?>

        <?php echo $loader->footerS(true) ?>
        <script type="text/javascript">
            $(document).ready(function () {
                animation('data_output', 0, fm_lg);
            });

            $(document).on('submit', '#fm_lg', function (event) {
                event.preventDefault();
                var d = $(this).serializeArray();
                fm_lg = document.getElementById('data_output').innerHTML;
                animation('data_output', 400, fm_lg_ld);
                ajax_dynamic_post('../scripts/session/session.php', d).then(function (data) {
                    switch (data['res']) {
                        case 0: animation_to_sites('data_output', 400, '../dash'); break;
                        case 1: animation('data_output', 400, fm_dt_fl); break;
                        case 2: animation('data_output', 400, fm_up_fl); break;
                    }
                });
            });

            $(document).on('click', '#bt_lg_bk', function () {
                animation('data_output', 400, fm_lg);
            });
        </script>
    </body>
</html>