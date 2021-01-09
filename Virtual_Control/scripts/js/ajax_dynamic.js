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

/**
 * sends a request to the specified url from a form. this will change the window location.
 * @param {string} path the path to send the post request to
 * @param {object} params the paramiters to add to the url
 * @param {string} [method=post] the method to use on the form
 */
function post(path, params, method = 'post') {

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    const form = document.createElement('form');
    form.method = method;
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