<?php
include_once '../scripts/general/loader.php';
include_once '../scripts/session/session_chk.php';
include_once '../scripts/general/sqldata.php';
include_once '../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();

//共通ページ（読み込み）
$fm_ld = fm_ld('fm_ld');

//共通ページ（エラー）
$fm_fl = fm_fl('fm_fl', '', 'エラーが発生しました。', '以下をご確認ください。');
$fm_fl->openList();
$fm_fl->addList('データベースとの接続をご確認ください。');
$fm_fl->addList('要求しているデータと実際のデータを比べ、記述や内容が正しいかどうかをご確認ください。');
$fm_fl->addList('アカウント認証であるセッションが切れていると思われます。もう一度ログインし直してから再試行してください。');
$fm_fl->addList('【アクセスログ】<br>[DATA]');
$fm_fl->closeList();
$fm_fl->Button('bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt');

//共通ページ（チェックエラー）
$fm_fl_in = fm_fl('fm_fl_in', '', 'チェックエラーが発生しました。', '以下をご確認ください。');
$fm_fl_in->Caption('[DATA]');
$fm_fl_in->Button('bt_fl_in_bk', '入力に戻る', 'button', 'chevron-circle-left');

//共通ページ（認証エラー）
$fm_fl_at = fm_fl('fm_fl_at', '', '認証が発生しました。', '認証のために入力したパスワードが正しいかどうかご確認ください。');
$fm_fl_at->Button('bt_fl_at_bk', '認証に戻る', 'button', 'chevron-circle-left');
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - MIB', true) ?>
	<?php echo form_generator::ExportClass() ?>
	<script type="text/javascript">
	    var m_data = [];
	    var f_id = new functionID();
	</script>
    </head>
    <body class="text-monospace">
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
	<?php echo $loader->load_Logo() ?>

	<?php echo $loader->Title('OPTION - MIB', 'object-group') ?>
	<div id="data_output"></div>

	<?php echo $loader->footer() ?>
	<?php echo $loader->footerS(true) ?>

	<script type="text/javascript">
	    function mib_data_get(duration, dataid) {
		var fdata = {"request_id": f_id.getFunctionIDRow, "request_data_id": dataid};
		f_id.resetID();
		animation('data_output', duration, fm_ld);
		ajax_dynamic_post('../scripts/mib/mib_get.php', fdata).then(function (data) {
		    switch (data['CODE']) {
			case 0:
			    m_data = data['DATA'];
			    animation('data_output', 400, data['PAGE']);
			    break;
			case 1:
			    var fm_w = fm_fl.replace('[DATA]', data['LOG']);
			    animation('data_output', 400, fm_w);
			    break;
		    }
		});
	    }

	    $(document).ready(function () {
		f_id.change_mib_group_select();
		mib_data_get(0, 0);
	    });

	    $(document).on('click', '#bt_hm', function () {
		animation_to_sites('data_output', 400, './');
	    });
	</script>
    </body>
</html>