/**
 * [Function] Ajax POST処理（クロスドメインリンク無し・データ込み）
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
 * [FUNCTION] Ajax POST処理（クロスドメイン無し・GET専用）
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

/**
 * [Function] Ajax POST処理（リンクポスト系）
 * 
 * @param {string} path URLを指定します
 * @param {object} params データを指定します
 */
function post(path, params) {
    const form = document.createElement('form');
    form.method = 'post';
    form.action = path;

    for (const key in params) {
	if (params.hasOwnProperty(key)) {
	    const hiddenField = document.createElement('input');
	    hiddenField.type = 'hidden';
	    hiddenField.name = key;
	    hiddenField.value = params[key];

	    form.appendChild(hiddenField);
	}
    }

    document.body.appendChild(form);
    form.submit();
}