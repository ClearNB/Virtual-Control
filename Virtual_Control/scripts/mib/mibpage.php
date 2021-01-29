<?php

include_once __DIR__ . '/../general/former.php';

class MIBPage extends form_generator {

    private static $ruleData = [
	'GROUP_OID' => '【判定条件】半角数字（0〜9）と記号（.）を用いて255文字まで, グループOIDは一意で、配下に依存していない必要があります<br>【例】1.3.6.1.2.1',
	'GROUP_NAME' => '【判定条件】半角英数字（a〜z, A〜Z, 0〜9）50文字まで',
	'SUB_OID' => '【判定条件】半角数字（0〜9）のみで32ビット整数まで, グループOIDに続く一意の数字を指定する必要があります。<br>【例】1.3.6.1.2.1 + 1',
	'SUB_NAME' => '【判定条件】半角英数字（a〜z, A〜Z, 0〜9）50文字まで, サブツリー名はANALYにて表示されます。',
	'NODE_OID' => '【判定条件】半角数字のみ, グループOID + サブツリーOIDに続く1つの数字を指定する必要があります。<br>【例】1.3.6.1.2.1.1 + 1',
	'NODE_SUB' => '【判定条件】OIDサブ情報, 半角数字（0〜9）と記号（.）を用いて11文字まで, ノードOID指定より交尾で追加可能な追加OID情報を指定します（先頭に . を付ける必要はありません）<br>【例】1.3.6.1.2.1.1 + 1 + 1',
    ];
    private static $iconData = [
	'GROUP_OID' => 'id-badge',
	'GROUP_NAME' => 'sliders-h',
	'SUB_OID' => 'id-badge',
	'SUB_NAME' => 'sliders-h'
    ];

    public function __construct() {
	parent::__construct('fm_pg');
    }

    public function get_mibpage_bycode($response_code, $response_data) {
	$res_page = '';
	switch ($response_code) {
	    //GENERAL
	    case 0: $res_page = $this->getCorrect();
		break;       //COMPLETE
	    case 1: $res_page = $this->getError($response_data);
		break;     //ERROR (GENERAL)
	    case 2: $res_page = $this->getQueryError();
		break;     //ERROR (QUERY)
	    case 3: case 5: $res_page = $response_data;
		break;     //ERROR (INPUT, AUTH)
	    case 4: $res_page = $this->getAuth();
		break;     //AUTH
	    case 6: $res_page = $this->getConfirm($response_data);
		break;     //CONFIRM
	    //GROUP
	    case 10: $res_page = $this->getGroupSelect($response_data);
		break;     //SELECT
	    case 11: $res_page = $this->getGroupCreate();
		break;      //CREATE
	    case 12: $res_page = $this->getGroupEditSelect($response_data);
		break;     //EDIT SELECT
	    case 13: $res_page = $this->getGroupEditOID($response_data);
		break;     //EDIT OID
	    case 14: $res_page = $this->getGroupEditName($response_data);
		break;     //EDIT NAME
	    case 15: $res_page = $this->getGroupDelete($response_data);
		break;     //DELETE
	    //SUB
	    case 20: $res_page = $this->getSubSelect($response_data);
		break;     //SELECT
	    case 21: $res_page = $this->getSubCreate($response_data);
		break;     //CREATE
	    case 22: $res_page = $this->getSubEditSelect($response_data);
		break;     //EDIT SELECT
	    case 23: $res_page = $this->getSubEditOID($response_data);
		break;     //EDIT OID
	    case 24: $res_page = $this->getSubEditName($response_data);
		break;     //EDIT NAME
	    case 25: $res_page = $this->getSubDelete($response_data);
		break;     //DELETE
	    //NODE
	    case 30:
		break;     //EDIT
	    case 31:
		break;     //EDIT ADD & EDIT (EMPTY)
	    case 32:
		break;     //EDIT ADD & EDIT (ADD AS NORMAL)
	    case 33:
		break;     //EDIT ADD & EDIT (ADD AS TABLE)
	    case 34:
		break;     //EDIT ADD & EDIT (ADD AS TABLE DATA)
	    case 35:
		break;     //EDIT ADD & EDIT (ICON SELECT)
	    case 36:
		break;     //EDIT DELETE
	    default: $res_page = $this->getError();
		break;
	}
	$this->reset();
	return $res_page;
    }

    public function getCorrect() {
	$this->SubTitle('更新に成功しました！', 'ボタンを押して変更が反映したか確認しましょう！', 'check-square');
	$this->Button('bt_cs_bk', '戻る', 'button', 'chevron-circle-left');
	return $this->Export();
    }

    public function getError($log = '〈ログなし〉') {
	$logs = [
	    'データベースとの接続をご確認ください。',
	    '要求しているデータと実際のデータを比べ、記述や内容が正しいかどうかをご確認ください。',
	    'アカウントセッションが切れていると思われます。もう一度ログインし直してから再試行してください。',
	    $log
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'データベースエラー');
	return $this->Export();
    }

    public function getQueryError() {
	$logs = [
	    '要求しようとした行為は、サーバ側で拒否されました。',
	    'UIで示されている操作以外で操作することは許されていません。',
	    'アカウントセッションが切れていると思われます。もう一度ログインし直してから再試行してください。'
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], '手続きエラー');
	return $this->Export();
    }

    public function getAuth() {
	return $this->fm_at();
    }

    public function getConfirm($confirm_data) {
	$this->SubTitle('入力の確認', '以下の情報をご確認の上、「更新する」ボタンを押してください。<br>なお、「入力に戻る」ボタンを押すと、入力に戻ります。', 'clipboard-check');
	$this->Caption($confirm_data);
	$this->Button('bt_cf_sb', '更新する', 'button', 'sign-in-alt');
	$this->Button('bt_cf_bk', '入力に戻る', 'button', 'chevron-circle-left');
	return $this->Export();
    }

    public function getGroupSelect($mibdata) {
	$this->Button('bt_sl_gr_bk', '設定一覧に戻る', 'button', 'list');
	$this->SubTitle('MIBグループ選択', '以下からMIBグループを選択し、管理を行ってください。', 'mouse');
	if (sizeof($mibdata) == 0) {
	    $this->Caption('【MIBグループはありません】');
	} else {
	    $i = 1;
	    foreach ($mibdata['GROUP'] as $k => $s) {
		$this->Check(1, 'gsel-' . $i, 'request_data_id', $k, '【' . $s['GROUP_OID'] . '】' . $s['GROUP_NAME'] . '（' . $s['GROUP_SUB_COUNT'] . '）', false);
		$i += 1;
	    }
	}
	$this->LargeButton('bt_sl_gr_go', 'サブツリー選択', 'button', 'car-battery', true);
	$this->Button('bt_sl_gr_cr', '作成', 'button', 'plus-square');
	$this->Button('bt_sl_gr_ed', '編集', 'button', 'edit', true);
	$this->Button('bt_sl_gr_dl', '削除', 'button', 'trash-alt', true);
	return $this->Export();
    }

    public function getGroupCreate() {
	$this->Button('bt_po_bk_gr', 'グループ選択に戻る', 'button', 'list');
	$this->SubTitle('グループ作成', '以下の必要事項を入力してください。', 'plus-square');
	$this->Input('in_gp_id', 'グループOID', self::$ruleData['GROUP_OID'], self::$iconData['GROUP_OID'], true);
	$this->Input('in_gp_nm', 'グループ名', self::$ruleData['GROUP_NAME'], self::$iconData['GROUP_NAME'], true);
	$this->WarnForm('fm_warn');
	$this->Button('bt_nt', '次へ', 'submit', 'sign-in-alt');
	return $this->Export();
    }

    public function getGroupEditSelect($mibdata) {
	$select_data = $mibdata['GROUP']['STORE'];
	$this->Button('bt_po_bk_gr', 'グループ選択に戻る', 'button', 'list');
	$this->SubTitle('グループ編集', '以下から編集したい項目を選択してください。', 'edit');
	$this->openList();
	$this->addList('対象グループ: ' . '【' . $select_data['GROUP_OID'] . '】' . $select_data['GROUP_NAME'] . '（' . $select_data['GROUP_SUB_COUNT'] . '）');
	$this->addList('配下対象サブツリー数: ' . $select_data['GROUP_SUB_COUNT']);
	$this->closeList();
	$this->Button('bt_ed_gr_id', 'グループOID', 'button', self::$iconData['GROUP_OID']);
	$this->Button('bt_ed_gr_nm', 'グループ名', 'button', self::$iconData['GROUP_NAME']);
	return $this->Export();
    }

    public function getGroupEditOID($mibdata) {
	$select_data = $mibdata['GROUP']['STORE'];
	$this->Button('bt_ed_bk_gr', '編集選択画面に戻る', 'button', 'list');
	$this->SubTitle('グループ編集', '以下の必須項目を入力してください。', 'edit', false, 'グループOID');
	$this->openList();
	$this->addList('対象グループ: ' . '【' . $select_data['GROUP_OID'] . '】' . $select_data['GROUP_NAME']);
	$this->addList('配下対象サブツリー数: ' . $select_data['GROUP_SUB_COUNT']);
	$this->addList('グループOIDが変更されることにより、配下のサブツリー・ノードのOIDが変更されます。');
	$this->closeList();
	$this->Input('in_gp_id', 'グループOID', self::$ruleData['GROUP_OID'], self::$iconData['GROUP_OID'], true);
	$this->WarnForm('fm_warn');
	$this->Button('bt_nt', '次へ', 'submit', 'sign-in-alt');
	return $this->Export();
    }

    public function getGroupEditName($mibdata) {
	$select_data = $mibdata['GROUP']['STORE'];
	$this->Button('bt_ed_bk_gr', '編集選択画面に戻る', 'button', 'list');
	$this->SubTitle('グループ編集', '以下の必須項目を入力してください。', 'edit', false, 'グループ名');
	$this->openList();
	$this->addList('対象グループ: ' . '【' . $select_data['GROUP_OID'] . '】' . $select_data['GROUP_NAME']);
	$this->addList('配下対象サブツリー数: ' . $select_data['GROUP_SUB_COUNT']);
	$this->addList('この変更による配下データへの影響はありませんが、表示される名前が変更されます。');
	$this->closeList();
	$this->Input('in_gp_nm', 'グループ名', self::$ruleData['GROUP_NAME'], self::$iconData['GROUP_NAME'], true);
	$this->WarnForm('fm_warn');
	$this->Button('bt_nt', '次へ', 'submit', 'sign-in-alt');
	return $this->Export();
    }

    public function getGroupDelete($mibdata) {
	$select_data = $mibdata['GROUP']['STORE'];
	$this->Button('bt_po_bk_gr', 'グループ選択に戻る', 'button', 'list');
	$this->SubTitle('グループ削除', '以下の情報のグループを削除します。', 'trash-alt');
	$this->openList();
	$this->addList('グループOID: ' . $select_data['GROUP_OID']);
	$this->addList('グループ名: ' . $select_data['GROUP_NAME']);
	$this->openListElem('【注意事項】');
	$this->openList();
	$this->addList('<strong>削除を行った場合、元に戻すことはできません！</strong>');
	$this->addList('グループデータを削除することにより、配下のサブツリーデータ・ノードデータも削除されます。');
	$this->addList('配下のサブツリーデータを監視項目としているエージェントにも、そのデータが削除されます。');
	$this->closeList();
	$this->closeListElem();
	$this->closeList();
	$this->Button('bt_dl_nt', '次へ', 'button', 'sign-in-alt');
	return $this->Export();
    }

    public function getSubSelect($mibdata) {
	$group_select = $mibdata['GROUP']['STORE'];
	$group_sub = $mibdata['SUB'][$group_select['GROUP_ID']];
	$this->Button('bt_sl_sb_bk', 'グループ選択に戻る', 'button', 'list');
	$this->CardDark('選択したグループ情報', 'object-ungroup', 'グループOID: ' . $group_select['GROUP_OID'], 'グループ名: ' . $group_select['GROUP_NAME'] . '<br>サブツリー数: ' . $group_select['GROUP_SUB_COUNT']);
	if (sizeof($group_sub) == 0) {
	    $this->Caption('【MIBサブツリーはありません】');
	} else {
	    $i = 1;
	    foreach ($group_sub as $k => $s) {
		$this->Check(1, 'ssel-' . $i, 'request_data_id', $k, '【' . $s['SUB_OID'] . '】' . $s['SUB_NAME'] . '（' . $s['SUB_NODE_COUNT'] . '）', false);
		$i += 1;
	    }
	}
	$this->LargeButton('bt_sl_sb_go', 'ノード編集', 'button', 'edit', true);
	$this->Button('bt_sl_sb_cr', '作成', 'button', 'plus-square');
	$this->Button('bt_sl_sb_ed', '編集', 'button', 'edit', true);
	$this->Button('bt_sl_sb_dl', '削除', 'button', 'trash-alt', true);
	return $this->Export();
    }

    public function getSubCreate($mibdata) {
	$group_select_data = $mibdata['GROUP']['STORE'];
	$this->Button('bt_po_bk_sb', 'サブツリー選択に戻る', 'button', 'list');
	$this->SubTitle('サブツリー編集', '以下から編集したい項目を選択してください。', 'edit');
	$this->openList();
	$this->addList('所属グループ: ' . '【' . $group_select_data['GROUP_OID'] . '】' . $group_select_data['GROUP_NAME'] . '（' . $group_select_data['GROUP_SUB_COUNT'] . '）');
	$this->closeList();
	$this->Button('bt_ed_sb_id', 'サブツリーOID', 'button', self::$iconData['GROUP_OID']);
	$this->Button('bt_ed_sb_nm', 'サブツリー名', 'button', self::$iconData['GROUP_NAME']);
	return $this->Export();
    }

    public function getSubEditSelect($mibdata) {
	$this->Button('bt_po_bk_sb', 'サブツリー選択に戻る', 'button', 'list');
	$this->SubTitle('サブツリー編集', '以下から編集したい項目を選択してください。', 'edit');
	$this->openList();
	$this->addList('所属グループ: ' . '【' . $mibdata['GROUP']['GROUP_OID'] . '】' . $mibdata['GROUP']['GROUP_NAME'] . '（' . $mibdata['GROUP']['GROUP_SUB_COUNT'] . '）');
	$this->addList('サブツリーOID: ' . $mibdata['SUB']['SUB_OID']);
	$this->addList('サブツリー名: ' . $mibdata['SUB']['SUB_NAME']);
	$this->closeList();
	$this->Button('bt_ed_sb_id', 'サブツリーOID', 'button', self::$iconData['GROUP_OID']);
	$this->Button('bt_ed_sb_nm', 'サブツリー名', 'button', self::$iconData['GROUP_NAME']);
    }

    public function getSubEditOID($mibdata) {
	$this->Button('bt_po_bk_sb', 'サブツリー選択に戻る', 'button', 'list');
	$this->SubTitle('サブツリー編集', '以下から編集したい項目を選択してください。', 'edit');
	$this->openList();
	$this->addList('所属グループ: ' . '【' . $mibdata['GROUP']['STORE']['GROUP_OID'] . '】' . $mibdata['GROUP']['STORE']['GROUP_NAME']);
	$this->addList('対象サブツリー' . '【' . $mibdata['SUB']['STORE']['SUB_OID'] . '】' . $mibdata['SUB']['STORE']['SUB_NAME']);
	$this->addList('配下ノード数' . $mibdata['SUB']['STORE']['SUB_NODE_COUNT']);
	$this->closeList();
	$this->Button('bt_ed_sb_id', 'サブツリーOID', 'button', self::$iconData['GROUP_OID']);
	$this->Button('bt_ed_sb_nm', 'サブツリー名', 'button', self::$iconData['GROUP_NAME']);
    }

    public function getSubEditName() {
	
    }

    public function getSubDelete() {
	
    }

    public function getNodeEditTop() {
	
    }

    public function getNodeEditForm() {
	
    }

    public function getNodeEditIconSelect() {
	
    }

}
