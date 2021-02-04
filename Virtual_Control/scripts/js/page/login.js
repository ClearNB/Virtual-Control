/* global fm_ld, ajax_dynamic_post_toget, ajax_dynamic_post, ANALY_WALK */

function page_get(duration, fdata, iswait) {
    if (fdata === '') {
        fdata = [];
    }
    fdata.push({'name': 'fun_id', 'value': getFunctionID()});
    if (iswait) {
        animation('data_output', duration, fm_ld);
    } else {
        $('button, input').attr('disabled', true);
    }
    ajax_dynamic_post('/scripts/login/login_get.php', fdata).then(function (data) {
        switch(data['CODE']) {
            case 3: animation_to_sites('data_output', 400, '/dash'); break;
            case 2: case 1: animation('fm_warn', 400, data['PAGE']); break;
            default: animation('data_output', 400, data['PAGE']);
        }
        if(!iswait) {
            $('button, input').attr('disabled', false);
        }
    });
}

$(document).ready(function () {
    change_login();
    page_get(0, '', true);
});

$(document).on('submit', '#fm_pg', function (event) {
    event.preventDefault();
    var fdata = $(this).serializeArray();
    change_login_sub();
    page_get(400, fdata, false);
});

$(document).on('click', '#bt_ft_bk', function () {
    change_login();
    page_get(400, '', true);
});

