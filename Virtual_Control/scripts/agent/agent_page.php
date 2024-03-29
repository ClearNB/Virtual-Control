<?php

include __DIR__ . '/../general/page.php';

class AgentPage extends Page {

    /**
     * [VAR] $rules
     * 
     * 入力ルールについて定義しています
     * 
     * @var array $rules
     */
    private static $rules = [
	'HOSTADDRESS' => '<strong>【条件】ドメイン名・IPv4アドレス・IPv6のいずれかを入力していること<br>（※）IPv6アドレスは「0省略」記述が可能です。</strong>',
	'COMMUNITY' => '<strong>【条件】半角英数字（小文字・大文字）・記号（$ _ のみ）を用いて255文字まで',
    ];
    private static $icons = [
	'HOSTADDRESS' => 'server',
	'COMMUNITY' => 'book',
	'MIB' => 'object-group'
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
     * [GET] AGENTページデータ取得
     * 
     * レスポンスコードより識別される情報でページの分岐を行います<br>
     * 継承により分岐の拡張ができます
     */
    public function setPageFunc() {
	switch ($this->response_code) {
	    case 0: //SELECT
		$this->setSelect();
		break;
	    case 1: //CREATE
		$this->setCreate();
		break;
	    case 2: //EDIT SELECT
		$this->setEditSelect();
	    case 3: case 4: case 5: //EDIT
		$this->setEdit();
		break;
	    case 6: //DELETE
		$this->setDelete();
		break;
	    case 10: // SUCCESS
		$this->setCorrect();
		break;
	    case 11: // FAIL (DATABASE)
		$this->setFail();
		break;
	    case 12: case 14: // FAIL (ON PAGE)
		$this->setResCodeToData();
		break;
	    case 13:
		$this->setAuth();
		break;
	    case 15: //CONFIRM
		$this->setConfirm();
		break;
	    case 999: //FAIL
		$this->setFail();
		break;
	}
    }

    /**
     * [SET] エージェント選択画面設定
     * 
     * エージェントテーブルデータをもとに、アカウント選択画面を設定します
     */
    public function setSelect() {
	$this->Button('bt_ag_bk', '設定一覧に戻る', 'button', 'list');
	$this->SubTitle('エージェント作成・編集・削除', '作成は「作成」ボタンを、編集・削除はエージェント一覧表からエージェントをラジオボタンで選択してからボタンを押します。', 'user');
	$this->Caption($this->response_data);
	$this->Button('bt_ag_cr', '作成', 'button', 'plus-square');
	$this->Button('bt_ag_ed', '編集', 'button', 'edit', true);
	$this->Button('bt_ag_dl', '削除', 'button', 'trash-alt', true);
    }

    /**
     * [SET] エージェント作成画面設定
     * 
     * エージェント作成画面を設定します
     */
    public function setCreate() {
	$this->Button('bt_cr_bk', 'エージェント選択へ戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('エージェント作成', '以下の情報を入力してください', 'plus-circle', false, '1: 情報入力');
	$this->Input('in_ag_hs', 'エージェントホストアドレス', self::$rules['HOSTADDRESS'], self::$icons['HOSTADDRESS'], true);
	$this->Input('in_ag_cm', 'コミュニティ名', self::$rules['COMMUNITY'], self::$icons['COMMUNITY'], true);
	$this->setSub();
	$this->WarnForm('fm_warn');
	$this->Button('bt_cr_nx', '次へ', 'submit', 'sign-in-alt');
    }

    /**
     * [SET] エージェント編集選択画面設定
     * 
     * エージェントデータ（選択したデータ）をもとに、編集選択画面を設定します
     */
    public function setEditSelect() {
	$this->Button('bt_ed_bk', 'エージェント選択画面に戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('【' . $this->response_data['COMMUNITY'] . '】' . $this->response_data['HOSTADDRESS'], '以下から変更したい項目を選択してください。', 'edit', false);
	$this->Button('bt_ed_hs', 'エージェントホスト', 'button', self::$icons['HOSTADDRESS']);
	$this->Button('bt_ed_cm', 'コミュニティ名', 'button', self::$icons['COMMUNITY']);
	$this->Button('bt_ed_oi', 'MIBサブツリー選択', 'button', self::$icons['MIB']);
    }

    /**
     * [SET] アカウント編集画面設定
     * 
     * ファンクションIDおよびアカウントデータをもとに、編集画面を設定します
     */
    public function setEdit() {
	switch ($this->response_code) {
	    case 3: //HOSTADDRESS
		$this->Button('bt_hs_bk', '編集選択画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('エージェント編集（エージェントホスト）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のエージェント: ' . $this->response_data['HOSTADDRESS'] . ' (' . $this->response_data['COMMUNITY'] . ')');
		$this->closeList();
		$this->Input('in_ag_hs', 'エージェントホスト', self::$rules['HOSTADDRESS'], 'user-check', true);
		$this->WarnForm('fm_warn');
		$this->Button('bt_id_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	    case 4: //COMMUNITY
		$this->Button('bt_cm_bk', '編集選択画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('エージェント編集（コミュニティ名）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のエージェント: ' . $this->response_data['HOSTADDRESS'] . ' (' . $this->response_data['COMMUNITY'] . ')');
		$this->closeList();
		$this->Input('in_ag_cm', 'コミュニティ名', self::$rules['COMMUNITY'], self::$icons['COMMUNITY'], true);
		$this->WarnForm('fm_warn');
		$this->Button('bt_nm_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	    case 5: //OID
		$this->Button('bt_oi_bk', '編集選択画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('エージェント編集（MIBサブツリー選択）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のエージェント: ' . $this->response_data['HOSTADDRESS'] . ' (' . $this->response_data['COMMUNITY'] . ')');
		$this->closeList();
		$this->Caption($this->response_data['MIBSELECT']);
		$this->WarnForm('fm_warn');
		$this->Button('bt_nm_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	}
    }

    /**
     * [SET] 削除確認画面設定
     * 
     * 削除を行う前に、情報を確認する画面を設定します
     */
    public function setDelete() {
	$this->Button('bt_dl_bk', 'エージェント選択画面に戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('エージェント削除', '以下のエージェントを削除します。', 'trash-alt');
	$this->openList();
	$this->addList('エージェントホスト: ' . $this->response_data['HOSTADDRESS']);
	$this->addList('コミュニティ名: ' . $this->response_data['COMMUNITY']);
	$this->closeList();
	$this->Button('bt_dl_sb', '削除する', 'button', 'sign-in-alt');
    }
    
    private function setSub() {
	$this->FormStart('MIBサブツリー選択', self::$icons['MIB']);
	$this->openList();
	$this->addList('MIBサブツリーを、以下から1つ以上選択します。');
	$this->addList('「全てを選択」にチェックがついていない状態で押すと、列挙されているすべてのサブツリーを選択できます。');
	$this->addList('ついている状態で押すと、全て解除されます。');
	$this->closeList();
	$this->Caption($this->response_data);
	$this->FormEnd();
    }
}
