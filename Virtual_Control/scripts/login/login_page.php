<?php

include __DIR__ . '/../general/page.php';

class LoginPage extends Page {

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $code レスポンスコードを指定します
     * @param string $data レスポンスデータを指定します
     * @param string $id フォームIDを指定します（Default: 'fm_pg'）
     */
    public function __construct($code, $data, $id = 'fm_pg') {
	parent::__construct($code, $data, $id);
    }

    /**
     * [SET] ANALYページデータ取得設定
     * 
     * レスポンスコードより識別される情報でページの分岐を行います<br>
     * 継承により分岐の拡張ができます
     */
    public function setPageFunc() {
	switch($this->response_code) {
	    case 0:
		$this->setLogin();
		break;
	    case 1: case 2: case 3:
		$this->setResCodeToData();
		break;
	    default:
		$this->setFail();
	}
    }

    /**
     * [SET] ログイン画面設定
     * 
     * ログイン情報がない場合は、この画面を設定し、ユーザID・パスワードの入力を求めます
     */
    public function setLogin() {
	$this->SubTitle('ログイン', 'ユーザIDとパスワードを入力してログインしましょう。', 'user-check');
	$this->Input('in_lg_id', 'ユーザID', '（半角英数字・小文字1-20文字）ユーザIDは、VCServerによって指定されています。', 'id-card-alt', true);
	$this->Password('in_lg_ps', 'パスワード', '（半角英数字・小/大文字・記号【$_】10-30文字）指定のパスワードを入力します。', 'key');
	$this->WarnForm('fm_warn');
	$this->Button('fm_sb', 'ログイン', 'submit', 'sign-in-alt');
    }
}
