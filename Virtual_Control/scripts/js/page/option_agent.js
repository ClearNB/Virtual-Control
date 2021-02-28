/* global fm_ld, ajax_dynamic_post */

/**
 * [Function] ページ遷移処理
 * 
 * @param {null|string|array} data フォームデータ
 * @param {int} type 取得タイプ（0..ページ取得, 1..ポスト）
 * @param {int} duration 待機画面への遷移時間【Default: 400】
 * @param {bool} iswait 待機画面を作成するかどうかを指定します（作成しない場合は、ボタンのロックを行います）【Default: false】
 * @returns {void} 引数の設定により要求されたページを返し、それにより画面遷移を行います
 */
function get_page(data, type = 0, duration = 400, iswait = true) {
    if (data === '') {
	data = [];
    }
    data.push({name: 'f_id', value: get_funid()});
    data.push({name: 'd_tp', value: type});
    if (iswait) {
	animation('data_output', duration, fm_ld);
    } else {
	$('button').attr('disabled', true);
    }
    ajax_dynamic_post('/scripts/agent/agent_get.php', data).then(function (data) {
	if (data['CODE'] === 2) {
	    animation(data['ID'], 400, data['PAGE']);
	} else {
	    animation('data_output', 400, data['PAGE']);
	}
	if (!iswait) {
	    $('button').attr('disabled', false);
	}
    });
}

//読み込み時
$(document).ready(function () {
    change_agent_sel();
    get_page('', 0, 0);
});

//1: アカウント選択画面（戻る・作成・編集・削除ボタン押下）
$(document).on('click', '#bt_ag_bk, #bt_ag_cr, #bt_ag_ed, #bt_ag_dl', function () {
    var data = [];
    switch ($(this).attr('id')) {
	case "bt_ag_bk":
	    animation_to_sites('data_output', 400, '/option');
	    break;
	case "bt_ag_cr":
	    change_agent_create();
	    get_page('', 0);
	    break;
	case "bt_ag_ed":
	    change_agent_edit_sel();
	    data.push({name: 'p_id', value: $('input[name="sl_ag"]:checked').val()});
	    get_page(data, 0);
	    break;
	case "bt_ag_dl":
	    change_agent_del();
	    data.push({name: 'p_id', value: $('input[name="sl_ag"]:checked').val()});
	    get_page(data, 0);
	    break;
    }
});

//ユーザ選択画面に戻る
$(document).on('click', '#bt_cs_bk, #bt_cr_bk, #bt_ed_bk, #bt_dl_bk, #bt_cf_bk, #bt_at_bk, #bt_fl_bk', function () {
    change_agent_select();
    get_page('', 0);
});

//フォームデータ送信時
$(document).on('submit', '#fm_pg', function (event) {
    event.preventDefault();
    var data = $(this).not('input:checkbox:not(:checked)').serializeArray();
    get_page(data, 1, 400, false);
});

$(document).on('click', '#bt_dl_sb', function () {
    get_page('', 1);
});

//認証データ送信時
$(document).on('submit', '#fm_at', function (event) {
    event.preventDefault();
    var data = $(this).serializeArray();
    get_page(data, 1, 400, false);
});

//確認終了後
$(document).on('click', '#bt_cf_sb', function () {
    get_page('', 1);
});

//2: アカウント編集（各画面遷移）
$(document).on('click', '#bt_ed_hs, #bt_ed_cm, #bt_ed_oi, #bt_ed_bk', function () {
    switch ($(this).attr('id')) {
	case "bt_ed_hs":
	    change_agent_edit_hs();
	    get_page('');
	    break;
	case "bt_ed_cm":
	    change_agent_edit_cm();
	    get_page('');
	    break;
	case "bt_ed_oi":
	    change_agent_edit_mb();
	    get_page('');
	    break;
	case "bt_ed_bk":
	    change_agent_sel();
	    get_page('');
	    break;
    }
});

//編集・各画面からの戻るボタン
$(document).on('click', '#bt_hs_bk, #bt_cm_bk, #bt_oi_bk', function () {
    change_agent_edit_sel();
    get_page('');
});

$(document).on('click', '#bt_fl_at_bk, #bt_fl_in_bk', function () {
    get_page('');
});

$(document).on('click', '#bt_fl_rt', function () {
    animation_to_sites('data_output', 400, '/option/agent');
});

$(document).on('change', 'input[name="sl_ag"]', function () {
    $('#bt_ag_ed, #bt_ag_dl').attr('disabled', ($('input[name="sl_ag"]:checked').length !== 1));
});

$(document).on('change', 'input[name="sl_mb[]"], input[name="sl_ab"]', function () {
    if ($(this).attr('name') === 'sl_ab') {
	$('input[name="sl_mb[]"]').prop('checked', ($(this).prop('checked') === true));
    }
    $('input[name="sl_ab"]').prop('checked', ($('input[name="sl_mb[]"]').not(':checked').length === 0));
    $('input[name="sl_mb[]"]').prop('required', $('input[name="sl_mb[]"]:checked').length === 0);
});