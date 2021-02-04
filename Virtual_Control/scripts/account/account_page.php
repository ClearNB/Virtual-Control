<?php

include_once __DIR__ . '/../general/former.php';

class AccountPage extends form_generator {

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
     * オブジェクトコンストラクタです<br>
     * Formerのコンストラクタも含まれます
     * 
     * @param string $id ページのIDを指定します（Default: 'fm_pg'）
     */
    public function __construct() {
	parent::__construct('fm_pg');
    }

    public function get_page_byid($id, $data) {
	$res_page = '';
	switch ($id) {
	    case 1: $res_page = $this->getSelect($data);
		break;
	    case 2: $res_page = $this->getCreate();
		break;
	    case 3: $res_page = $this->getEditSelect($data);
		break;
	    case 4: case 5: case 6: $res_page = $this->getEdit($id, $data);
		break;
	    case 7: $res_page = $this->getDelete($data);
		break;
	    case 10: $res_page = $this->getCorrect();
		break;
	    case 11: $res_page = $this->getFail($data);
		break;
	    case 12: case 15: $res_page = $data;
		break;
	    case 13: $res_page = $this->getUserFail();
		break;
	    case 14: $res_page = $this->fm_at();
		break;
	    case 16: $res_page = $this->getConfirm($data);
		break;
	    case 999: $res_page = $this->getFail($data);
		break;
	}
	$this->reset();
	return $res_page;
    }

    /**
     * [GET] アカウント選択画面取得
     * 
     * アカウントテーブルデータをもとに、アカウント選択画面を作成します
     * 
     * @param string $table AccountTableで取得したアカウントテーブルデータ
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getSelect($table): string {
	$this->Button('bt_ac_bk', '設定一覧へ', 'button', 'list');
	$this->SubTitle('ユーザ作成・編集・削除', '作成は「作成」ボタンを、編集・削除は「未ログイン」のユーザを左のラジオボタンで選択してからボタンを押します。<br>（※）「編集」のみ、自分のユーザを選択して編集することができます。', 'user');
	$this->Caption($table);
	$this->Button('bt_ac_cr', '作成', 'button', 'plus-square');
	$this->Button('bt_ac_ed', '編集', 'button', 'edit', true);
	$this->Button('bt_ac_dl', '削除', 'button', 'trash-alt', true);
	return $this->Export();
    }

    /**
     * [GET] アカウント作成画面取得
     * 
     * アカウント作成画面を作成します
     * 
     * @return string ページデータをHTMLの文字列で返します
     */
    public function getCreate() {
	$this->Button('bt_cr_bk', 'ユーザ選択へ戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('アカウント作成', '以下の情報を入力してください', 'plus-circle', false, '1: 情報入力');
	$this->Input('in_ac_id', 'ユーザID', self::$rules['USERID'], 'file-invoice', true);
	$this->Input('in_ac_nm', 'ユーザ名', self::$rules['USERNAME'], 'user-circle', true);
	$this->Password('in_ac_ps', 'パスワード', self::$rules['PASSWORD'], 'key', true);
	$this->Password('in_ac_ps_rp', 'パスワードの確認', self::$rules['PASSWORD_CONFIRM'], 'key', true);
	$this->FormTitle('権限', 'user-shield');
	$this->openList();
	$this->addList('VCServer: 監視に加え、設定管理（ユーザ・エージェント・MIB）を行うことができます。つまり管理者権限です。');
	$this->addList('VCHost: ANALY, WARNでの監視のみの権限が与えられます。設定管理を行うことはできません。');
	$this->closeList();
	$this->Check(1, 'rd_01', 'in_ac_pr', '0', 'VCServer', true);
	$this->Check(1, 'rd_02', 'in_ac_pr', '1', 'VCHost', false);
	$this->WarnForm('fm_warn');
	$this->Button('bt_cr_nx', '次へ', 'submit', 'sign-in-alt');
	return $this->Export();
    }

    /**
     * [GET] アカウント編集選択画面取得
     * 
     * アカウントデータ（選択したデータ）をもとに、編集選択画面を取得します
     * 
     * @param array $account_data アカウントデータ（AccountDataで取得したデータで、ユーザ情報を取得したもの）
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getEditSelect($account_data) {
	$this->Button('bt_ed_bk', 'アカウント選択画面に戻る', 'button', 'chevron-circle-left');
	$this->SubTitle($account_data['USERNAME'] . ' (' . $account_data['USERID'] . ')', '以下から変更したい項目を選択してください。', 'edit', false, $account_data['PERMISSION']);
	$this->Button('bt_ed_id', 'ユーザID', 'button', 'file-invoice');
	$this->Button('bt_ed_nm', 'ユーザ名', 'button', 'user-circle');
	$this->Button('bt_ed_ps', 'パスワード', 'button', 'key');
	return $this->Export();
    }

    /**
     * [GET] アカウント編集画面作成
     * 
     * ファンクションIDおよびアカウントデータをもとに、編集画面を作成します
     * 
     * @param string $type ファンクションIDを指定します（4..USERID, 5..USERNAME, 6..PASSWORD）
     * @param array $account_data アカウントデータ（AccountDataで取得したデータで、ユーザ情報を取得したもの）
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getEdit($type, $account_data) {
	switch ($type) {
	    case 4: //USERID
		$this->Button('bt_id_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('アカウント編集（ユーザID）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のユーザ: ' . $account_data['USERID'] . ' (' . $account_data['USERNAME'] . ')');
		$this->closeList();
		$this->Input('in_ac_id', 'ユーザID', self::$rules['USERID'], 'file-invoice', true);
		$this->WarnForm('fm_warn');
		$this->Button('bt_id_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	    case 5: //USERNAME
		$this->Button('bt_nm_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('アカウント編集（ユーザ名）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のユーザ: ' . $account_data['USERID'] . ' (' . $account_data['USERNAME'] . ')');
		$this->closeList();
		$this->Input('in_ac_nm', 'ユーザ名', self::$rules['USERNAME'], 'user-circle', true);
		$this->WarnForm('fm_warn');
		$this->Button('bt_nm_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	    case 6: //PASSWORD
		$this->Button('bt_nm_bk', '編集画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('アカウント編集（パスワード）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のユーザ: ' . $account_data['USERID'] . ' (' . $account_data['USERNAME'] . ')');
		$this->closeList();
		$this->Password('in_ac_ps', 'パスワード', self::$rules['PASSWORD'], 'key', true);
		$this->Password('in_ac_ps_rp', 'パスワードの確認', self::$rules['PASSWORD_CONFIRM'], 'key', true);
		$this->WarnForm('fm_warn');
		$this->Button('bt_nm_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	}
	return $this->Export();
    }

    /**
     * [GET] 削除画面取得
     * 
     * 削除を行う前に、情報を確認する画面を作成します
     * 
     * @param array $account_data アカウントデータ（AccountDataで取得したデータで、ユーザ情報を取得したもの）
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getDelete($account_data) {
	$this->Button('bt_dl_bk', 'アカウント選択画面に戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('アカウント削除', '以下のユーザを削除します。', 'trash-alt');
	$this->openList();
	$this->addList('ユーザID: ' . $account_data['USERID']);
	$this->addList('ユーザ名: ' . $account_data['USERNAME']);
	$this->addList('権限: ' . $account_data['PERMISSION']);
	$this->closeList();
	$this->Button('bt_dl_sb', '削除する', 'button', 'sign-in-alt');
	return $this->Export();
    }

    /**
     * [GET] 更新確認画面作成
     * 
     * 入力〜認証までの段階をクリアした場合、入力された情報をもう一度確認する画面を作成します
     * 
     * @param string $confirm_data AccountSetで認証まで確認されたデータを指定します
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getConfirm($confirm_data) {
	$this->SubTitle('入力確認', '入力事項が正しければ「更新する」を押してください。<br>（※）「キャンセル」の場合、アカウント選択画面に遷移します。', 'user-check');
	$this->Caption($confirm_data);
	$this->Button('bt_cf_sb', '更新する', 'button', 'sign-in-alt');
	$this->Button('bt_cf_bk', 'キャンセル', 'button', 'chevron-circle-left');
	return $this->Export();
    }

    public function getFail($log = '〈形式ログはなし〉') {
	$logs = [
	    'データベースとの接続をご確認ください。',
	    '要求しているデータと実際のデータを比べ、記述や内容が正しいかどうかをご確認ください。',
	    'アカウント認証であるセッションが切れていると思われます。もう一度ログインし直してから再試行してください。',
	    $log
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'エラーが発生しました');
	return $this->Export();
    }

    public function getUserFail() {
	$logs = [
	    'このユーザは現在ログイン中です。',
	    '削除時は、あなたを含め、ログインしているユーザは削除できません。',
	    '接続先の端末のセッションが切れていてもログイン状態は継続されています。ユーザに変更を加えたい場合は、そのユーザを一度ログインしログアウトする必要があります。'
	];
	$this->fm_fl($logs, ['bt_fl_bk', 'ユーザ選択画面に戻る', 'button', 'sync-alt'], 'ユーザに変更を加えることはできません');
	return $this->Export();
    }

    public function getCorrect() {
	$this->SubTitle('更新に成功しました！', 'ボタンを押して変更が反映したか確認しましょう！', 'check-square');
	$this->Button('bt_cs_bk', 'ユーザ選択画面に戻る', 'button', 'chevron-circle-left');
	return $this->Export();
    }

}
