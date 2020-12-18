function animation_to_sites(output_id, duration, href) {
    $('#' + output_id).hide(duration, function () {
	window.location.href = href;
    });
}

function animation(output_id, duration, data) {
    $('#' + output_id).hide(duration, function () {
        $('#' + output_id).html(data);
        $('#' + output_id).show('slow'); 
    });
}