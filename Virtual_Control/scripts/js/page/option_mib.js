/* global MIB_GROUP_SELECT, MIB_GROUP_SELECT_INIT, fm_ld, ajax_dynamic_post, fm_fl, MIB_SUB_SELECT, OPTION_BACK */

var r_data = 0;

function mib_data_get(fdata = '', duration = 400, iswait = true) {
    if (get_funid() === 0) {
        animation_to_sites('data_output', 400, '../');
    } else {
        if (get_funid() === 20 || get_funid() === 21) {
            r_data = 0;
        }
        if (fdata === '') {
            fdata = [];
        }
        fdata.push({'name': 'f_id', 'value': get_funid()});
        fdata.push({'name': 'sel_id', 'value': r_data});
        if (iswait) {
            animation('data_output', duration, fm_ld);
        } else {
            $('button').attr('disabled', true);
        }
        ajax_dynamic_post('/scripts/mib/mib.php', fdata).then(function (data) {
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
}
}

$(document).ready(function () {
    change_mib_group_select();
    mib_data_get('', 0);
});