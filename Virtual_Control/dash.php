<!DOCTYPE html>

<?php
include_once ('./scripts/general/sqldata.php');
include_once ('./scripts/session/session_chk.php');
include_once ('./scripts/general/loader.php');
include_once ('./scripts/general/former.php');

session_start();
if(!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}

$loader = new loader();

$id = $_SESSION['gsc_userid'];
$getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERID = '$id'");

$fm_pg = new form_generator('fm_pg');
$fm_pg->SubTitle($getdata['USERNAME'] . 'さん', 'アクセス監視をしましょう。<br>行動を選択してください。', 'user');
$fm_pg->openListGroup();
$fm_pg->ListGroupData('check', 'SNMPチェック', 'vials', 'SNMPの情報を試しに取得することができます', '詳しくはクリック！');
$fm_pg->ListGroupData('analy', 'アナリティクス', 'chart-pie', 'アクセス状況をリアルタイムで監視できます', '詳しくはクリック！');
$fm_pg->ListGroupData('warn', '警告情報', 'file-excel', 'アクセス状況の警告情報をご覧になれます', '詳しくはクリック！');
$fm_pg->ListGroupData('option', 'オプション', 'wrench', 'アカウントまたはサーバの設定を行います', '詳しくはクリック！');
$fm_pg->closeDiv();
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'DASHBOARD') ?>
	<?php echo form_generator::ExportClass([$fm_pg]) ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar & Logo -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?>
	
        <?php echo $loader->load_Logo() ?>

        <!-- Server Status -->
        <?php echo $loader->Title('DASH', 'align-justify') ?>
	
	<div id="data_output"></div>
	
        <!-- Footer -->
        <?php echo $loader->footer(); ?>

        <?php echo $loader->footerS(); ?>
	
	<script type="text/javascript">
	    $(document).ready(function() {
		animation('data_output', 0, fm_pg);
	    });
	    
	    $(document).on('click', '#check, #analy, #warn, #option', function() {
		switch($(this).attr('id')) {
		    case "check":
			animation_to_sites("data_output", 400, "./test.php");
			break;
		    case "analy":
			animation_to_sites("data_output", 400, "./analy.php");
			break;
		    case "warn":
			animation_to_sites("data_output", 400, "./warn.php");
			break;
		    <?php if ($getdata['PERMISSION'] == 1) { echo 'case "option": animation_to_sites("data_output", 400, "./option"); break;'; } ?>
		}
	    });
	</script>
    </body>

</html>