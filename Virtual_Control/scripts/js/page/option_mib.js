/* global MIB_GROUP_SELECT, MIB_GROUP_SELECT_INIT, fm_ld, ajax_dynamic_post, fm_fl, MIB_SUB_SELECT, OPTION_BACK */

var r_data = 0;

function mib_data_get(fdata = '', duration = 400, iswait = true) {
    if (getFunctionID() === 0) {
        animation_to_sites('data_output', 400, '../');
    } else {
        if (getFunctionID() === 20 || getFunctionID() === 21) {
            r_data = 0;
        }
        if (fdata === '') {
            fdata = [];
        }
        fdata.push({'name': 'request_id', 'value': getFunctionID()});
        fdata.push({'name': 'request_data_id', 'value': r_data});
        if (iswait) {
            animation('data_output', duration, fm_ld);
        } else {
            $('button').attr('disabled', true);
        }
        ajax_dynamic_post('/scripts/mib/mib_get.php', fdata).then(function (data) {
            var target = '';
            switch (data['CODE']) {
                case 1:
                    target = 'fm_nd_ed';
                    break;
                case 2:
                    target = 'fm_warn';
                    break;
                default:
                    target = 'data_output';
            }
            if (!iswait) {
                $('button').attr('disabled', false);
            }
            if (r_data === 999) {
                r_data = 0;
            }
            animation(target, 400, data['PAGE']);
        });
        if (getFunctionID() % 10 === 0) {
            r_data = 0;
        }
}
}

$(document).ready(function () {
    change_mib_group_select_init();
    mib_data_get('', 0);
});

//1: MIB_GROUP_SELECT
$(document).on('click', 'button[id^="bt_po_bk"]', function () {
    switch ($(this).attr('id')) {
        case 'bt_po_bk_gr':
            change_mib_group_select();
            break;
        case 'bt_po_bk_sb':
            change_mib_sub_select();
            break;
        case 'bt_po_bk_nd':
            change_mib_node_edit();
            break;
    }
    mib_data_get();
});

//グループ・サブツリー選択（ボタンイベント）
$(document).on('click', 'button[id^="bt_sl"]', function () {
    var id = $(this).attr('id');
    if (new RegExp("(cr)$").test(id)) {
        switch (id) {
            case 'bt_sl_gr_cr':
                change_mib_group_create();
                break;
            case 'bt_sl_sb_cr':
                change_mib_sub_create();
                break;
            case 'bt_sl_nd_cr':
                change_mib_node_edit_form();
                break;
        }
    } else if (new RegExp("(bk)$").test(id)) {
        switch (id) {
            case 'bt_sl_gr_bk':
                change_option_back();
                break;
            case 'bt_sl_sb_bk':
                change_mib_group_select();
                break;
            case 'bt_sl_nd_bk':
                change_mib_sub_select();
                break;
        }
    } else if (new RegExp("(go|ed|dl)$").test(id)) {
        if ($('input[name="request_data_id"]:checked').length === 1) {
            r_data = $('input[name="request_data_id"]:checked').val();
            switch (id) {
                case 'bt_sl_gr_go':
                    change_mib_sub_select_init();
                    break;
                case 'bt_sl_gr_ed':
                    change_mib_group_edit();
                    break;
                case 'bt_sl_gr_dl':
                    change_mib_group_delete();
                    break;
                case 'bt_sl_sb_go':
                    change_mib_node_edit_init();
                    break;
                case 'bt_sl_sb_ed':
                    change_mib_sub_edit();
                    break;
                case 'bt_sl_sb_dl':
                    change_mib_sub_delete();
                    break;
            }
        }
    }
    mib_data_get();
});

//編集（OID・名前系・戻る）
$(document).on('click', 'button[id^="bt_ed"]', function () {
    switch ($(this).attr('id')) {
        case 'bt_ed_bk_gr':
            change_mib_group_edit();
            break;
        case 'bt_ed_gr_id':
            change_mib_group_edit_oid();
            break;
        case 'bt_ed_gr_nm':
            change_mib_group_edit_name();
            break;
        case 'bt_ed_bk_sb':
            change_mib_sub_edit();
            break;
        case 'bt_ed_sb_id':
            change_mib_sub_edit_oid();
            break;
        case 'bt_ed_sb_nm':
            change_mib_sub_edit_name();
            break;
    }
    mib_data_get();
});

$(document).on('click', '#bt_dl_nt', function () {
    mib_data_get();
});

$(document).on('click', '#bt_fl_rt', function () {
    animation_to_sites('data_output', 400, './');
});

$(document).on('submit', '#fm_pg', function (event) {
    event.preventDefault();
    var fdata = $(this).serializeArray();
    mib_data_get(fdata, 400, false);
});

$(document).on('click', '#bt_cf_sb, #bt_cf_bk, #bt_at_bk', function () {
    switch ($(this).attr('id')) {
        case 'bt_cf_sb':
            mib_data_get();
            break;
        case 'bt_cf_bk':
        case 'bt_at_bk':
            r_data = 999;
            mib_data_get();
            break;
    }
});

$(document).on('click', '#bt_cs_bk', function () {
    switch (parseInt(getFunctionID() / 10)) {
        case 2:
            change_mib_group_select_init();
            break;
        case 3:
            change_mib_sub_select();
            break;
        case 4:
            change_mib_node_edit();
            break;
    }
    mib_data_get();
});

$(document).on('change', 'input[name="request_data_id"]', function () {
    $('button[id$="_ed"], button[id$="_dl"], button[id$="_go"]').attr('disabled', (($('input[name="request_data_id"]:checked').length === 1) ? false : true));
});

//NODE
$(document).on('click', 'button[id^="nd-"]', function () {
    rdata = $(this).attr('id');
    change_mib_get_edit_select();
});