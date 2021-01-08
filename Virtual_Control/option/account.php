<?php
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/session/session_chk.php';
include_once __DIR__ . '/../scripts/general/sqldata.php';
include_once __DIR__ . '/../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();

//ユーザID・ユーザ名・パスワード注意事項記述
$userid_text = '<strong>【条件】半角英数字（[小文字] 数字・英字組み合わせ）: 5-20文字</strong>';
$username_text = '<strong>【条件】(半角) 1-30文字, (全角) 1-15文字</strong><br>（※）文字は「UTF-8」のエンコード方式によりカウントされます。';
$password_text = '<strong>【条件】半角英数字[小文字・大文字組み合わせ]・数字・記号( $ _ のみ)を組み合わせて10-30文字<br>（※）記号は指定された2文字のみをご利用ください。<br>例: GSC_Pass$01（11文字）';
$password_r_text = '確認のためもう一度パスワードを入力してください。';

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

//共通ページ（ユーザ選択エラー）
$fm_fl_us = fm_fl('fm_fl_us', '', 'ユーザに変更を加えることはできません。', 'このユーザは現在ログイン中です。削除時は、あなたを含め、ログインしているユーザは削除できません。');
$fm_fl_us->Button('bt_fl_us_bk', 'アカウント選択画面に戻る', 'button', 'chevron-circle-left');

$fm_at = fm_at('fm_at', $getdata['USERNAME']);

//1: アカウント情報画面（TP）
$fm_pg = new form_generator('fm_pg');
$fm_pg->Button('bt_ac_bk', '設定一覧へ', 'button', 'list');
$fm_pg->SubTitle('ユーザ作成・編集・削除', '作成は「作成」ボタンを、編集・削除は「未ログイン」のユーザを左のラジオボタンで選択してからボタンを押します。', 'user');
$fm_pg->Caption('[DATA]');
$fm_pg->Button('bt_ac_cr', '作成', 'button', 'plus-square');
$fm_pg->Button('bt_ac_ed', '編集', 'button', 'edit');
$fm_pg->Button('bt_ac_dl', '削除', 'button', 'trash-alt');

//2: アカウント作成（入力）
$fm_ac_cr = new form_generator('fm_ac_cr');
$fm_ac_cr->Button('bt_cr_bk', 'アカウント選択へ戻る', 'button', 'chevron-circle-left');
$fm_ac_cr->SubTitle('アカウント作成', '以下の情報を入力してください', 'plus-circle', false, '1: 情報入力');
$fm_ac_cr->Input('in_ac_id', 'ユーザID', $userid_text, 'file-invoice', true, false, '(?=.*?[a-z])(?=.*?[0-9]){5,20}');
$fm_ac_cr->Input('in_ac_nm', 'ユーザ名', $username_text, 'user-circle', true);
$fm_ac_cr->Password('in_ac_ps', 'パスワード', $password_text, 'key', true);
$fm_ac_cr->Password('in_ac_ps_rp', 'パスワードの確認', $password_r_text, 'key', true);
$fm_ac_cr->FormTitle('権限', 'user-shield');
$fm_ac_cr->openList();
$fm_ac_cr->addList('VCServer: 監視に加え、設定管理（アカウント・エージェント・MIB）を行うことができます。つまり管理者権限です。');
$fm_ac_cr->addList('VCHost: ANALY, WARNでの監視のみの権限が与えられます。設定管理を行うことはできません。');
$fm_ac_cr->closeList();
$fm_ac_cr->Check(1, 'rd_01', 'in_ac_pr', '0', 'VCServer', true);
$fm_ac_cr->Check(1, 'rd_02', 'in_ac_pr', '1', 'VCHost', false);
$fm_ac_cr->Button('bt_cr_nx', '次へ', 'submit', 'sign-in-alt');

//3: アカウント編集（項目選択画面）
$fm_ac_ed = new form_generator('fm_ac_ed');
$fm_ac_ed->Button('bt_ed_bk', 'アカウント選択画面に戻る', 'button', 'chevron-circle-left');
$fm_ac_ed->SubTitle('[USERNAME] ([USERID])', '以下から変更したい項目を選択してください。', 'edit', false, '[PERMISSION]');
$fm_ac_ed->Button('bt_ed_id', 'ユーザID', 'button', 'file-invoice');
$fm_ac_ed->Button('bt_ed_nm', 'ユーザ名', 'button', 'user-circle');
$fm_ac_ed->Button('bt_ed_ps', 'パスワード', 'button', 'key');

//3-1: アカウント編集（ユーザID）
$fm_ed_id = new form_generator('fm_ed_id');
$fm_ed_id->Button('bt_id_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
$fm_ed_id->SubTitle('アカウント編集（ユーザID）', '以下の情報をもとに変更を行います。', 'edit');
$fm_ed_id->openList();
$fm_ed_id->addList('変更対象のユーザ: [USERID]');
$fm_ed_id->addList('現在: [USERID]');
$fm_ed_id->closeList();
$fm_ed_id->Input('in_ac_id', 'ユーザID', $userid_text, 'file-invoice', true);
$fm_ed_id->Button('bt_id_nx', '次へ', 'submit', 'sign-in-alt');

//3-2: アカウント編集（ユーザ名）
$fm_ed_nm = new form_generator('fm_ed_nm');
$fm_ed_nm->Button('bt_nm_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
$fm_ed_nm->SubTitle('アカウント編集（ユーザ名）', '以下の情報をもとに変更を行います。', 'edit');
$fm_ed_nm->openList();
$fm_ed_nm->addList('変更対象のユーザ: [USERID]');
$fm_ed_nm->addList('現在: [USERNAME]');
$fm_ed_nm->closeList();
$fm_ed_nm->Input('in_ac_nm', 'ユーザ名', $username_text, 'user-circle', true);
$fm_ed_nm->Button('bt_nm_nx', '次へ', 'submit', 'sign-in-alt');

//3-3: アカウント編集（パスワード）
$fm_ed_ps = new form_generator('fm_ed_ps');
$fm_ed_ps->Button('bt_nm_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
$fm_ed_ps->SubTitle('アカウント編集（パスワード）', '以下の情報をもとに変更を行います。', 'edit');
$fm_ed_ps->openList();
$fm_ed_ps->addList('変更対象のユーザ: [USERID]');
$fm_ed_ps->addList('現在: (パスワードは表示されません)');
$fm_ed_ps->closeList();
$fm_ed_ps->Password('in_ac_ps', 'パスワード', $password_text, 'key', true);
$fm_ed_ps->Password('in_ac_ps_rp', 'パスワードの確認', $password_r_text, 'key', true);
$fm_ed_ps->Button('bt_nm_nx', '次へ', 'submit', 'sign-in-alt');

//4-1: アカウント削除
$fm_ac_dl = new form_generator('fm_ac_dl');
$fm_ac_dl->Button('bt_dl_bk', 'アカウント選択画面に戻る', 'button', 'chevron-circle-left');
$fm_ac_dl->SubTitle('アカウント削除', '以下のユーザを削除します。', 'trash-alt');
$fm_ac_dl->openList();
$fm_ac_dl->addList('ユーザID: [USERID]');
$fm_ac_dl->addList('ユーザ名: [USERNAME]');
$fm_ac_dl->addList('権限: [PERMISSION]');
$fm_ac_dl->closeList();
$fm_ac_dl->Button('bt_dl_sb', '削除する', 'button', 'sign-in-alt');

//5: 更新確認画面
$fm_cf = new form_generator('fm_cf');
$fm_cf->SubTitle('入力確認', '入力事項が正しければ「更新する」を押してください。<br>（※）「キャンセル」の場合、アカウント選択画面に遷移します。', 'user-check');
$fm_cf->Caption('[DATA]');
$fm_cf->Button('bt_cf_sb', '更新する', 'button', 'sign-in-alt');
$fm_cf->Button('bt_cf_bk', 'キャンセル', 'button', 'chevron-circle-left');

//6: 更新成功画面
$fm_cs = new form_generator('fm_cs');
$fm_cs->SubTitle('更新に成功しました！', 'ボタンを押して変更が反映したか確認しましょう！', 'check-square');
$fm_cs->Button('bt_cs_bk', 'アカウント選択画面に戻る', 'button', 'chevron-circle-left');
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - ACCOUNT', true) ?>
	<?php echo form_generator::ExportClass() ?>
	<script type="text/javascript">
	    var a_data = [];
	    var f_id = new functionID();
	    var w_page = '';
	</script>
    </head>
    <body>
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
	<?php echo $loader->load_Logo() ?>

	<?php echo $loader->Title('OPTION - ACCOUNT', 'user') ?>
	<div id="data_output"></div>

	<?php echo $loader->footer() ?>
	<?php echo $loader->footerS(true) ?>

	<script type="text/javascript">
	    function edit_preuserid(data) {
		a_data['pre_userid'] = data;
	    }
	    
	    function getUserData(page, types) {
		var res = page;
		for (var i = 0; i < types.length; i++) {
		    switch (types[i]) {
			case 0: //UserID
			    res = res.replaceAll('[USERID]', a_data[a_data['pre_userid']]['USERID']);
			    break;
			case 1: //UserName
			    res = res.replaceAll('[USERNAME]', a_data[a_data['pre_userid']]['USERNAME']);
			    break;
			case 2: //Permission
			    res = res.replaceAll('[PERMISSION]', a_data[a_data['pre_userid']]['PERMISSION']);
			    break;
		    }
		}
		return res;
	    }

	    function page_generate(page) {
		var data = getUserData(page, [0, 1, 2]);
		animation('data_output', 400, data);
	    }

	    function table_generate(duration) {
		f_id.resetID();
		w_page = '';
		animation('data_output', duration, fm_ld);
		ajax_dynamic_post_toget('../scripts/account/account_get.php').then(function (data) {
		    switch (data['code']) {
			case 0:
			    a_data = data['a_data'];
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

	    function data_function(data) {
		data.push({name: "functionid", value: f_id.getFunctionID.toString()});
		animation('data_output', 400, fm_ld);
		ajax_dynamic_post('../scripts/account/account_set.php', data).then(function (data) {
		    switch (data['CODE']) {
			case 0:
			    animation('data_output', 400, fm_cs);
			    break;
			case 1:
			    var fm_w = fm_fl.replace('[DATA]', data['ERR_TEXT']);
			    animation('data_output', 400, fm_w);
			    break;
			case 2:
			    var fm_w = fm_fl_in.replace('[DATA]', data['ERR_TEXT']);
			    animation('data_output', 400, fm_w);
			    break;
			case 3:
			    animation('data_output', 400, fm_fl_us);
			    break;
			case 4:
			    animation('data_output', 400, fm_at);
			    break;
			case 5:
			    animation('data_output', 400, fm_fl_at);
			    break;
			case 6:
			    var fm_w = fm_cf.replace('[DATA]', data['DATA']);
			    if (typeof a_data['pre_userid'] !== 'undefined') {
				page_generate(fm_w);
			    } else {
				animation('data_output', 400, fm_w);
			    }
			    break;
		    }
		});
	    }

	    //読み込み時
	    $(document).ready(function () {
		table_generate(0);
	    });

	    //1: アカウント選択画面（戻る・作成・編集・削除ボタン押下）
	    $(document).on('click', '#bt_ac_bk, #bt_ac_cr, #bt_ac_ed, #bt_ac_dl', function () {
		switch ($(this).attr('id')) {
		    case "bt_ac_bk":
			animation_to_sites('data_output', 400, './');
			break;
		    case "bt_ac_cr":
			f_id.change_account_create();
			animation('data_output', 400, fm_ac_cr);
			break;
		    case "bt_ac_ed":
			edit_preuserid($('input[name="pre_userid"]:checked').val());
			page_generate(fm_ac_ed);
			break;
		    case "bt_ac_dl":
			f_id.change_account_delete();
			edit_preuserid($('input[name="pre_userid"]:checked').val());
			page_generate(fm_ac_dl);
			break;
		}
	    });

	    //戻るボタン（作成・編集・削除から）
	    $(document).on('click', '#bt_cr_bk, #bt_ed_bk, #bt_dl_bk, #bt_fl_us_bk, #bt_cf_bk, #bt_at_bk', function () {
		table_generate(400);
	    });

	    //フォームデータ送信時
	    $(document).on('submit', '#fm_ac_cr, #fm_ed_id, #fm_ed_nm, #fm_ed_ps, #fm_ac_dl', function (event) {
		event.preventDefault();
		w_page = document.getElementById('data_output').innerHTML.toString();
		var data = $(this).serializeArray();
		if ($(this).attr('id').toString().indexOf('fm_ed') === 0) {
		    data.push({name: "pre_userid", value: a_data['pre_userid']});
		}
		a_data['data_presub'] = data;
		data_function(data);
	    });

	    $(document).on('click', '#bt_dl_sb', function () {
		var data = [{name: "pre_userid", value: a_data['pre_userid']}];
		a_data['data_presub'] = data;
		data_function(data);
	    });

	    //認証データ送信時
	    $(document).on('submit', '#fm_at', function (event) {
		event.preventDefault();
		var data = $(this).serializeArray().concat(a_data['data_presub']);
		data_function(data);
	    });

	    //確認終了後
	    $(document).on('click', '#bt_cf_sb', function () {
		var data = a_data['data_presub'];
		data_function(data);
	    });

	    //2: アカウント編集（各画面遷移）
	    $(document).on('click', '#bt_ed_id, #bt_ed_nm, #bt_ed_ps, #bt_ed_bk', function () {
		switch ($(this).attr('id')) {
		    case "bt_ed_id":
			f_id.change_account_edit_userid();
			page_generate(fm_ed_id);
			break;
		    case "bt_ed_nm":
			f_id.change_account_edit_username();
			page_generate(fm_ed_nm);
			break;
		    case "bt_ed_ps":
			f_id.change_account_edit_password();
			page_generate(fm_ed_ps);
			break;
		    case "bt_ed_bk":
			table_generate(400);
			break;
		}
	    });

	    //編集・各画面からの戻るボタン
	    $(document).on('click', '#bt_id_bk, #bt_nm_bk, #bt_ps_bk', function () {
		page_generate(fm_ac_ed);
	    });

	    $(document).on('click', '#bt_fl_at_bk', function () {
		animation('data_output', 400, fm_at);
	    });

	    $(document).on('click', '#bt_fl_rt, #bt_cs_bk, #bt_fl_in_bk', function () {
		switch ($(this).attr('id')) {
		    case 'bt_fl_rt':
		    case 'bt_cs_bk':
			animation_to_sites('data_output', 400, './account.php');
			break;
		    case 'bt_fl_in_bk':
			animation('data_output', 400, w_page);
			break;
		}
	    });

	    $(document).on('change', 'input[name="pre_userid"]', function () {
		var count = $('#fm_pg input:radio:checked').length;
		if (count === 1) {
		    $('#bt_ac_ed, #bt_ac_dl').prop('disabled', false);
		} else {
		    $('#bt_ac_ed, #bt_ac_dl').prop('disabled', true);
		}
	    });
	</script>
    </body>
</html>