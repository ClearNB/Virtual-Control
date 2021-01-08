<?php
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/session/session_chk.php';
include_once __DIR__ . '/../scripts/general/sqldata.php';
include_once __DIR__ . '/../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();

$fm_pg = new form_generator('fm_pg', '');
$fm_pg->Button('bt_pg_bk', 'ホームに戻る', 'button', 'home');
$fm_pg->SubTitle('設定一覧', '設定したい項目を選んでください。', 'wrench');
$fm_pg->openListGroup();
$fm_pg->addListGroup('account', 'ACCOUNT', 'user', 'アカウント管理ページ', 'アカウント一覧／作成／編集／削除');
$fm_pg->addListGroup('mib', 'MIB', 'database', 'MIB管理ページ', 'MIB一覧／作成／編集／削除');
$fm_pg->addListGroup('agent', 'AGENT', 'server', 'エージェント管理ページ', 'エージェント一覧／作成／編集／削除');
$fm_pg->closeListGroup();
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION', true); ?>
	<?php echo form_generator::ExportClass() ?>
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