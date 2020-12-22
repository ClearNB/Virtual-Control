<?php
include_once './scripts/general/loader.php';
include_once './scripts/session/session_chk.php';
include_once './scripts/general/sqldata.php';
include_once './scripts/general/former.php';

session_action_user();

$loader = new loader();

$fm_an_sl = new form_generator('fm_an_sl', '');

$fm_an_sl->Button('bt_an_sl_bk', 'ホームに戻る', 'button', 'home');

$fm_an_sl->CardDark(
	'エージェント選択'
	, ''
	, ''
	, ''
	, ''
	, ''
);


$fm_an_sl->openSelect('fm_sl_ag');
$fm_an_sl->addOption(1, 'エージェント1');
$fm_an_sl->addOption(2, 'エージェント2');
$fm_an_sl->addOption(3, 'エージェント3');
$fm_an_sl->closeSelect();

$fm_an_sl->Button('bt_an_sl_sb', '選択して情報を取得する', 'button', 'home');



$agents = ['agt01', 'agt02', 'agt03', 'agt04', 'agt05']; //エージェントデータはデータベースから取得する

$userid = $_SESSION['gsc_userid'];
$getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERID = '$userid'");
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'ANALY') ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>

	<?php echo $loader->load_Logo() ?>

	<?PHP echo $loader->Title('ANALY', 'chart-bar') ?>

        <div id="data_output"></div>

	<?php echo $loader->footer() ?>

        <!-- JavaScript dependencies -->
	<?php echo $loader->footerS() ?>

	<script type="text/javascript">
	    $(document).ready(function () {
		animation('data_output', 0, fm_an_sl);
	    });

	    $(document).on('click', '#bt_st_lk', function () {
			animation_to_sites('data_output', 400, './index.php');
	    });
	</script>
    </body>
</html>