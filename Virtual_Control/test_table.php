<?php
include_once './scripts/general/loader.php';
include_once './scripts/session/session_chk.php';
include_once './scripts/general/sqldata.php';
include_once './scripts/general/former.php';

session_action_user();
$getdata = session_get_userdata();

$loader = new loader();

$fm_ld = fm_ld('fm_ld', 'テーブルデータの取得中', 'しばらくお待ちください...');

$fm_rs = new form_generator('fm_rs');
$fm_rs->Button('bt_ac_bk', 'ホームに戻る', 'button', 'home');
$fm_rs->Caption('[data]');

$fm_fl = new form_generator('fm_fl');
$fm_fl->SubTitle('データの取得に失敗しました', 'データベースに接続していない可能性があります。', 'exclamation-triangle');
$fm_fl->Button('bt_ac_rs', '再試行', 'button', 'undo-alt');
$fm_fl->Button('bt_ac_bk', 'ホームに戻る', 'button', 'home');
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'TEST') ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <!-- HEADER NAVIGATION -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?>

        <!-- HEADER SECTION -->
        <?php echo $loader->load_logo() ?>

        <!-- TITLE SECTION -->
        <?php echo $loader->Title('SNMP TEST', 'fas fa-server') ?>

        <!-- CONTENT SECTION -->
        <div id="data_output"></div>

        <!-- FOOTER -->
        <?php echo $loader->footer() ?>

        <!-- FOOTER SCRIPTS -->
        <?php echo $loader->footerS(); ?>
        <script type="text/javascript">
            $(document).ready(function() {
                animation('data_output', 0, fm_ld);
		get_account();
            });
	    
	    $(document).on('click', '#bt_ac_bk, #bt_ac_rs', function() {
		switch($(this).attr('id')) {
		    case "bt_ac_bk":
			animation_to_sites('data_output', 400, './');
			break;
		    case "bt_ac_rs":
			get_account();
			break;
		}
	    });
	    
	    function get_account() {
		ajax_dynamic_post_toget('./scripts/account/account_get.php').then(function(data) {
		    switch(data['code']) {
			case 0:
			    var fm_w = fm_rs.replace('[data]', data['data']);
			    animation('data_output', 400, fm_w);
			    break;
			case 1:
			    animation('data_output', 400, fm_fl);
			    break;
		    }
		});
	    }
        </script>
    </body>
</html>
