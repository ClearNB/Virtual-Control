<?php
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/session/session_chk.php';
include_once __DIR__ . '/../scripts/general/sqldata.php';
include_once __DIR__ . '/../scripts/general/former.php';

session_action_user();
$getdata = session_get_userdata();

$loader = new loader();

$fm_pg = new form_generator('fm_pg');
$fm_pg->SubTitle($getdata['USERNAME'] . 'さん', 'アクセス監視をしましょう。<br>行動を選択してください。', 'user', false, $getdata['PERMISSION_TEXT']);
$fm_pg->openListGroup();
$fm_pg->addListGroup('analy', 'アナリティクス', 'chart-pie', 'アクセス状況をエージェント別に監視します', '詳しくはクリック！');
$fm_pg->addListGroup('warn', '警告情報', 'file-excel', '日別に発生したトラップログを表示します', '詳しくはクリック！');
if ($getdata['PERMISSION'] == 0) {
    $fm_pg->addListGroup('refresh', 'データベース初期化', 'sync', '【デバッグ専用】テーブルデータを初期化します。', 'クリックして実行');
    $fm_pg->addListGroup('option', 'オプション', 'wrench', '監視のための設定を行います', '詳しくはクリック！');
}
$fm_pg->closeListGroup();
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'DASH', true) ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>

	<?php echo $loader->load_Logo() ?>

	<?php echo $loader->Title('DASH', 'align-justify') ?>

	<div id="data_output"></div>

	<?php echo $loader->footer(); ?>
	<?php echo $loader->footerS(true); ?>

	<script type="text/javascript">
	    $(document).ready(function () {
		animation('data_output', 0, fm_pg);
	    });

	    $(document).on('click', '#refresh, #analy, #warn, #option', function () {
		switch ($(this).attr('id')) {
		    
		    case "analy":
			animation_to_sites("data_output", 400, "../analy");
			break;
		    case "warn":
			animation_to_sites("data_output", 400, "../warn");
			break;
		    <?php if ($getdata['PERMISSION'] == 0) { echo 'case "refresh": animation_to_sites("data_output", 400, "../init"); break;'; } ?>
		    <?php if ($getdata['PERMISSION'] == 0) { echo 'case "option": animation_to_sites("data_output", 400, "../option"); break;'; } ?>
		}
	    });
	</script>
    </body>

</html>