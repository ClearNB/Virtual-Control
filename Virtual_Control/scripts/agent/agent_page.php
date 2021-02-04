<?php

include __DIR__ . '/../general/former.php';

class AgentPage extends form_generator {

    /**
     * [VAR] $rules
     * 
     * 入力ルールについて定義しています
     * 
     * @var array $rules
     */
    private static $rules = [
	'AGENTHOST' => '<strong>【条件】ドメイン名・IPv4アドレス・IPv6のいずれかを入力していること<br>（※）IPv6アドレスは「0省略」記述が可能です。</strong>',
	'COMMUNITY' => '<strong>【条件】半角英数字（小文字・大文字）・記号（$ _ のみ）を用いて255文字まで',
    ];
    private static $icons = [
	'AGENTHOST' => 'server',
	'COMMUNITY' => 'book',
	'MIB' => 'object-group'
    ];

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです<br>
     * Formerのコンストラクタも含まれます
     * 
     * @param string $id ページのIDを指定します（Default: 'fm_pg'）
     */
    public function __construct($id = 'fm_pg') {
	parent::__construct($id);
    }

    /**
     * [GET] エージェント選択画面取得
     * 
     * エージェントテーブルデータをもとに、アカウント選択画面を作成します
     * 
     * @param string $select AgentSelectで取得したエージェントラジオボタングループデータ
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getSelect($select): string {
	$this->Button('bt_ag_bk', '設定一覧へ', 'button', 'list');
	$this->SubTitle('エージェント作成・編集・削除', '作成は「作成」ボタンを、編集・削除はエージェント一覧表からエージェントをラジオボタンで選択してからボタンを押します。', 'user');
	$this->Caption($select);
	$this->Button('bt_ag_cr', '作成', 'button', 'plus-square');
	$this->Button('bt_ag_ed', '編集', 'button', 'edit', true);
	$this->Button('bt_ag_dl', '削除', 'button', 'trash-alt', true);
	return $this->Export();
    }

    /**
     * [GET] エージェント作成画面取得
     * 
     * エージェント作成画面を作成します
     * 
     * @param string $mib_select MIBセレクタの文字列（HTML）を指定します
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getCreate($mib_select) {
	$mib = $this->getSub();
	$this->Button('bt_cr_bk', 'エージェント選択へ戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('エージェント作成', '以下の情報を入力してください', 'plus-circle', false, '1: 情報入力');
	$this->Input('in_ag_hs', 'エージェントホストアドレス', self::$rules['AGENTHOST'], self::$icons['AGENTHOST'], true);
	$this->Input('in_ag_cm', 'コミュニティ名', self::$rules['COMMUNITY'], self::$icons['COMMUNITY'], true);
	$this->Caption($mib . $mib_select);
	$this->WarnForm('fm_warn');
	$this->Button('bt_cr_nx', '次へ', 'submit', 'sign-in-alt');
	return $this->Export();
    }

    /**
     * [GET] アカウント編集選択画面取得
     * 
     * アカウントデータ（選択したデータ）をもとに、編集選択画面を取得します
     * 
     * @param array $agentdata エージェントデータ（AgentDataで取得したデータで、ユーザ情報を取得したもの）
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getEditSelect($agentdata) {
	$this->Button('bt_ed_bk', 'エージェント選択画面に戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('【' . $agentdata['COMMUNITY'] . '】' . $agentdata['AGENTHOST'], '以下から変更したい項目を選択してください。', 'edit', false);
	$this->Button('bt_ed_hs', 'エージェントホスト', 'button', self::$icons['AGENTHOST']);
	$this->Button('bt_ed_cm', 'コミュニティ名', 'button', self::$icons['COMMUNITY']);
	$this->Button('bt_ed_oi', 'MIBサブツリー選択', 'button', self::$icons['MIB']);
	return $this->Export();
    }

    /**
     * [GET] アカウント編集画面作成
     * 
     * ファンクションIDおよびアカウントデータをもとに、編集画面を作成します
     * 
     * @param string $type ファンクションIDを指定します（4..USERID, 5..USERNAME, 6..PASSWORD）
     * @param array $agent_data エージェントデータ（AgentDataで取得したデータで、ユーザ情報を取得したもの）
     * @param string $mib_select MIBセレクタの文字列（HTML）を指定します
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getEdit($type, $agent_data, $mib_select = '') {
	switch ($type) {
	    case 14: //AGENTHOST
		$this->Button('bt_hs_bk', '編集選択画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('エージェント編集（エージェントホスト）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のエージェント: ' . $agent_data['AGENTHOST'] . ' (' . $agent_data['COMMUNITY'] . ')');
		$this->closeList();
		$this->Input('in_ag_hs', 'エージェントホスト', self::$rules['AGENTHOST'], 'user-check', true);
		$this->WarnForm('fm_warn');
		$this->Button('bt_id_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	    case 15: //COMMUNITY
		$this->Button('bt_cm_bk', '編集選択画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('エージェント編集（コミュニティ名）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のエージェント: ' . $agent_data['AGENTHOST'] . ' (' . $agent_data['COMMUNITY'] . ')');
		$this->closeList();
		$this->Input('in_ag_cm', 'コミュニティ名', self::$rules['COMMUNITY'], self::$icons['COMMUNITY'], true);
		$this->WarnForm('fm_warn');
		$this->Button('bt_nm_nx', '次へ', 'submit', 'sign-in-alt');
		break;
	    case 16: //OID
		$mib = $this->getSub();
		$this->Button('bt_oi_bk', '編集選択画面に戻る', 'button', 'chevron-circle-left');
		$this->SubTitle('エージェント編集（MIBサブツリー選択）', '以下の情報をもとに変更を行います。', 'edit');
		$this->openList();
		$this->addList('変更対象のエージェント: ' . $agent_data['AGENTHOST'] . ' (' . $agent_data['COMMUNITY'] . ')');
		$this->closeList();
		$this->Caption($mib . $mib_select);
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
     * @param array $agentdata エージェントデータ（AgentDataで取得したデータで、ユーザ情報を取得したもの）
     * @return string 引数をもとにページデータをHTMLの文字列で返します
     */
    public function getDelete($agentdata) {
	$this->Button('bt_dl_bk', 'エージェント選択画面に戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('エージェント削除', '以下のエージェントを削除します。', 'trash-alt');
	$this->openList();
	$this->addList('エージェントホスト: ' . $agentdata['AGENTHOST']);
	$this->addList('コミュニティ名: ' . $agentdata['COMMUNITY']);
	$this->closeList();
	$this->Button('bt_dl_sb', '削除する', 'button', 'sign-in-alt');
	return $this->Export();
    }

    /**
     * [GET] 更新確認画面作成
     * 
     * 入力〜認証までの段階をクリアした場合、入力された情報をもう一度確認する画面を作成します
     * 
     * @param string $confirm_data AgentSetで認証まで確認されたデータを指定します
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
	$fm_fl = fm_fl('fm_fl', 'エラーが発生しました。', '以下をご確認ください。');
	$fm_fl->openList();
	$fm_fl->addList('データベースとの接続をご確認ください。');
	$fm_fl->addList('要求しているデータと実際のデータを比べ、記述や内容が正しいかどうかをご確認ください。');
	$fm_fl->addList('アカウント認証であるセッションが切れていると思われます。もう一度ログインし直してから再試行してください。');
	$fm_fl->addList('【アクセスログ】<br>' . $log);
	$fm_fl->closeList();
	$fm_fl->Button('bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt');
	return $fm_fl->Export();
    }

    public function getCorrect() {
	$this->SubTitle('更新に成功しました！', 'ボタンを押して変更が反映したか確認しましょう！', 'check-square');
	$this->Button('bt_cs_bk', 'エージェント選択画面に戻る', 'button', 'chevron-circle-left');
	return $this->Export();
    }
    
    private function getSub() {
	$this->FormTitle('MIBサブツリー選択', self::$icons['MIB']);
	$this->openList();
	$this->addList('MIBサブツリーを、以下から1つ以上選択します。');
	$this->addList('「全てを選択」にチェックがついていない状態で押すと、列挙されているすべてのサブツリーを選択できます。');
	$this->addList('ついている状態で押すと、全て解除されます。');
	$this->closeList();
	$res = $this->Export(false);
	$this->reset('fm_pg');
	return $res;
    }
}
