<?php
include_once '../scripts/general/loader.php';
include_once '../scripts/session/session_chk.php';
include_once '../scripts/general/sqldata.php';
include_once '../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();

//OID・それぞれの名前の注意事項記述
$oid_group_text = '<strong>【条件】半角数字と記号（.）を用いて255文字まで（OIDである必要があります）</strong>';
$oid_sub_text = '<strong>【条件】サブツリーでは、グループのOIDに続く半角数字を入力します<br>【入力例】（1.3.6.1.2.1.）【1】</strong>';
$oid_node_text = '<strong>【条件】ノードでは、サブツリーOIDに続く半角数字を入力します</strong>';

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

//1. 選択（サブツリー）
$fm_sl_sb = new form_generator('fm_sl_sb');

//1-1. 作成（サブツリー）
$fm_cr_sb = new form_generator('fm_cr_sb');

//1-2-1. 編集（サブツリー・選択）
$fm_ed_sb = new form_generator('fm_ed_sb');

//1-2-2. 編集（サブツリー・ノード一覧）
$fm_ed_nd_ls = new form_generator('fm_ed_nd_ls');

//1-2-2. 編集（サブツリー・ノード要素編集）

//1-2-2-1. 編集（サブツリー・サブツリーOID）

//1-2-2-2. 編集（サブツリー・アイコン選択）
$_24 = new form_generator('fm_ed_sb_ic');
$_24->Button('bt_nd_bk', 'ノード編集に戻る', 'button', '');
$_24->SubTitle('アイコン選択', '以下から適切なアイコンを選択してください', 'icons');
$_24->Caption('[ICON_SELECT]');

//1-2-3. 編集（サブツリー・サブツリー名）
$_23 = new form_generator('fm_ed_sb_nm');
$_23->Button('bt_sb_bk', 'ノード一覧に戻る', 'button', 'chevron-circle-left');

//1-3. 削除（サブツリー）

//2. 選択（グループ）【TP】


//2-1. 作成（グループ）

//2-2. 編集（グループ）

//2-2-1. 編集（グループ・グループOID）

//2-2-2. 編集（グループ・グループ名）

//2-3. 削除（グループ）

?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - MIB', true) ?>
	<?php echo form_generator::ExportClass() ?>
	<script type="text/javascript">
	    var mib_data = [];
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
	    function table_generate(duration) {
		f_id.resetID();
		w_page = '';
		animation('data_output', duration, fm_ld);
		ajax_dynamic_post_toget('../scripts/mib/mib_get.php').then(function (data) {
		    switch (data['code']) {
			case 0:
			    a_data = data;
			    var fm_w = fm_pg.replace('[DATA]', data['data']);
			    animation('data_output', 400, fm_w);
			    break;
			case 1:
			    var fm_w = fm_fl.replace('[DATA]', data['ERR_TEXT']);
			    animation('data_output', 400, fm_w);
			    break;
		    }
		});
	    }
	    
	    $(document).ready(function() {
		animation('data_output', 0, fm_ed_sb_ic);
	    });
	</script>
    </body>
</html>