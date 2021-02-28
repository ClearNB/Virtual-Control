/* global ajax_dynamic_post, fm_ld */

function page_get(duration = 500) {
    var fdata = [];
    fdata.push({'name': 'f_id', 'value': get_funid()});
    animation('data_output', duration, fm_ld);
    ajax_dynamic_post('/scripts/init/init.php', fdata).then(function (data) {
        animation('data_output', 400, data['PAGE']);
    });
}

$(document).ready(function () {
    change_init();
    page_get(0);
});

$(document).on('click', '#bt_pg_st', function () {
    change_init_post();
    page_get();
});

$(document).on('click', '#bt_fl_rt, #bt_sc_ln, #bt_pg_bk', function () {
    animation_to_sites('data_output', 400, '/');
});