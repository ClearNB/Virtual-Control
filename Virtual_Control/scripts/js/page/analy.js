/* global fm_ld, ajax_dynamic_post_toget, ajax_dynamic_post, ANALY_WALK */

var r_data = '';

function download_csv() {
    post('/download/', {'download-data': r_data, 'file-name': 'csvdata-[DATE].csv'});
}

function snmp_page_get(duration, type, d) {
    animation('data_output', duration, fm_ld);
    if (type !== '' && d !== '') {
	if (type === 0) {
	    var f = {'f_id': getFunctionID(), 'sl_agt': d};
	} else if (type === 1) {
	    var f = {'f_id': getFunctionID(), 'sl_sub': d};
	}
    } else {
	var f = {'f_id': getFunctionID()};
    }
    ajax_dynamic_post('/scripts/analy/analy_get.php', f).then(function (data) {
	if (getFunctionID() === 52) {
	    r_data = data['CSV'];
	}
	animation('data_output', 400, data['PAGE']);
    });
}

$(document).ready(function () {
    change_analy_get_agent();
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
    change_analy_get_sub();
    snmp_page_get(400, 1, $(this).attr('id'));
});

$(document).on('click', '#bt_rt_dl', function () {
    download_csv();
});

$(document).on('click', '#bt_rt_bk', function () {
    change_analy_get_agent();
    snmp_page_get(400, '', '');
});

$(document).on('click', '#bt_sb_bk', function () {
    change_analy_back_result();
    snmp_page_get(400, '', '');
});

$(document).on('click', '#bt_fl_rt', function () {
    animation_to_sites('data_output', 400, '/analy');
});

$(document).on('click', '#bt_rt_rf', function () {
    change_analy_walk_refresh();
    snmp_page_get(400, '', '');
});

$(document).on('change', 'input[name="sl_ag"]', function () {
    $('button[id="bt_sl_sb"]').attr('disabled', (($('input[name="sl_ag"]:checked').length === 1) ? false : true));
});