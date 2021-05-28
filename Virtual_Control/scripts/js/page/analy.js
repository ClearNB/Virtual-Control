/* global fm_ld, ajax_dynamic_post_toget, ajax_dynamic_post, ANALY_WALK */

function download_csv() {
    post('/download/', {'f_id': get_funid()});
}

function snmp_page_get(duration, type, d) {
    animation('data_output', duration, fm_ld);
    if (type !== '' && d !== '') {
	if (type === 0) {
	    var f = {'f_id': get_funid(), 'sl_agt': d};
	} else if (type === 1) {
	    var f = {'f_id': get_funid(), 'sl_sub': d};
	}
    } else {
	var f = {'f_id': get_funid()};
    }
    ajax_dynamic_post('/scripts/analy/analy.php', f).then(function (data) {
	animation('data_output', 400, data['PAGE']);
    });
}

$(document).ready(function () {
    change_analy_select();
    snmp_page_get(0, '', '');
});

$(document).on('click', '#bt_sl_bk, #bt_sl_st', function () {
    switch ($(this).attr('id')) {
	case 'bt_sl_bk':
	    animation_to_sites('data_output', 400, '/');
	    break;
	case 'bt_sl_st':
	    animation_to_sites('data_output', 400, '/option/agent');
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
});