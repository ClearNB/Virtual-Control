<?php

include_once __DIR__ . '/../general/page.php';

class InitPage extends Page {

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

    public function setPageFunc() {
	switch ($this->response_code) {
	    case 0: //CONFIRM
		$this->setConfirm();
		break;
	    case 1: //CORRECT
		$this->setCorrect();
		break;
	    default: //FAIL
		$this->setFail();
		break;
	}
    }

    protected function setConfirm() {
	$this->SubTitle('データベース初期化', 'ここでは、データベースの初期化を行うことができます。以下の注意事項をよく読んで実行してください。', 'sync');
	$this->openList();
	$this->addList('これを行うことにより初期状態に戻され、テーブルの定義やテーブルの内容が全て初期状態に置き換えられます。');
	$this->addList('/data/data_format.jsonというファイルがあることをご確認ください。');
	$this->addList('初期化を行う前に、データベースサーバとWebサーバが相互に接続できているか確認してください。');
	$this->closeList();
	$this->Button('bt_pg_st', '初期化を開始', 'button', 'play');
	$this->Button('bt_pg_bk', 'ホームに戻る', 'button', 'home');
    }
    
    protected function setCorrect() {
	$this->SubTitle('初期化が完了しました！', '早速、新しくなったデータで試しましょう！', 'thumbs-up');
	$this->openList();
	$this->addList('ユーザ名: [USERID]');
	$this->addList('パスワード: [PASS]');
	$this->closeList();
	$this->Button('bt_sc_ln', 'ホームに戻る', 'button', 'home');
    }

}
