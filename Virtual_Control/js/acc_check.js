$(document).on('change', 'input[name="index-s"]', function () {
    var con = $(this).val();
    var s = 0;
    if ($('#index-0').prop('checked') === true) {
        s = 1;
    }
    if (con === 'all') {
        var changeFlag = $(this).prop("checked");
        $("#account_table input:checkbox").prop("checked", changeFlag);
    } else {
        var c_none_checked = $('input[name="index-s"]').not(':checked, #index-0').length;
        if (c_none_checked === 0) {
            $('#index-0').prop('checked', true);
            s = 1;
        } else {
            $('#index-0').prop('checked', false);
            s = 0;
        }
    }
    var c_checked = $("#account_table input:checkbox:checked").length - s;
    if (c_checked > 0) {
        if (c_checked === 1) {
            $('#bt_ac_ed').prop("disabled", false);
	    $('#bt_ac_dl').prop("disabled", false);
        } else {
            $('#bt_ac_ed').prop("disabled", true);
	    $('#bt_ac_dl').prop("disabled", true);
        }
    } else {
        $('#bt_ac_ed').prop("disabled", true);
        $('#bt_ac_dl').prop("disabled", true);
    }
});