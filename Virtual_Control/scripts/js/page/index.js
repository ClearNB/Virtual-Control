/* global ajax_dynamic_post, fm_ld */

function page_get() {
    var fdata = [];
    fdata.push({'name': 'f_id', 'value': get_funid()});
    animation('data_output', 0, fm_ld);
    ajax_dynamic_post('/scripts/links/links_get.php', fdata).then(function (data) {
        animation('data_output', 400, data['PAGE']);
    });
}

$(document).ready(function () {
    change_index();
    page_get();
});

$(document).on('click', '#bt_go_gh, #bt_go_lg, #bt_go_rf', function () {
    switch ($(this).attr('id')) {
        case "bt_go_gh":
            animation_to_sites('data_output', 400, 'https://github.com/ClearNB/Virtual-Control');
            break;
        case "bt_go_lg":
            animation_to_sites('data_output', 400, './login');
            break;
        case "bt_go_rf":
            animation_to_sites('data_output', 400, './init');
            break;
    }
});