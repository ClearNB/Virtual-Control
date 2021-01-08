<?php
include_once './scripts/general/loader.php';
include_once './scripts/session/session_chk.php';
include_once './scripts/general/sqldata.php';
include_once './scripts/general/former.php';

$loader = new loader();

$fm_pg = new form_generator('fm_pg');
$fm_pg->SubTitle('データベース初期化', 'ここでは、データベースの初期化を行うことができます。以下の注意事項をよく読んで実行してください。', 'sync');
$fm_pg->openList();
$fm_pg->addList('これを行うことにより初期状態に戻され、テーブルの定義やテーブルの内容が全て初期状態に置き換えられます。');
$fm_pg->addList('今まで作業していた内容が全て消えます。作業のためにデータを追加・変更していた場合は、バックアップを行う必要があります。');
$fm_pg->addList('初期化を行う前に、データベースサーバとWebサーバが相互に接続できているか確認してください。');
$fm_pg->closeList();
$fm_pg->Button('bt_pg_st', '初期化を開始', 'button', 'play');
$fm_pg->Button('bt_pg_bk', 'ホームに戻る', 'button', 'home');

$fm_in_ld = fm_ld('fm_in_ld', 'データベースの初期化中', '全てが完了するまで他の場所に移動しないでください。');

$fm_in_sc = new form_generator('fm_in_sc');
$fm_in_sc->SubTitle('初期化が完了しました！', '早速、新しくなったデータで試しましょう！', 'thumbs-up');
$fm_in_sc->openList();
$fm_in_sc->addList('ユーザ名: [USERID]');
$fm_in_sc->addList('パスワード: [PASS]');
$fm_in_sc->closeList();
$fm_in_sc->Button('bt_sc_ln', 'ホームに戻る', 'button', 'home');

$fm_in_fl = new form_generator('fm_in_fl');
$fm_in_fl->SubTitle('初期化に失敗しました', '以下のログをご確認ください。', 'exclamation-triangle');
$fm_in_fl->Caption('cap_log');
$fm_in_fl->Button('bt_fl_rs', 'やり直す', 'button', 'sync-alt');
$fm_in_fl->Button('bt_fl_ln', 'ホームに戻る', 'button', 'home');

?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'INIT') ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
	<?php echo $loader->navigation(999) ?>
	<?php echo $loader->load_Logo() ?>
	<?php echo $loader->Title('INIT', 'sync-alt') ?>

        <div id="data_output"></div>

        <!-- Footer -->
	<?php echo $loader->footer() ?>

        <!-- JavaScript dependencies -->
	<?php echo $loader->footerS() ?>

	<script type="text/javascript">
	    $(document).ready(function () {
		animation('data_output', 0, fm_pg);
	    });
	    
	    $(document).on('click', '#bt_pg_st, #bt_pg_bk', function() {
		switch($(this).attr('id')) {
		    case "bt_pg_st":
			animation('data_output', 400, fm_in_ld);
			data_post();
			break;
		    case "bt_pg_bk":
			animation_to_sites('data_output', 400, './');
			break;
		}
	    });

	    $(document).on('click', '#bt_fl_ln, #bt_fl_rs', function () {
		switch ($(this).attr('id')) {
		    case "bt_fl_ln":
			animation_to_sites('data_output', 400, './');
			break;
		    case "bt_fl_rs":
			animation('data_output', 400, fm_in_ld);
			data_post();
			break;
		}
	    });
	    
	    $(document).on('click', '#bt_sc_ln', function () {
		switch ($(this).attr('id')) {
		    case "bt_sc_ln":
			animation_to_sites('data_output', 400, './');
			break;
		}
	    });

	    function data_post() {
		ajax_dynamic_post_toget('./scripts/init/init.php').then(function (data) {
		    switch (data['CODE']) {
			case 0:
			    var fm_w = fm_in_sc.replace('[USERID]', data['USERID']).replace('[PASS]', data['PASS']);
			    animation('data_output', 400, fm_w);
			    break;
			case 1:
			    var fm_w = fm_in_fl.replace('cap_log', data['ERROR']);
			    animation('data_output', 400, fm_w);
			    break;
		    }
		});
	    }
	</script>
    </body>

</html>