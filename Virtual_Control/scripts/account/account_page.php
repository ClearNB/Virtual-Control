<?php

include_once __DIR__ . '/../general/page.php';

class AccountPage extends Page {

    /**
     * [VAR] $rules
     * 
     * 入力ルールについて定義しています
     * 
     * @var array $rules
     */
    private static $rules = [
	'USERID' => '<strong>【条件】半角英数字（[小文字] 数字・英字組み合わせ）: 5-20文字</strong>',
	'USERNAME' => '<strong>【条件】(半角) 1-30文字, (全角) 1-15文字</strong><br>（※）文字は「UTF-8」のエンコード方式によりカウントされます。',
	'PASSWORD' => '<strong>【条件】半角英数字[小文字・大文字組み合わせ]・数字・記号( $ _ のみ)を組み合わせて10-30文字<br>（※）記号は指定された2文字のみをご利用ください。<br>例: GSC_Pass$01（11文字）',
	'PASSWORD_CONFIRM' => '確認のためもう一度パスワードを入力してください。'
    ];

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
     * [GET] ACCOUNTページデータ取得
     * 
     * レスポンスコードより識別される情報でページの分岐を行います<br>
     * 継承により分岐の拡張ができます
     */
    public function setPageFunc() {
	switch ($this->response_code) {
	    case 1: $this->setSelect();
		break;
	    case 2: $this->setCreate();
		break;
	    case 3: $this->setEditSelect();
		break;
	    case 4: case 5: case 6: $this->setEdit();
		break;
	    case 7: $this->setDelete();
		break;
	    case 10: $this->setCorrect();
		break;
	    case 11: $this->setFail();
		break;
	    case 12: case 15: $this->response_data;
		break;
	    case 13: $this->setUserFail();
		break;
	    case 14: $this->fm_at();
		break;
	    case 16: $this->setConfirm();
		break;
	    case 999: $this->setFail();
		break;
	}
    }

    /**
     * [SET] アカウント選択画面設定
     * 
     * アカウントテーブルデータをもとに、アカウント選択画面を設定します
     */
    protected function setSelect() {
	$this->Button('bt_ac_bk', '設定一覧へ', 'button', 'list');
	$this->SubTitle('ユーザ作成・編集・削除', '作成は「作成」ボタンを、編集・削除は「未ログイン」のユーザを左のラジオボタンで選択してからボタンを押します。<br>（※）「編集」のみ、自分のユーザを選択して編集することができます。', 'user');
	$this->Caption($this->response_data);
	$this->Button('bt_ac_cr', '作成', 'button', 'plus-square');
	$this->Button('bt_ac_ed', '編集', 'button', 'edit', true);
	$this->Button('bt_ac_dl', '削除', 'button', 'trash-alt', true);
    }

    /**
     * [SET] アカウント作成画面設定
     * 
     * アカウント作成画面を設定します
     */
    protected function setCreate() {
	$this->Button('bt_cr_bk', 'ユーザ選択へ戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('アカウント作成', '以下の情報を入力してください', 'plus-circle', false, '1: 情報入力');
	$this->Input('in_ac_id', 'ユーザID', self::$rules['USERID'], 'file-invoice', true);
	$this->Input('in_ac_nm', 'ユーザ名', self::$rules['USERNAME'], 'user-circle', true);
	$this->Password('in_ac_ps', 'パスワード', self::$rules['PASSWORD'], 'key');
	$this->Password('in_ac_ps_rp', 'パスワードの確認', self::$rules['PASSWORD_CONFIRM'], 'key');
	$this->FormTitle('権限', 'user-shield');
	$this->openList();
	$this->addList('VCServer: 監視に加え、設定管理（ユーザ・エージェント・MIB）を行うことができます。つまり管理者権限です。');
	$this->addList('VCHost: ANALY, WARNでの監視のみの権限が与えられます。設定管理を行うことはできません。');
	$this->closeList();
	$this->Check(1, 'rd_01', 'in_ac_pr', '0', 'VCServer', true);
	$this->Check(1, 'rd_02', 'in_ac_pr', '1', 'VCHost', false);
	$this->WarnForm('fm_warn');
	$this->Button('bt_cr_nx', '次へ', 'submit', 'sign-in-alt');
    }

    /**
     * [SET] アカウント編集選択画面設定
     * 
     * アカウントデータ（選択したデータ）をもとに、編集選択画面を取得します
     */
    protected function setEditSelect() {
	$this->Button('bt_ed_bk', 'アカウント選択画面に戻る', 'button', 'chevron-circle-left');
	$this->SubTitle($this->response_data['USERNAME'] . ' (' . $this->response_data['USERID'] . ')', '以下から変更したい項目を選択してください。', 'edit', false, $this->response_data['PERMISSION']);
	$this->Button('bt_ed_id', 'ユーザID', 'button', 'file-invoice');
	$this->Button('bt_ed_nm', 'ユーザ名', 'button', 'user-circle');
	$this->Button('bt_ed_ps', 'パスワード', 'button', 'key');
    }

    /**
     * [SET] アカウント編集画面設定
     * 
     * ファンクションIDおよびアカウントデータをもとに、編集画面を設定します
     */
    protected function setEdit() {
	switch ($this->response_code) {
	    case 4: //USERID
		$this->Button('bt_id_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('アカウント編集（ユーザID）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のユーザ: ' . $this->response_data['USERID'] . ' (' . $this->response_data['USERNAME'] . ')');
		$this->closeList();
		$this->Input('in_ac_id', 'ユーザID', self::$rules['USERID'], 'file-invoice', true);
		$this->WarnForm('fm_warn');
		$this->Button('bt_id_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	    case 5: //USERNAME
		$this->Button('bt_nm_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('アカウント編集（ユーザ名）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のユーザ: ' . $this->response_data['USERID'] . ' (' . $this->response_data['USERNAME'] . ')');
		$this->closeList();
		$this->Input('in_ac_nm', 'ユーザ名', self::$rules['USERNAME'], 'user-circle', true);
		$this->WarnForm('fm_warn');
		$this->Button('bt_nm_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	    case 6: //PASSWORD
		$this->Button('bt_nm_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('アカウント編集（パスワード）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のユーザ: ' . $this->response_data['USERID'] . ' (' . $this->response_data['USERNAME'] . ')');
		$this->closeList();
		$this->Password('in_ac_ps', 'パスワード', self::$rules['PASSWORD'], 'key');
		$this->Password('in_ac_ps_rp', 'パスワードの確認', self::$rules['PASSWORD_CONFIRM'], 'key');
		$this->WarnForm('fm_warn');
		$this->Button('bt_nm_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	}
    }

    /**
     * [SET] 削除画面設定
     * 
     * 削除を行う前に、情報を確認する画面を設定します
     */
    protected function setDelete($account_data) {
	$this->Button('bt_dl_bk', 'アカウント選択画面に戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('アカウント削除', '以下のユーザを削除します。', 'trash-alt');
	$this->openList();
	$this->addList('ユーザID: ' . $account_data['USERID']);
	$this->addList('ユーザ名: ' . $account_data['USERNAME']);
	$this->addList('権限: ' . $account_data['PERMISSION']);
	$this->closeList();
	$this->Button('bt_dl_sb', '削除する', 'button', 'sign-in-alt');
    }

    /**
     * [SET] ユーザ編集・削除時エラー設定
     * 
     * ユーザがログイン中であるのにもかかわらず編集や削除を行った場合の画面を設定します
     * (Button) id: bt_fl_bk , output : ユーザ選択画面に戻る , type: button
     */
    protected function setUserFail() {
	$logs = [
	    'このユーザは現在ログイン中です。',
	    '削除時は、あなたを含め、ログインしているユーザは削除できません。',
	    '接続先の端末のセッションが切れていてもログイン状態は継続されています。ユーザに変更を加えたい場合は、そのユーザを一度ログインしログアウトする必要があります。'
	];
	$this->fm_fl($logs, ['bt_fl_bk', 'ユーザ選択画面に戻る', 'button', 'sync-alt'], 'ユーザに変更を加えることはできません');
    }
}
