<?php

include __DIR__ . '/../general/former.php';

class LoginPage extends form_generator {

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです<br>
     * Formerのコンストラクタも含まれます
     * 
     * @param string $id ページのIDです（Default: 'fm_pg'）
     */
    public function __construct() {
	parent::__construct('fm_pg');
    }

    public function getpage_bycode($code) {
	$res_page = '';

	switch ($code) {
	    case 0: //LOGIN
		$res_page = $this->getLogin();
		break;
	    default:
		$res_page = $this->getFail();
		break;
	}
	$this->reset();
	return $res_page;
    }

    public function getLogin() {
	$this->SubTitle('ログイン', 'ユーザIDとパスワードを入力してログインしましょう。', 'user-check');
	$this->Input('in_lg_id', 'ユーザID', '（半角英数字・小文字1-20文字）ユーザIDは、VCServerによって指定されています。', 'id-card-alt', true);
	$this->Password('in_lg_ps', 'パスワード', '（半角英数字・小/大文字・記号【$_】10-30文字）指定のパスワードを入力します。', 'key', true);
	$this->WarnForm('fm_warn');
	$this->Button('fm_sb', 'ログイン', 'submit', 'sign-in-alt');
	return $this->Export();
    }
    
    public function getFail() {
	$logs = [
	    '要求したページは表示されません。',
	    '要求しているデータと実際のデータを比べ、記述や内容が正しいかどうかをご確認ください。',
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'エラーが発生しました');
	return $this->Export();
    }

}
