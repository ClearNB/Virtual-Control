/* global fm_ld, ajax_dynamic_post */

var r_data = '';

function download_csv() {
    post('/download/', {'download-data': r_data, 'file-name': 'csvtrapdata-[DATE].csv'});
}

function page_get(dist = 400, fdata = '') {
    if (fdata === '') {
        fdata = [];
    }
    fdata.push({'name': 'f_id', 'value': getFunctionID()});
    animation('data_output', dist, fm_ld);
    ajax_dynamic_post('/scripts/warn/warn_get.php', fdata).then(function (data) {
        if (getFunctionID() === 81) {
            r_data = data['CSV'];
        }
        animation('data_output', 400, data['PAGE']);
    });
}

$(document).ready(function () {
    change_warn_result();
    page_get(0);
});

$(document).on('click', '#bt_fl_rt', function () {
    animation_to_sites('data_output', 400, '/');
});

$(document).on('click', '#bt_rs_rt, #bt_rs_bk, #bt_rs_dl', function () {
    switch ($(this).attr('id')) {
        case "bt_rs_rt":
            change_warn_result();
            page_get();
            break;
        case "bt_rs_bk":
            animation_to_sites('data_output', 400, '/');
            break;
        case "bt_rs_dl":
            download_csv();
            break;
    }
});

$(document).on('click', 'div[id^="sub_i"]', function () {
    var fdata = [];
    fdata.push({'name': 'sub', 'value': $(this).attr('id')});
    change_warn_sub();
    page_get(400, fdata);
});

$(document).on('click', '#bt_sb_bk', function () {
    change_warn_back();
    page_get();
});