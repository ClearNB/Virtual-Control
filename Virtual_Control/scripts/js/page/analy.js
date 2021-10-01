/* global fm_ld, ajax_dynamic_post_toget, ajax_dynamic_post, ANALY_WALK */

function download_csv() {
    post('/download/', {'f_id': get_funid()});
}

function snmp_page_get(duration, type, d) {
    animation('data_output', duration, fm_ld);
    if (type !== '' && d !== '') {
        switch (type) {
            case 0:
                var f = {'f_id': get_funid(), 'sl_agt': d};
                break;
            case 1:
                var f = {'f_id': get_funid(), 'sl_sub': d};
                break;
            case 2:
                var f = {'f_id': get_funid(), 'sl_gt_ps': d};
                break;
        }
    } else {
        var f = {'f_id': get_funid()};
    }
    ajax_dynamic_post('/scripts/analy/analy.php', f).then(function (data) {
        switch(data['CODE']) {
            case 0:
                animation('data_output', 400, data['PAGE']);
                break;
            case 1:
                animation('log_output', 200, data['PAGE']);
                break;
        }
    });
}

$(document).ready(function () {
    change_analy_select();
    snmp_page_get(0, '', '');
});

$(document).on('click', '#bt_sl_bk, #bt_sl_st, #bt_sl_fi', function () {
    switch ($(this).attr('id')) {
        case 'bt_sl_bk':
            animation_to_sites('data_output', 400, '/');
            break;
        case 'bt_sl_st':
            animation_to_sites('data_output', 400, '/option/agent');
            break;
        case 'bt_sl_fi':
            change_analy_past_get();
            snmp_page_get(400, 0, $('input[name="sl_ag"]:checked').val());
            break;
    }
});

$(document).on('submit', '#fm_pg', function (event) {
    event.preventDefault();
    change_analy_walk();
    snmp_page_get(400, 0, $('input[name="sl_ag"]:checked').val());
});

$(document).on('click', 'div[id^="sub_i"]', function () {
    change_analy_sub();
    snmp_page_get(400, 1, $(this).attr('id'));
});

$(document).on('change', 'select[name="sl_gt_ps"]', function () {
    if ($('select[name="sl_gt_ps"] option:selected').val() !== '0') {
        change_analy_past();
        snmp_page_get(400, 2, $('select[name="sl_gt_ps"] option:selected').val());
    }
});

$(document).on('change', 'input[name="sl_ps"]', function () {
    $('button[id="bt_ps_gt"]').attr('disabled', ($('input[name="sl_ps"]:checked').length === 1) ? false : true);
});

$(document).on('click', '#bt_ps_bk, #bt_ps_gt', function () {
    switch ($(this).attr('id')) {
        case 'bt_ps_bk':
            change_analy_select();
            snmp_page_get(400, '', '');
            break;
        case 'bt_ps_gt':
            change_analy_past_s();
            snmp_page_get(400, 2, $('input[name="sl_ps"]:checked').val());
            break;
    }
});



$(document).on('click', '#bt_rt_dl', function () {
    change_analy_dl();
    download_csv();
});

$(document).on('click', '#bt_rt_bk', function () {
    change_analy_select();
    snmp_page_get(400, '', '');
});

$(document).on('click', '#bt_sb_bk', function () {
    change_analy_back();
    snmp_page_get(400, '', '');
});

$(document).on('click', '#bt_fl_rt', function () {
    animation_to_sites('data_output', 400, '/analy');
});

$(document).on('click', '#bt_rt_rf', function () {
    change_analy_ref();
    snmp_page_get(400, '', '');
});

$(document).on('change', 'input[name="sl_ag"]', function () {
    $('button[id="bt_sl_sb"]').attr('disabled', (($('input[name="sl_ag"]:checked').length === 1) ? false : true));
    $('button[id="bt_sl_fi"]').attr('disabled', (($('input[name="sl_ag"]:checked').length === 1) ? false : true));
});