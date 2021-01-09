<?php
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/session/session_chk.php';
include_once __DIR__ . '/../scripts/general/sqldata.php';
include_once __DIR__ . '/../scripts/general/former.php';

session_action_user();
$getdata = session_get_userdata();

$loader = new loader();

$fm_ld = fm_ld('fm_ld');

$fm_fl = fm_fl('fm_fl', '', '接続中にエラーが発生しました。', '以下をご確認ください。');
$fm_fl->openList();
$fm_fl->addList('トラップファイルが規定の場所に置いてあるか、マニュアルの通りに確認してください。');
$fm_fl->addList('トラップログの場所を開示する権限が失効している可能性があります。');
$fm_fl->addList('【アクセスログ】<br>[LOG]');
$fm_fl->closeList();
$fm_fl->Button('bt_fl_rt', '再試行', 'button', 'sync-alt');
$fm_fl->Button('bt_fl_bk', 'ホームに戻る', 'button', 'home');

//結果ページ
$fm_rt = new form_generator('fm_rt');
$fm_rt->Button('bt_rt_bk', 'ホームに戻る', 'button', 'home');
$fm_rt->SubTitle('WARN', '取得情報を以下に参照します。', 'exclamation-triangle');
$fm_rt->openList();
$fm_rt->addList('取得日時: [DATE]');
$fm_rt->closeList();
$fm_rt->Button('bt_rt_rt', '最新の状態に更新する', 'button', 'sync-alt');
$fm_rt->SubTitle('トラップ情報', 'ログは日別に管理されています。', 'calendar-week');
$fm_rt->Caption('[SELECT]', false, 2);
$fm_rt->SubTitle('結果CSVをダウンロード', '取得した情報を、コンマ区切りのCSVファイル（SJIS-winフォーマット）で出力することができます。<br>（※）OSによってはダウンロードできない場合があります。', 'file-csv');
$fm_rt->Button('bt_rt_dl', 'ダウンロード', 'button', 'download');

//サブページ
$fm_sb = new form_generator('fm_sb');
$fm_sb->Button('bt_sb_bk', '戻る', 'button', 'chevron-circle-left');
$fm_sb->Title('WARN - データ取得結果', 'exclamation-triangle');
$fm_sb->Caption('[SUB]', true, 3);
$fm_sb->Button('bt_sb_bk', '戻る', 'button', 'chevron-circle-left');
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'WARN', true) ?>
	<?php echo form_generator::ExportClass() ?>
	<script type="text/javascript">
	    var r_data = [];
	    var r_page = '';
	</script>
    </head>

    <body class="text-monospace">
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>

	<?php echo $loader->load_Logo() ?>

	<?PHP echo $loader->Title('WARN', 'exclamation-triangle') ?>

        <div id="data_output"></div>

	<?php echo $loader->footer() ?>

	<?php echo $loader->footerS(true) ?>

	<script type="text/javascript">
	    function download_csv() {
		post('../download/', {'download-data': r_data['CSV'], 'file-name': 'csvtrapdata-[DATE].csv'});
	    }

	    function ajax_snmpwarn(dist) {
		animation('data_output', dist, fm_ld);
		ajax_dynamic_post_toget('../scripts/warn/snmpwarn.php').then(function (data) {
		    switch (data['CODE']) {
			case 0:
			    r_data = data;
			    r_page = fm_rt.replace('[SELECT]', data['SELECT']).replace('[DATE]', data['DATE']);
			    change_result_page();
			    break;
			case 1:
			    var fm_w = fm_fl.replace('[LOG]', data['LOG']);
			    animation('data_output', 400, fm_w);
			    break;
		    }
		});
	    }

	    $(document).ready(function () {
		ajax_snmpwarn(0);
	    });

	    $(document).on('click', '#bt_fl_rt, #bt_fl_bk', function () {
		switch ($(this).attr('id')) {
		    case "bt_fl_rt":
			ajax_snmpwarn(400);
			break;
		    case "bt_fl_bk":
			animation_to_sites('data_output', 400, '../');
			break;
		}
	    });

	    $(document).on('click', '#bt_rt_rt, #bt_rt_bk, #bt_rt_dl', function () {
		switch ($(this).attr('id')) {
		    case "bt_rt_rt":
			ajax_snmpwarn(400);
			break;
		    case "bt_rt_bk":
			animation_to_sites('data_output', 400, '../');
			break;
		    case "bt_rt_dl":
			download_csv();
			break;
		}
	    });

	    $(document).on('click', 'div[id^="sub_i"]', function () {
		var num = $(this).attr('id');
		change_sub_page(num);
	    });

	    function change_result_page() {
		animation('data_output', 400, r_page);
	    }

	    function change_sub_page(num) {
		var data_sub = r_data['SUB'][num];
		var fm_w = fm_sb.replace('[SUB]', data_sub);
		animation('data_output', 400, fm_w);
	    }

	    $(document).on('click', '#bt_sb_bk', function () {
		change_result_page();
	    });
        </script>
    </body>
</html>