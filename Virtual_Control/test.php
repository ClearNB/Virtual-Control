<!DOCTYPE html>

<!-- PHP HEADER MODULE -->
<?php
include ('./scripts/session_chk.php');
session_start();
if (!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}

include_once ('./scripts/former.php');
include_once ('./scripts/loader.php');

$loader = new loader();

$fm = new form_generator('fm');
$fm->Button('fm_bt_bk', 'ホームに戻る', 'submit', 'vials');
$fm->SubTitle('SNMPWALKテスト', '情報の取得をテストします', 'fas fa-server');
$fm->Input('host', 'ホストアドレス', '接続先を指定します。', 'id-card-alt', true);
$fm->Input('community', 'コミュニティ', 'エージェントが属するコミュニティを設定します。', 'users', true);
$fm->Input('oid', 'OID', 'フィルタリングするOIDを設定します。', 'object-ungroup', false);
$fm->Button('fm_bt_sb', 'SNMPWALKを送信', 'submit', 'vials');

$fm_rt = new form_generator('fm_rt');
$fm_rt->Title('SNMPWALK結果', 'male');
$fm_rt->CaptionLg('snmpdata');
$fm_rt->Button('bt_rt_bk', '戻る', 'button', 'chevron-circle-left');

$fm_ld = new form_generator('fm_ld');
$fm_ld->SubTitle('接続中です…', 'しばらくお待ちください', 'fas fa-spinner fa-spin');

$fm_fl = new form_generator('fm_fl');
$fm_fl->SubTitle('接続に失敗しました。', '以下をご確認ください。', 'fas fa-exclamination');
$fm_fl->openList();
$fm_fl->addList('エージェントと接続できる環境であるか確認してください。');
$fm_fl->addList('正しい値が入力されているか確認してください。');
$fm_fl->addList('エージェントのファイアウォール設定をご確認ください。');
$fm_fl->closeList();
$fm_fl->Button('bt_fl_bk', '戻る', 'button', 'chevron-circle-left');

$index = $_SESSION['gsc_userindex'];
$getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERINDEX = $index");
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'TEST') ?>
            <script type="text/javascript">
                var fm = '<?php echo $fm->Export() ?>';
                var fm_rt = '<?php echo $fm_rt->Export() ?>';
                var fm_ld = '<?php echo $fm_ld->Export() ?>';
                var fm_fl = '<?php echo $fm_fl->Export() ?>';
                var fm_w;
            </script>
    </head>

    <body class="text-monospace">
        <!-- HEADER NAVIGATION -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?>

        <!-- HEADER SECTION -->
        <?php echo $loader->load_logo() ?>

        <!-- TITLE SECTION -->
        <div class="bg-dark">
            <div class="container">
                <?php echo $loader->Title('SNMP TEST', 'fas fa-server') ?>
            </div>
        </div>

        <!-- CONTENT SECTION -->
        <div id="data_output"></div>

        <!-- FOOTER -->
        <?php echo $loader->footer() ?>

        <!-- FOOTER SCRIPTS -->
        <?php echo $loader->footerS(); ?>
        <script type="text/javascript">
            $(document).ready($function() {
                animation('data_output', 0, fm);
            });

            /* Ajax - SNMPWALKER */
            $(document).on('submit', '#fm', function (event) {
                event.preventDefault();
                //ボタンによる実行を阻止
                var d = $(this);
                animation('data_output', 400, fm_ld);
                ajax_dynamic_post('./scripts/snmpwalk.php', d).then(function (data) {
                    switch(data['code']) {
                        case 0:
                            fm_w = fm_rt.replace('snmpdata', data['res']);
                            animation('data_output', 400, fm_w);
                            break;
                        case 1:
                            animation('data_output', 400, fm_fl);
                            break;
                    }
                });
            });
            
            $(document).on('click', '#bt_rt_bk, #bt_fl_bk', function() {
                animation('data_output', 400, fm);
            });
            
            $(document).on('click', '#bt_fm_bk', function() {
                animation_to_sites('data_output', 400, './');
            });
        </script>
    </body>

</html>
