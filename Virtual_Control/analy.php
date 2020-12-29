<?php
include_once __DIR__ . '/scripts/general/loader.php';
include_once __DIR__ . '/scripts/session/session_chk.php';
include_once __DIR__ . '/scripts/general/sqldata.php';
include_once __DIR__ . '/scripts/general/former.php';

session_action_user();
$getdata = session_get_userdata();

$loader = new loader();

//最初のページ
$fm_sl = new form_generator('fm_sl');
$fm_sl->Button('bt_sl_bk', 'ホームに戻る', 'button', 'home');
$fm_sl->SubTitle('エージェント選択', '以下からエージェントを選択してください。', 'object-group');
$fm_sl->Caption('[DATA]');
$fm_sl->Button('bt_sl_sb', '選択して情報を取得する', 'submit', 'home', 'dark', 'disabled');

//共通ページ
$fm_ld = fm_ld('fm_ld');
$fm_fl = fm_fl('fm_fl', '', '接続中にエラーが発生しました。', '以下をご確認ください。');
$fm_fl->openList();
$fm_fl->addList('エージェントとの接続をご確認ください。');
$fm_fl->addList('データベースとの接続をご確認ください。');
$fm_fl->addList('【アクセスログ】<br>[LOG]');
$fm_fl->closeList();
$fm_fl->Button('bt_fl_bk', '戻る', 'button', 'caret-square-left');

//結果ページ
$fm_rt = new form_generator('fm_rt');
$fm_rt->Button('bt_rt_bk', 'エージェント選択一覧へ戻る', 'button', 'caret-square-left');
$fm_rt->SubTitle('ANALY', '取得情報を以下に参照します。', 'poll');
$fm_rt->openList();
$fm_rt->addList('取得日時: [DATE]');
$fm_rt->addList('エージェントホスト: [HOST]');
$fm_rt->addList('コミュニティ: [COMMUNITY]');
$fm_rt->closeList();
$fm_rt->SubTitle('取得SNMP情報', '以下のサブツリーから取得してください。', 'object-group');
$fm_rt->Caption('[LIST]', true, 2);

//サブページ
$fm_sb = new form_generator('fm_sb');
$fm_sb->Button('bt_sb_bk', '戻る', 'button', 'chevron-circle-left');
$fm_sb->Title('ANALY - データ取得結果', 'poll-h');
$fm_sb->Caption('[DATA]', true, 3);
$fm_sb->Button('bt_sb_bk', '戻る', 'button', 'chevron-circle-left');
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'ANALY') ?>
	<?php echo form_generator::ExportClass() ?>
	<script type="text/javascript">
	    var r_data = [];
	    var r_page = '';
	</script>
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
	    //共通ファンクション
	    function ajax_get_agent(duration) {
		animation('data_output', 400, duration);
		ajax_dynamic_post_toget('../scripts/agent/agent_get.php').then(function (data) {
		    switch (data['code']) {
			case 0:
			    var fm_w = fm_sl.replace('[DATA]', data['data']);
			    animation('data_output', 400, fm_w);
			    break;
			case 1:
			    var fm_w = fm_fl.replace('[LOG]', data['log']);
			    animation('data_output', 400, fm_w);
			    break;
		    }
		});
	    }

	    function ajax_snmpwalk(f_data) {
		animation('data_output', 400, fm_ld);
		ajax_dynamic_post('../scripts/snmp/snmpwalk.php', f_data).then(function (data) {
		    switch (data['CODE']) {
			case 0:
			    r_data = data;
			    r_page = fm_rt.replace('[DATE]', data['DATE'])
				    .replace('[HOST]', data['HOST'])
				    .replace('[COMMUNITY]', data['COMMUNITY'])
				    .replace('[LIST]', data['LIST']);
			    change_result_page();
			    break;
			case 1:
			    var fm_w = fm_fl.replace('[LOG]', data['LOG']);
			    animation('data_output', 400, fm_w);
			    break;
		    }
		});
	    }

	    function change_result_page() {
		animation('data_output', 400, r_page);
	    }

	    function change_sub_page(num) {
		console.log(num);
		var data_sub = r_data['SUB'][num];
		var fm_w = fm_sb.replace('[DATA]', data_sub);
		animation('data_output', 400, fm_w);
	    }


	    //デフォルトページ読み込み
	    $(document).ready(function () {
		ajax_get_agent(0);
	    });

	    //fm_sl
	    
	    $(document).on('click', '#bt_sl_bk', function () {
		animation_to_sites('data_output', 400, './index.php');
	    });
	    
	    $(document).on('submit', '#fm_sl', function(event) {
		event.preventDefault();
		ajax_snmpwalk($(this).serializeArray());
	    });
	    
	    //fm_rt
	    
	    $(document).on('click', 'div[id^="sub_i"]', function() {
		var num = $(this).attr('id');
		change_sub_page(num);
	    });
	    
	    $(document).on('click', '#bt_rt_bk', function () {
		ajax_get_agent(400);
	    });
	    
	    //fm_fl
	    $(document).on('click', '#bt_fl_bk', function () {
		ajax_get_agent(400);
	    });
	    
	    //fm_sb
	    
	    $(document).on('click', '#bt_sb_bk', function() {
		change_result_page();
	    });

	    $(document).on('change', 'input[name="sl_ag"]', function () {
		var count = $('#fm_sl input:radio:checked').length;
		if (count === 1) {
		    $('#bt_sl_sb').prop('disabled', false);
		} else {
		    $('#bt_sl_sb').prop('disabled', true);
		}
	    });
	</script>
    </body>
</html>