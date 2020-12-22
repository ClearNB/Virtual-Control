<?php
include_once './scripts/general/loader.php';
include_once './scripts/session/session_chk.php';
include_once './scripts/general/sqldata.php';
include_once './scripts/general/former.php';

$loader = new loader();

$fm_in_ld = fm_ld('fm_in_ld', 'データベースの初期化中', '全てが完了するまで他の場所に移動しないでください。');

$fm_in_sc = new form_generator('fm_in_sc');
$fm_in_sc->SubTitle('初期化が完了しました！', '早速、新しくなったデータで試しましょう！', 'thumbs-up');
$fm_in_sc->Button('bt_sc_ln', 'ホームに向かう', 'button', 'home');

$fm_in_fl = new form_generator('fm_in_fl');
$fm_in_fl->SubTitle('初期化に失敗しました', '以下のログをご確認ください。', 'exclamation-triangle');
$fm_in_fl->Caption('cap_log');
$fm_in_fl->Button('bt_fl_rs', 'やり直す', 'button', 'sync-alt');
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'INIT') ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
	<?php echo $loader->navigation(0) ?>
	<?php echo $loader->load_Logo() ?>
	<?php echo $loader->Title('データベースの初期化', 'sync-alt') ?>

        <div id="data_output"></div>

        <!-- Footer -->
	<?php echo $loader->footer() ?>

        <!-- JavaScript dependencies -->
	<?php echo $loader->footerS() ?>

	<script type="text/javascript">
	    $(document).ready(function () {
		animation('data_output', 0, fm_in_ld);
		data_post();
	    });

	    $(document).on('click', '#bt_sc_ln, #bt_fl_rs', function () {
		switch ($(this).attr('id')) {
		    case "bt_sc_ln":
			animation_to_sites('data_output', 400, './');
			break;
		    case "bt_fl_rs":
			data_post();
			break;
		}
	    });

	    function data_post() {
		ajax_dynamic_post_toget('./scripts/init/init.php').then(function (data) {
		    switch (data['code']) {
			case 0:
			    animation('data_output', 400, fm_in_sc);
			    break;
			case 1:
			    var fm_w = fm_in_fl.replace('cap_log', data['s_text']);
			    animation('data_output', 400, fm_w);
			    break;
		    }
		});
	    }
	</script>
    </body>

</html>