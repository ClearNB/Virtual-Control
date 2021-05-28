/* global fm_ld, ajax_dynamic_post */

function page_get() {
    var fdata = [];
    fdata.push({'name': 'f_id', 'value': get_funid()});
    animation('data_output', 0, fm_ld);
    ajax_dynamic_post('/scripts/links/links_get.php', fdata).then(function (data) {
        animation('data_output', 400, data['PAGE']);
    });
}

$(document).ready(function () {
    change_option();
    page_get();
});

$(document).on('click', '#bt_pg_bk, #account, #mib, #agent', function () {
    switch ($(this).attr('id')) {
        case 'bt_pg_bk':
            animation_to_sites('data_output', 400, '/option');
            break;
        case 'account':
            animation_to_sites('data_output', 400, '/option/account');
            break;
        case 'mib':
            animation_to_sites('data_output', 400, '/option/mib');
            break;
        case 'agent':
            animation_to_sites('data_output', 400, '/option/agent');
            break;
    }
});