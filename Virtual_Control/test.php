<?php
include_once './scripts/general/loader.php';
include_once './scripts/session/session_chk.php';
include_once './scripts/general/sqldata.php';
include_once './scripts/general/former.php';

session_action_user();
$getdata = session_get_userdata();

$loader = new loader();

$fm = new form_generator('fm');
$fm->Button('bt_fm_bk', 'ホームに戻る', 'button', 'chevron-circle-left');
$fm->SubTitle('SNMPWALKテスト', '情報の取得をテストします', 'fas fa-server');
$fm->Input('host', 'ホストアドレス', '接続先を指定します。', 'id-card-alt', true);
$fm->Input('community', 'コミュニティ', 'エージェントが属するコミュニティを設定します。', 'users', true);
$fm->Input('oid', 'OID', 'フィルタリングするOIDを設定します。', 'object-ungroup', false);
$fm->Button('fm_bt_sb', 'SNMPWALKを送信', 'submit', 'vials');

$fm_rt = new form_generator('fm_rt');
$fm_rt->Title('SNMPWALK結果', 'male');
$fm_rt->Caption('snmpdata', true, 3);
$fm_rt->Button('bt_rt_bk', '戻る', 'button', 'chevron-circle-left');

$fm_ld = new form_generator('fm_ld');
$fm_ld->SubTitle('接続中です…', 'しばらくお待ちください', 'fas fa-spinner fa-spin');

$fm_fl = new form_generator('fm_fl');
$fm_fl->SubTitle('接続に失敗しました。', '以下をご確認ください。', 'exclamation-triangle');
$fm_fl->openList();
$fm_fl->addList('エージェントと接続できる環境であるか確認してください。');
$fm_fl->addList('正しい値が入力されているか確認してください。');
$fm_fl->addList('エージェントのファイアウォール設定をご確認ください。');
$fm_fl->closeList();
$fm_fl->Button('bt_fl_bk', '戻る', 'button', 'chevron-circle-left');
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'TEST') ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <?php echo $loader->navigation($getdata['PERMISSION']) ?>

        <?php echo $loader->load_logo() ?>
	
        <?php echo $loader->Title('SNMP TEST', 'fas fa-server') ?>
	
        <div id="data_output"></div>
	
        <?php echo $loader->footer() ?>
	
        <?php echo $loader->footerS(); ?>
        <script type="text/javascript">
            $(document).ready(function() {
                animation('data_output', 0, fm);
            });

            $(document).on('submit', '#fm', function (event) {
                event.preventDefault();
                //ボタンによる実行を阻止
                var d = $(this).serialize();
                animation('data_output', 400, fm_ld);
                ajax_dynamic_post('./scripts/snmp/snmpwalk.php', d).then(function (data) {
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
