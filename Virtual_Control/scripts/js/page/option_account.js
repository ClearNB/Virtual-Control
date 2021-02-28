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
    ajax_dynamic_post('/scripts/account/account.php', data).then(function (data) {
	if (data['CODE'] === 2) {
	    animation('fm_warn', 400, data['PAGE']);
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
    change_account_sel();
    get_page('', 0, 0);
});

//1: アカウント選択画面（戻る・作成・編集・削除ボタン押下）
$(document).on('click', '#bt_ac_bk, #bt_ac_cr, #bt_ac_ed, #bt_ac_dl', function () {
    var data = [];
    switch ($(this).attr('id')) {
	case "bt_ac_bk":
	    animation_to_sites('data_output', 400, '/option');
	    break;
	case "bt_ac_cr":
	    change_account_create();
	    get_page('', 0);
	    break;
	case "bt_ac_ed":
	    change_account_edit_sel();
	    data.push({name: 'p_id', value: $('input[name="p_id"]:checked').val()});
	    get_page(data, 0);
	    break;
	case "bt_ac_dl":
	    change_account_del();
	    data.push({name: 'p_id', value: $('input[name="p_id"]:checked').val()});
	    get_page(data, 0);
	    break;
    }
});

//ユーザ選択画面に戻る
$(document).on('click', '#bt_cs_bk, #bt_cr_bk, #bt_ed_bk, #bt_dl_bk, #bt_fl_us_bk, #bt_cf_bk, #bt_at_bk, #bt_fl_bk', function () {
    change_account_sel();
    get_page('', 0);
});

//フォームデータ送信時
$(document).on('submit', '#fm_pg', function (event) {
    event.preventDefault();
    var data = $(this).serializeArray();
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
$(document).on('click', '#bt_ed_id, #bt_ed_nm, #bt_ed_ps, #bt_ed_bk', function () {
    switch ($(this).attr('id')) {
	case "bt_ed_id":
	    change_account_edit_id();
	    get_page('');
	    break;
	case "bt_ed_nm":
	    change_account_edit_name();
	    get_page('');
	    break;
	case "bt_ed_ps":
	    change_account_edit_pass();
	    get_page('');
	    break;
	case "bt_ed_bk":
	    change_account_sel();
	    get_page('');
	    break;
    }
});

//編集・各画面からの戻るボタン
$(document).on('click', '#bt_id_bk, #bt_nm_bk, #bt_ps_bk', function () {
    change_account_edit_sel();
    get_page('');
});

$(document).on('click', '#bt_fl_at_bk, #bt_fl_in_bk', function () {
    get_page('');
});

$(document).on('click', '#bt_fl_rt', function () {
    animation_to_sites('data_output', 400, '/option/account');
});

$(document).on('change', 'input[name="p_id"]', function () {
    $('#bt_ac_ed, #bt_ac_dl').attr('disabled', ($('input[name="p_id"]:checked').length !== 1));
});