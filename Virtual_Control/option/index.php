<?php
include_once ('../scripts/general/sqldata.php');
include_once ('../scripts/session/session_chk.php');
include_once ('../scripts/general/loader.php');
include_once ('../scripts/general/former.php');
if (!session_chk()) {
    http_response_code(301);
    header('Location: ../403.php');
    exit();
}
$id = $_SESSION['gsc_userid'];
$getdata = select(true, "GSC_USERS", "USERNAME, PERMISSION", "WHERE USERID = '$id'");
if (!$getdata && $getdata['PERMISSION'] != 0) {
    http_response_code(301);
    header('Location: ../403.php');
    exit();
}

$loader = new loader();

$fm_pg = new form_generator('fm_pg', '');
$fm_pg->Button('bt_pg_bk', 'ホームに戻る', 'button', 'home');
$fm_pg->SubTitle('設定一覧', '設定したい項目を選んでください。', 'wrench');
$fm_pg->openListGroup();
$fm_pg->ListGroupData('account', 'ACCOUNT', 'user', 'アカウント管理ページ', 'アカウント一覧／作成／編集／削除');
$fm_pg->ListGroupData('mib', 'MIB', 'database', 'MIB管理ページ', 'MIB一覧／作成／編集／削除');
$fm_pg->ListGroupData('agent', 'AGENT', 'server', 'エージェント管理ページ', 'エージェント一覧／作成／編集／削除');
$fm_pg->closeDiv();

//Check(type[1..Radio, Other..Check], $id)
$fm_pg->Check(1, 'rd_01', 'agt', 1, 'AGENT1', true);
$fm_pg->Check(1, 'rd_02', 'agt', 2, 'AGENT2', false);
$fm_pg->Check(1, 'rd_03', 'agt', 3, 'AGENT3', false);

?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION', true); ?>
	<?php echo form_generator::ExportClass([$fm_pg]) ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar & Logo -->
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>

	<?php echo $loader->load_Logo(); ?>

	<?php echo $loader->Title('OPTION', 'wrench') ?>
	
	<div id="data_output"></div>

        <!-- Footer -->
	<?php echo $loader->footer() ?>

	<?php echo $loader->footerS(true) ?>
	
	<script type="text/javascript">
	    $(document).ready(function() {
		animation('data_output', 0, fm_pg);
	    });
	    
	    $(document).on('click', '#bt_pg_bk, #account, #mib, #agent', function() {
		switch($(this).attr('id')) {
		    case 'bt_pg_bk':
			animation_to_sites('data_output', 400, '../');
			break;
		    case 'account':
			animation_to_sites('data_output', 400, './account.php');
			break;
		    case 'mib':
			animation_to_sites('data_output', 400, './mib.php');
			break;
		    case 'agent':
			animation_to_sites('data_output', 400, './agent.php');
			break;
		}
	    });
	</script>
    </body>

</html>