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
$fm->SubTitle('SNMPTRAPテスト', 'トラップ情報の取得をテストします', 'fas fa-server');
$fm->Button('fm_bt_sb', 'SNMPTRAP結果を表示', 'submit', 'vials');

$fm_rt = new form_generator('fm_rt');
$fm_rt->Title('SNMPTRAP結果', 'male');
$fm_rt->Caption('snmpdata', true, 3);
$fm_rt->Button('bt_rt_bk', '戻る', 'button', 'chevron-circle-left');

$fm_ld = new form_generator('fm_ld');
$fm_ld->SubTitle('ログファイルの解析中です…', 'しばらくお待ちください', 'fas fa-spinner fa-spin');

$fm_fl = new form_generator('fm_fl');
$fm_fl->SubTitle('ログファイルの取得に失敗しました。', '以下をご確認ください。', 'exclamation-triangle');
$fm_fl->openList();
$fm_fl->addList('アプリケーション単体に、何らかの例外が発生していると思われます。');
$fm_fl->addList('ログファイル（/var/www/html/data/trap/*）において、全てのファイルが書き込み・読み込みが可能であるか確認してください。');
$fm_fl->addList('ログファイル内が正しい値を格納していない恐れも考えられます。ファイル内をご確認ください。');
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
                ajax_dynamic_post('./scripts/warn/snmpwarn.php', d).then(function (data) {
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
