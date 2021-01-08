$(function() {
    var requiredCheckboxes = $(':checkbox[required]');
    requiredCheckboxes.change(function() {
        if (requiredCheckboxes.is(':checked')) {requiredCheckboxes.removeAttr('required');}
        else {requiredCheckboxes.attr('required', 'required');}
    });
    $("input").each(function() {
        $(this).on('invalid', function(e) {
            e.target.setCustomValidity('');
            if (!e.target.validity.valid) {
                e.target.setCustomValidity('最低でも1つ以上にチェックしてください。');
            }
        }).on('input, click', function(e) {e.target.setCustomValidity('');});
    });
});