<?php
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/session/session_chk.php';
include_once __DIR__ . '/../scripts/general/sqldata.php';
include_once __DIR__ . '/../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();

//エージェントホスト・コミュニティ注意事項記述
$agenthost_text = '<strong>【条件】ドメイン名・IPv4アドレス・IPv6のいずれかを入力していること<br>（※）IPv6アドレスは「0省略」記述が可能です。</strong>';
$community_text = '<strong>【条件】半角英数字（小文字・大文字）・記号（$ _ のみ）を用いて255文字まで';

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

$fm_fl_ab = fm_fl('fm_fl_ab', '', '手続きエラーが発生しました。', '以下をご確認ください。');
$fm_fl_ab->openList();
$fm_fl_ab->addList('エージェント情報を選択されていないのにも関わらず、処理が行われようとしました。');
$fm_fl_ab->addList('エージェント情報が無いと処理ができません。');
$fm_fl_ab->closeList();
$fm_fl_ab->Button('bt_fl_rt', 'ページを再読込する', 'button', 'caret-square-left');

//共通ページ（チェックエラー）
$fm_fl_in = fm_fl('fm_fl_in', '', 'チェックエラーが発生しました。', '以下をご確認ください。');
$fm_fl_in->Caption('[DATA]');
$fm_fl_in->Button('bt_fl_in_bk', '入力に戻る', 'button', 'chevron-circle-left');

//共通ページ（認証エラー）
$fm_fl_at = fm_fl('fm_fl_at', '', '認証が発生しました。', '認証のために入力したパスワードが正しいかどうかご確認ください。');
$fm_fl_at->Button('bt_fl_at_bk', '認証に戻る', 'button', 'chevron-circle-left');

$fm_at = fm_at('fm_at', $getdata['USERNAME']);

//1: エージェント選択画面（TP）
$fm_pg = new form_generator('fm_pg');
$fm_pg->Button('bt_ag_bk', '設定一覧へ', 'button', 'list');
$fm_pg->SubTitle('エージェント作成・編集・削除', '作成は「作成」ボタンを、編集・削除はエージェントのラジオボタンを選択してからボタンを押します。', 'server');
$fm_pg->Caption('[SELECT]');
$fm_pg->Button('bt_ag_cr', '作成', 'button', 'plus-square');
$fm_pg->Button('bt_ag_ed', '編集', 'button', 'edit', 'dark', 'disabled');
$fm_pg->Button('bt_ag_dl', '削除', 'button', 'trash-alt', 'dark', 'disabled');

//2: エージェント作成（入力）
$fm_ag_cr = new form_generator('fm_ag_cr');
$fm_ag_cr->Button('bt_cr_bk', 'エージェント選択へ戻る', 'button', 'chevron-circle-left');
$fm_ag_cr->SubTitle('エージェント作成', '以下の情報を入力してください', 'plus-circle', false, '1: 情報入力');
$fm_ag_cr->Input('in_ag_hs', 'エージェントホスト', $agenthost_text, 'laptop-code', true);
$fm_ag_cr->Input('in_ag_cm', 'コミュニティ名', $community_text, 'users', true);
$fm_ag_cr->FormTitle('監視対象MIB', 'object-group');
$fm_ag_cr->openList();
$fm_ag_cr->addList('選択されるものは、MIBサブツリーです。');
$fm_ag_cr->addList('監視対象MIBを設定することで、ANALYで監視するMIBの数を制御することができます。');
$fm_ag_cr->addList('「MIB設定」にて自分のカスタマイズしたMIB情報を付加させることもできます。');
$fm_ag_cr->addList('なお、WARNにおける監視対象MIBのMIB-2は、作成時に自動的に導入されます。');
$fm_ag_cr->closeList();
$fm_ag_cr->Caption('[MIB]');
$fm_ag_cr->Button('bt_sb', '次へ', 'submit', 'sign-in-alt');

//3: アカウント編集（項目選択画面）
$fm_ag_ed = new form_generator('fm_ag_ed');
$fm_ag_ed->Button('bt_ed_bk', 'エージェント選択画面に戻る', 'button', 'chevron-circle-left');
$fm_ag_ed->SubTitle('[HOST]', '以下から変更したい項目を選択してください。', 'edit', false, '[COM]');
$fm_ag_ed->Button('bt_ed_hs', 'エージェントホスト', 'button', 'laptop-code');
$fm_ag_ed->Button('bt_ed_cm', 'コミュニティ名', 'button', 'users');
$fm_ag_ed->Button('bt_ed_mb', '監視対象MIB', 'button', 'object-group');

//3-1: アカウント編集（ユーザID）
$fm_ed_hs = new form_generator('fm_ed_hs');
$fm_ed_hs->Button('bt_ed_el_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
$fm_ed_hs->SubTitle('エージェント編集（エージェントホスト）', '以下の情報をもとに変更を行います。', 'edit');
$fm_ed_hs->openList();
$fm_ed_hs->addList('変更対象のエージェント: [AGENT_INFO]');
$fm_ed_hs->addList('現在: [HOST]');
$fm_ed_hs->closeList();
$fm_ed_hs->Input('in_ag_hs', 'エージェントホスト', $agenthost_text, 'laptop-code', true);
$fm_ed_hs->Button('bt_sb', '次へ', 'submit', 'sign-in-alt');

//3-2: アカウント編集（コミュニティ名）
$fm_ed_cm = new form_generator('fm_ed_cm');
$fm_ed_cm->Button('bt_ed_el_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
$fm_ed_cm->SubTitle('エージェント編集（コミュニティ名）', '以下の情報をもとに変更を行います。', 'edit');
$fm_ed_cm->openList();
$fm_ed_cm->addList('変更対象のエージェント: [AGENT_INFO]');
$fm_ed_cm->closeList();
$fm_ed_cm->Input('in_ag_cm', 'コミュニティ名', $community_text, 'user-circle', true);
$fm_ed_cm->Button('bt_sb', '次へ', 'submit', 'sign-in-alt');

//3-3: アカウント編集（監視対象MIB）
$fm_ed_mb = new form_generator('fm_ed_mb');
$fm_ed_mb->Button('bt_ed_el_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
$fm_ed_mb->SubTitle('エージェント編集（監視対象MIB）', 'MIBは、以下のチェックボックスから1件以上選択します。', 'edit');
$fm_ed_mb->openList();
$fm_ed_mb->addList('変更対象のエージェント: [AGENT_INFO]');
$fm_ed_mb->closeList();
$fm_ed_mb->Caption('[MIB_AGENT]');
$fm_ed_mb->Button('bt_sb', '次へ', 'submit', 'sign-in-alt');

//4-1: アカウント削除
$fm_ag_dl = new form_generator('fm_ag_dl');
$fm_ag_dl->Button('bt_dl_bk', 'エージェント選択画面に戻る', 'button', 'chevron-circle-left');
$fm_ag_dl->SubTitle('エージェント削除', '以下のエージェントを削除します。', 'trash-alt');
$fm_ag_dl->openList();
$fm_ag_dl->addList('削除対象のエージェント: [AGENT_INFO]');
$fm_ag_dl->closeList();
$fm_ag_dl->Button('bt_dl_sb', '次へ', 'button', 'sign-in-alt');

//5: 更新確認画面
$fm_cf = new form_generator('fm_cf');
$fm_cf->SubTitle('入力確認', '入力事項が正しければ「更新する」を押してください。<br>（※）「キャンセル」の場合、エージェント選択画面に遷移します。', 'user-check');
$fm_cf->Caption('[DATA]');
$fm_cf->Button('bt_cf_sb', '更新する', 'button', 'sign-in-alt');
$fm_cf->Button('bt_cf_bk', 'キャンセル', 'button', 'chevron-circle-left');

//6: 更新成功画面
$fm_cs = new form_generator('fm_cs');
$fm_cs->SubTitle('更新に成功しました！', 'ボタンを押して変更が反映したか確認しましょう！', 'check-square');
$fm_cs->Button('bt_cs_bk', 'エージェント選択画面に戻る', 'button', 'chevron-circle-left');
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - AGENT', true) ?>
	<?php echo form_generator::ExportClass() ?>
	<script type="text/javascript">
	    var a_data = [];
	    var f_id = new functionID();
	    var w_page = '';
	    let focustag;
	</script>
    </head>
     <body class="text-monospace">
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
	<?php echo $loader->load_Logo() ?>

	<?php echo $loader->Title('OPTION - AGENT', 'user') ?>
        <div id="data_output"></div>

	<?php echo $loader->footer() ?>
	<?php echo $loader->footerS(true) ?>

        <script type="text/javascript">

	    function edit_preagentid(data) {
		a_data['pre_agentid'] = data;
	    }

	    /**
	     * [GET] エージェント情報書き換え
	     * 
	     * 作成・編集・削除時の情報をページ上で書き換えます。
	     * 
	     * @param {string} page HTMLコードで書かれた文字列
	     * @param {int[]} types (0..[HOST], 1..[COM], 2..[MIB], 3..[SELECT], 4..[AGENT_INFO])
	     * @returns {res|String} 変換したHTMLコードを返します
	     */
	    function getAgentData(page, types) {
		var res = page;
		for (var i = 0; i < types.length; i++) {
		    switch (types[i]) {
			case 0: //AgentHost
			    res = res.replaceAll('[HOST]', a_data['DATA'][a_data['pre_agentid']]['AGENTHOST']);
			    break;
			case 1: //Community
			    res = res.replaceAll('[COM]', a_data['DATA'][a_data['pre_agentid']]['COMMUNITY']);
			    break;
			case 2: //MIB
			    res = res.replaceAll('[MIB]', a_data['MIB']);
			    break;
			case 3: //AgentSelect
			    res = res.replaceAll('[SELECT]', a_data['SELECT']);
			    break;
			case 4: //AgentInfo
			    res = res.replaceAll('[AGENT_INFO]', '【' + a_data['DATA'][a_data['pre_agentid']]['COMMUNITY'] + '】' + a_data['DATA'][a_data['pre_agentid']]['AGENTHOST']);
			    break;
			case 5: //MIB (Agent)
			    res = res.replaceAll('[MIB_AGENT]', a_data['MIB_AGENT'][a_data['pre_agentid']]);
			    break;
		    }
		}
		return res;
	    }

	    function page_generate(page) {
		var data;
		if (typeof a_data['pre_agentid'] !== 'undefined') {
		    data = getAgentData(page, [0, 1, 3, 4, 5]);
		} else {
		    data = getAgentData(page, [2, 3]);
		}
		animation('data_output', 400, data);
	    }

	    function sel_generate(duration) {
		f_id.resetID();
		w_page = '';
		animation('data_output', duration, fm_ld);
		ajax_dynamic_post_toget('../scripts/agent/agent_get.php').then(function (data) {
		    switch (data['CODE']) {
			case 0:
			    a_data = data;
			    page_generate(fm_pg);
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
		ajax_dynamic_post('../scripts/agent/agent_set.php', data).then(function (data) {
		    switch (data['CODE']) {
			case 0:
			    animation('data_output', 400, fm_cs);
			    break;
			case 1:
			    var fm_w = fm_fl.replace('[DATA]', data['ERR_TEXT']);
			    animation('data_output', 400, fm_w);
			    break;
			case 2:
			    animation('data_output', 400, fm_fl_ab);
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
			    if (typeof a_data['pre_agentid'] !== 'undefined') {
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
		sel_generate(0);
	    });

	    //1: エージェント選択画面（戻る・作成・編集・削除ボタン押下）
	    $(document).on('click', '#bt_ag_bk, #bt_ag_cr, #bt_ag_ed, #bt_ag_dl', function () {
		switch ($(this).attr('id')) {
		    case "bt_ag_bk":
			animation_to_sites('data_output', 400, './');
			break;
		    case "bt_ag_cr":
			f_id.change_agent_create();
			page_generate(fm_ag_cr);
			break;
		    case "bt_ag_ed":
			edit_preagentid($('input[name="sl_ag"]:checked').val());
			page_generate(fm_ag_ed);
			break;
		    case "bt_ag_dl":
			f_id.change_agent_delete();
			edit_preagentid($('input[name="sl_ag"]:checked').val());
			page_generate(fm_ag_dl);
			break;
		}
	    });

	    //戻るボタン（作成・編集・削除から）
	    $(document).on('click', '#bt_cr_bk, #bt_ed_bk, #bt_dl_bk, #bt_fl_us_bk, #bt_cf_bk, #bt_at_bk', function () {
		sel_generate(400);
	    });

	    //バリデーション（チェックボックスチェック）
	    $(document).on('click', '#fm_ag_cr, #fm_ed_mb', function () {
		animation_form('err_mb', 200, '');
		$('input[name="sl_mb[]"]').each(function () {
		    if ($(':focus').attr("type") === "submit") {
			requiredsum = $('input[name="sl_mb[]"]:required').length;
			if (requiredsum > 0) {
			    animation_form('err_mb', 0, '<div class="err_text">【！】最低でも1つ以上は選択してください</div>');
			}
		    }
		});
	    });

	    //フォームデータ送信時
	    $(document).on('submit', '#fm_ag_cr, #fm_ed_hs, #fm_ed_cm, #fm_ed_mb, #fm_ag_dl', function (event) {
		event.preventDefault();
		w_page = document.getElementById('data_output').innerHTML.toString();
		var data = $(this).serializeArray();
		if ($(this).attr('id').toString().indexOf('fm_ed') === 0) {
		    data.push({name: "pre_agentid", value: a_data['pre_agentid']});
		}
		a_data['data_presub'] = data;
		data_function(data);
	    });

	    $(document).on('click', '#bt_dl_sb', function () {
		var data = [{name: "pre_agentid", value: a_data['pre_agentid']}];
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
	    $(document).on('click', '#bt_ed_hs, #bt_ed_cm, #bt_ed_mb, #bt_ed_bk', function () {
		switch ($(this).attr('id')) {
		    case "bt_ed_hs":
			f_id.change_agent_edit_host();
			page_generate(fm_ed_hs);
			break;
		    case "bt_ed_cm":
			f_id.change_agent_edit_community();
			page_generate(fm_ed_cm);
			break;
		    case "bt_ed_mb":
			f_id.change_agent_edit_mib();
			page_generate(fm_ed_mb);
			break;
		    case "bt_ed_bk":
			sel_generate(400);
			break;
		}
	    });

	    //編集・各画面からの戻るボタン
	    $(document).on('click', '#bt_ed_el_bk', function () {
		page_generate(fm_ag_ed);
	    });

	    $(document).on('click', '#bt_fl_at_bk', function () {
		animation('data_output', 400, fm_at);
	    });

	    $(document).on('click', '#bt_fl_rt, #bt_cs_bk, #bt_fl_in_bk', function () {
		switch ($(this).attr('id')) {
		    case 'bt_fl_rt':
		    case 'bt_cs_bk':
			animation_to_sites('data_output', 400, './agent.php');
			break;
		    case 'bt_fl_in_bk':
			animation('data_output', 400, w_page);
			break;
		}
	    });

	    $(document).on('change', 'input[name="sl_ag"]', function () {
		var count = $('#fm_pg input:radio:checked').length;
		if (count === 1) {
		    $('#bt_ag_ed, #bt_ag_dl').prop('disabled', false);
		} else {
		    $('#bt_ag_ed, #bt_ag_dl').prop('disabled', true);
		}
	    });

	    $(document).on('change', 'input[name="sl_mb[]"], input[name="sl_ab"]', function () {
		if($(this).attr('name') === 'sl_ab') {
		    if($(this).prop("checked")) {
			$('input[name="sl_mb[]"]').prop("checked", true);
		    } else {
			$('input[name="sl_mb[]"]').prop("checked", false);
		    }
		}
		if($('input[name="sl_mb[]"]').not(':checked').length !== 0) {
		    $('#mb_ck_0').prop("checked", false);
		}
		if ($('input[name="sl_mb[]"]:checked').length > 0) {
		    $('input[name="sl_mb[]"]').prop("required", false);
		} else {
		    $('input[name="sl_mb[]"]').prop("required", true);
		}
	    });
        </script>
    </body>
</html>