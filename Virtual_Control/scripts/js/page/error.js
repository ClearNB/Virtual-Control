/* global fm_pg */

$(document).ready(function () {
    animation('data_output', 0, fm_pg);
});

$(document).on('click', '#bt_pg_bk', function () {
    animation_to_sites('data_output', 400, '/');
});
