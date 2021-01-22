/* global MIB_GROUP_SELECT, MIB_GROUP_SELECT_INIT, fm_ld, ajax_dynamic_post, fm_fl, MIB_SUB_SELECT, OPTION_BACK */

var r_data = 0;
var f_id = new functionID();

function mib_data_get(duration) {
    if (f_id.getFunctionIDRow === OPTION_BACK) {
	animation_to_sites('data_output', 400, '../');
    } else {
	if (f_id.getFunctionIDRow === MIB_GROUP_SELECT || f_id.getFunctionIDRow === MIB_GROUP_SELECT_INIT || f_id.getFunctionIDRow === MIB_SUB_SELECT) {
	    r_data = 0;
	}
	var fdata = {"request_id": f_id.getFunctionIDRow, "request_data_id": r_data};
	animation('data_output', duration, fm_ld);
	ajax_dynamic_post('/scripts/mib/mib_get.php', fdata).then(function (data) {
	    switch (data['CODE']) {
		case 0:
		    animation('data_output', 400, data['PAGE']);
		    break;
		case 1:
		    animation('data_output', 400, data['PAGE']);
		    break;
	    }
	});
    }
}

$(document).ready(function () {
    f_id.change_mib_group_select_init();
    mib_data_get(0);
});

//1: MIB_GROUP_SELECT
$(document).on('click', 'button[id^="bt_po_bk"]', function () {
    switch ($(this).attr('id')) {
	case 'bt_po_bk_gr':
	    f_id.change_mib_group_select();
	    break;
	case 'bt_po_bk_sb':
	    f_id.change_mib_sub_select();
	    break;
    }
    mib_data_get(400);
});

//グループ・サブツリー選択（ボタンイベント）
$(document).on('click', 'button[id^="bt_sl"]', function () {
    var id = $(this).attr('id');
    if (new RegExp("(cr)$").test(id)) {
	switch (id) {
	    case 'bt_sl_gr_cr':
		f_id.change_mib_group_create();
		break;
	    case 'bt_sl_sb_cr':
		f_id.change_mib_sub_create();
		break;
	}
    } else if (new RegExp("(bk)$").test(id)) {
	switch (id) {
	    case 'bt_sl_gr_bk':
		f_id.change_option_back();
		break;
	    case 'bt_sl_sb_bk':
		f_id.change_mib_group_select();
		break;
	}
    } else if (new RegExp("(go|ed|dl)$").test(id)) {
	if ($('input[name="request_data_id"]:checked').length === 1) {
	    r_data = $('input[name="request_data_id"]:checked').val();
	    switch (id) {
		case 'bt_sl_gr_go':
		    f_id.change_mib_sub_select_init();
		    break;
		case 'bt_sl_gr_ed':
		    f_id.change_mib_group_edit();
		    break;
		case 'bt_sl_gr_dl':
		    f_id.change_mib_group_delete();
		    break;
		case 'bt_sl_sb_go':
		    f_id.change_mib_node_edit_init();
		    break;
		case 'bt_sl_sb_ed':
		    f_id.change_mib_sub_edit();
		    break;
		case 'bt_sl_sb_dl':
		    f_id.change_mib_sub_delete();
		    break;
	    }
	} else {
	    r_data = 999;
	}
    } else {
	r_data = 999;
    }
    mib_data_get(400);
});

//編集（OID・名前系・戻る）
$(document).on('click', 'button[id^="bt_ed"]', function () {
    switch ($(this).attr('id')) {
	case 'bt_ed_bk_gr':
	    f_id.change_mib_group_edit();
	    break;
	case 'bt_ed_gr_id':
	    f_id.change_mib_group_edit_oid();
	    break;
	case 'bt_ed_gr_nm':
	    f_id.change_mib_group_edit_name();
	    break;

	case 'bt_ed_bk_sb':
	    f_id.change_mib_sub_edit();
	    break;
	case 'bt_ed_sb_id':
	    f_id.change_mib_sub_edit_oid();
	    break;
	case 'bt_ed_sb_nm':
	    f_id.change_mib_sub_edit_name();
	    break;
    }
    mib_data_get(400);
});

$(document).on('click', 'bt_fl_rt', function () {
    animation_to_sites('data_output', 400, './');
});

$(document).on('change', 'input[name="request_data_id"]', function () {
    $('button[id$="_ed"], button[id$="_dl"], button[id$="_go"]').attr('disabled', (($('input[name="request_data_id"]:checked').length === 1) ? false : true));
});