/**
 * [FUNCTION] ページアニメーション遷移
 * 
 * @param {string} output_id 遷移対象のHTMLコードのIDを指定します
 * @param {int} duration 遷移時間を指定します（ミリ秒）
 * @param {string} href ページ名を指定します
 * @returns {void} 指定されたhrefのページ先へ移動します
 */
function animation_to_sites(output_id, duration, href) {
    $('#' + output_id).hide(duration, function () {
	window.location.href = href;
    });
}

/**
 * [FUNCTION] アニメーション遷移
 *
 * @param {string} output_id 遷移対象のHTMLコードのIDを指定します
 * @param {int} duration 遷移時間を指定します（ミリ秒）
 * @param {string} data HTMLコードの文字列
 * @returns {void} 指定されたdataのHTMLコードに入れ替えます
 */
function animation(output_id, duration, data) {
    $('#' + output_id).hide(duration, function () {
        $('#' + output_id).html(data);
        $('#' + output_id).show('slow'); 
    });
}

/**
 * [FUNCTION] アニメーション遷移（フォーム）
 *
 * @param {string} output_id 遷移対象のHTMLコードのIDを指定します
 * @param {int} duration 遷移時間を指定します（ミリ秒）
 * @param {string} data HTMLコードの文字列
 * @returns {void} 指定されたdataのHTMLコードに入れ替えます
 */
function animation_form(output_id, duration, data) {
    $('#' + output_id).hide(duration, function () {
        $('#' + output_id).html(data);
        $('#' + output_id).show(200); 
    });
}