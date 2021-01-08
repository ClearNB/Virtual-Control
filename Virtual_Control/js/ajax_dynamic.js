/**
 * [FUNCTION] Ajax POST処理（フォームデータ込）
 * 
 * @param {string} url 呼び出し先のページ名を指定します
 * @param {string} data フォームデータ（serializeされたもの）を指定します
 * @returns {jqXHR} Ajax遷移成功の場合、その内容に関わらず返されます。
 */
function ajax_dynamic_post(url, data) {
    return $.ajax({
	type: 'POST',
	url: url,
	data: data,
	crossDomain: false,
	dataType: 'json'
    });
}

/**
 * [FUNCTION] Ajax POST処理（GET専用）
 * 
 * @param {string} url 呼び出し先のページ名を指定します
 * @returns {jqXHR} Ajax遷移成功の場合、その内容に関わらず返されます。
 */
function ajax_dynamic_post_toget(url) {
    return $.ajax({
	type: 'POST',
	url: url,
	crossDomain: false,
	dataType: 'json'
    });
}

function downloadCSV(data, filename) {
    var downloadData = new Blob([data], {type: "text/csv"});
    if (window.navigator.msSaveBlob) {
	window.navigator.msSaveBlob(downloadData, filename);
    } else {
	var downloadUrl = (window.URL || window.webkitURL).createObjectURL(downloadData);
	var link = document.createElement("a");
	link.href = downloadUrl;
	link.target = '_blank';
	link.download = filename;
	link.click();
	(window.URL || window.webkitURL).revokeObjectURL(downloadUrl);
    }
}