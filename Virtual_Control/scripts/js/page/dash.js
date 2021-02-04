/* global ajax_dynamic_post, fm_ld */

function page_get() {
    var fdata = [];
    fdata.push({'name': 'f_id', 'value': getFunctionID()});
    animation('data_output', 0, fm_ld);
    ajax_dynamic_post('/scripts/links/links_get.php', fdata).then(function (data) {
        animation('data_output', 400, data['PAGE']);
    });
}

$(document).ready(function () {
    change_dash();
    page_get();
});

$(document).on('click', '#analy, #warn, #option', function () {
    switch ($(this).attr('id')) {
        case "analy":
            animation_to_sites("data_output", 400, "/analy");
            break;
        case "warn":
            animation_to_sites("data_output", 400, "/warn");
            break;
        case "option":
            animation_to_sites("data_output", 400, "/option");
            break;
    }
});