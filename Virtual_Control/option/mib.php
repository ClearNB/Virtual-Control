<?php
include_once '../scripts/general/loader.php';
include_once '../scripts/session/session_chk.php';
include_once '../scripts/general/sqldata.php';
include_once '../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();

//読み込み画面
$fm_ld = fm_ld('fm_ld', 'データを反映中です', 'しばらくお待ちください...');

//失敗画面（入力エラー・データベースエラー）
$fm_fl_at = fm_fl('fm_fl_at', 'bt_bk_at', '認証に失敗しました。', 'パスワードをもう一度ご確認ください。');
$fm_fl_in = fm_fl('fm_fl_in', 'bt_bk_in', '入力に誤りがあります。', '以下をご覧ください。<br>[ERROR_TEXT]');
$fm_fl_dt = fm_fl('fm_fl_dt', '', 'データベースの接続に失敗しました。', 'データベース操作ができないため、これ以上の操作ができません。');

//認証画面
$fm_at = fm_at('fm_at', $getdata['USERNAME']);

//1: サブツリー選択画面（FP）
$fm_sl_sb = new form_generator('fm_sl_sb', '');
$fm_sl_sb->Button('bt_sb_bk', 'オプション一覧画面へ戻る', 'button', 'caret-square-left');
$fm_sl_sb->openListGroup();
$fm_sl_sb->addListGroup('group', '[グループ名]', 'object-group', '[OID]', 'クリックしてグループ選択へ');
$fm_sl_sb->closeList();
$fm_sl_sb->SubTitle('MIBサブツリー選択', 'サブツリーを選択してください。', 'project-diagram');
$fm_sl_sb->Caption('[Data]');
$fm_sl_sb->Button('bt_sb_cr', 'MIBサブツリー作成', 'button', 'plus-square');
$fm_sl_sb->Button('bt_sb_ed', 'MIBサブツリー編集', 'button', 'edit', 'dark', 'disabled');
$fm_sl_sb->Button('bt_sb_dl', 'MIBサブツリー削除', 'button', 'trash', 'dark', 'disabled');

//1-2: サブツリー作成（入力）
$fm_sb_cr = new form_generator('fm_sb_cr');
$fm_sb_cr->Button('bt_cr_bk', 'サブツリー選択へ戻る', 'button', 'project-diagram');
$fm_sb_cr->SubTitle('MIBサブツリー作成', 'MIBグループ内のサブツリーを作成します。', 'plus-square', false, '1: 入力');
$fm_sb_cr->openList();
$fm_sb_cr->addList('MIBグループOID: [グループOID]');
$fm_sb_cr->addList('MIBグループ名: [グループ名]');
$fm_sb_cr->closeList();
$fm_sb_cr->Input('in_mb_id', 'サブツリーID', '（半角英数字・1-255文字以内）<br>例: (グループOID) 1.3.6.1.2.1.1, (サブツリーOID) 1.3.6.1.2.1.1.1<br>→ 1', 'id-card-alt', true);
$fm_sb_cr->Caption('現在の指定: [サブツリーOID]');
$fm_sb_cr->Input('in_mb_nm', 'サブツリー名', '（半角英数字・1-50文字以内）<br>サブツリーOIDに対する説明を加えます。', 'address-card', true);
$fm_sb_cr->Button('bt_cr_sb', '次へ進む', 'button', 'caret-square-right');

//1-3: サブツリー編集（選択）
$fm_sb_ed_sl = new form_generator('fm_sb_ed_sl');
$fm_sb_cr->Button('bt_cr_bk', 'サブツリー選択へ戻る', 'button', 'project-diagram');
$fm_sb_cr->SubTitle('MIBサブツリー作成', 'MIBグループ内のサブツリーを作成します。', 'plus-square', false, '1: 入力');
$fm_sb_cr->openList();
$fm_sb_cr->addList('MIBサブツリーOID: [グループOID]');
$fm_sb_cr->addList('MIBサブツリー名: [グループ名]');
$fm_sb_cr->closeList();

//2: グループ選択画面
$fm_sl_gp = new form_generator('fm_sl_gp');
$fm_sl_gp->Button('bt_gp_bk', 'サブツリー選択へ戻る', 'button', 'project-diagram');
$fm_sl_gp->SubTitle('MIBグループの選択', 'グループを選択してください。', 'object-group');
$fm_sl_gp->Caption('[Data]');
$fm_sl_gp->Button('bt_gp_sl', 'MIBグループ選択', 'button', 'check-square');
$fm_sl_gp->Button('bt_gp_cr', 'MIBグループ作成', 'button', 'plus-square');
$fm_sl_gp->Button('bt_gp_ed', 'MIBグループ編集', 'button', 'edit', 'dark', 'disabled');
$fm_sl_gp->Button('bt_gp_dl', 'MIBグループ削除', 'button', 'trash', 'dark', 'disabled');

//2-1: グループ選択画面
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - MIB', true) ?>
	<?php echo form_generator::ExportClass() ?>
	<script type="text/javascript">
	    var mib_data = [];
	</script>
    </head>
    <body>
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
	<?php echo $loader->load_Logo() ?>
	
	<?php echo $loader->Title('OPTION - MIB', 'server') ?>
	<div id="data_output"></div>
	
	<?php echo $loader->footer() ?>
	<?php echo $loader->footerS(true) ?>
	
	<script type="text/javascript">
	    $(document).ready(function() {
		animation('data_output', 0, fm_sl_sb);
	    });
	    
	    //1: サブツリー選択
	    $(document).on('click', '#bt_sb_bk, #group, #bt_sb_cr, #bt_sb_ed, #bt_sb_dl', function() {
		switch($(this).attr('id')) {
		    case 'bt_sb_bk':
			animation_to_sites('data_output', 400, './');
			break;
		    case 'group':
			animation('data_output', 400, fm_sl_gp);
			break;
		    case 'bt_sb_cr':
			animation('data_output', 400, fm_sb_cr);
			break;
		}
	    });
	    
	    //2: グループ選択
	    $(document).on('click', '#bt_gp_bk, #bt_gp_sl, #bt_gp_cr, #bt_gp_ed, #bt_gp_dl', function () {
		switch($(this).attr('id')) {
		    case 'bt_gp_bk':
			animation('data_output', 400, fm_sl_sb);
			break;
		}
	    });
	    
	    //3: 
	</script>
    </body>
</html>