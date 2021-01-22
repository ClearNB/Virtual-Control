<?php

include_once __DIR__ . '/../general/former.php';

class MIBPage extends form_generator {

    private static $ruleData = [
	'GROUP_OID' => '【判定条件】数字と記号を用いて255文字まで',
	'GROUP_NAME' => '【判定条件】半角英数字25文字まで',
	'SUB_OID' => '【判定条件】半角数字のみ',
	'SUB_NAME' => '【判定条件】半角英数字50文字まで'
    ];
    private static $iconData = [
	'GROUP_OID' => 'id-badge',
	'GROUP_NAME' => 'sliders-h',
	'SUB_OID' => 'id-badge',
	'SUB_NAME' => 'sliders-h'
    ];

    public function __construct() {
	parent::__construct("fm_pg");
    }

    public function getGroupSelect($mibdata) {
	$this->Button('bt_sl_gr_bk', '設定一覧に戻る', 'button', 'list');
	$this->SubTitle('MIBグループ選択', '以下からMIBグループを選択し、管理を行ってください。', 'mouse');
	if (sizeof($mibdata) == 0) {
	    $this->Caption('【MIBグループはありません】');
	} else {
	    $i = 1;
	    foreach ($mibdata as $k => $s) {
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
	$this->Input('in_gp_id', 'グループOID', self::$ruleData['GROUP_OID'], self::$iconData['GROUP_OID']);
	$this->Input('in_gp_nm', 'グループ名', self::$ruleData['GROUP_NAME'], self::$iconData['GROUP_NAME']);
	$this->Button('bt_nt', '次へ', 'submit', 'sign-in-alt');
	return $this->Export();
    }

    public function getGroupEditSelect($mibdata) {
	$this->Button('bt_po_bk_gr', 'グループ選択に戻る', 'button', 'list');
	$this->SubTitle('グループ編集', '以下から編集したい項目を選択してください。', 'edit');
	$this->openList();
	$this->addList('対象グループ: ' . '【' . $mibdata['GROUP_OID'] . '】' . $mibdata['GROUP_NAME'] . '（' . $mibdata['GROUP_SUB_COUNT'] . '）');
	$this->addList('配下対象サブツリー数: ' . $mibdata['GROUP_SUB_COUNT']);
	$this->closeList();
	$this->Button('bt_ed_gr_id', 'グループOID', 'button', self::$iconData['GROUP_OID']);
	$this->Button('bt_ed_gr_nm', 'グループ名', 'button', self::$iconData['GROUP_NAME']);
	return $this->Export();
    }

    public function getGroupEditOID($mibdata) {
	$this->Button('bt_ed_bk_gr', '編集選択画面に戻る', 'button', 'list');
	$this->SubTitle('グループ編集', '以下の必須項目を入力してください。', 'edit', false, 'グループOID');
	$this->openList();
	$this->addList('対象グループ: ' . '【' . $mibdata['GROUP_OID'] . '】' . $mibdata['GROUP_NAME'] . '（' . $mibdata['GROUP_SUB_COUNT'] . '）');
	$this->addList('配下対象サブツリー数: ' . $mibdata['GROUP_SUB_COUNT']);
	$this->addList('グループOIDが変更されることにより、配下のサブツリー・ノードのOIDが変更されます。');
	$this->closeList();
	$this->Input('in_gp_id', 'グループOID', self::$ruleData['GROUP_OID'], self::$iconData['GROUP_OID']);
	$this->Button('bt_nt', '次へ', 'submit', 'sign-in-alt');
	return $this->Export();
    }

    public function getGroupEditName($mibdata) {
	$this->Button('bt_ed_bk_gr', '編集選択画面に戻る', 'button', 'list');
	$this->SubTitle('グループ編集', '以下の必須項目を入力してください。', 'edit', false, 'グループ名');
	$this->openList();
	$this->addList('対象グループ: ' . '【' . $mibdata['GROUP_OID'] . '】' . $mibdata['GROUP_NAME'] . '（' . $mibdata['GROUP_SUB_COUNT'] . '）');
	$this->addList('配下対象サブツリー数: ' . $mibdata['GROUP_SUB_COUNT']);
	$this->addList('この変更による配下データへの影響はありませんが、表示される名前が変更されます。');
	$this->closeList();
	$this->Input('in_gp_nm', 'グループ名', self::$ruleData['GROUP_NAME'], self::$iconData['GROUP_NAME']);
	$this->Button('bt_nt', '次へ', 'submit', 'sign-in-alt');
	return $this->Export();
    }

    public function getGroupDelete($mibdata) {
	$this->Button('bt_po_bk_gr', 'グループ選択に戻る', 'button', 'list');
	$this->SubTitle('グループ削除', '以下の情報のグループを削除します。', 'trash-alt');
	$this->openList();
	$this->addList('グループOID: ' . $mibdata['GROUP_OID']);
	$this->addList('グループ名: ' . $mibdata['GROUP_NAME']);
	$this->addList('グループデータを削除することにより、配下のサブツリーデータ・ノードデータも削除されます。');
	$this->addList('また、配下のサブツリーデータを監視項目としているエージェントにも、そのデータが削除されます。');
	$this->addList('【重要】削除を行った場合、元に戻すことはできません！');
	$this->closeList();
	$this->Button('bt_dl_nt', '次へ', 'button', 'sign-in-alt');
	return $this->Export();
    }

    public function getSubSelect($mibdata) {
	$this->Button('bt_sl_sb_bk', 'グループ選択に戻る', 'button', 'list');
	$this->CardDark('選択したグループ情報', 'object-ungroup', 'グループOID: ' . $mibdata['GROUP']['GROUP_OID'], 'グループ名: ' . $mibdata['GROUP']['GROUP_NAME'] . '<br>サブツリー数: ' . $mibdata['GROUP']['GROUP_SUB_COUNT']);
	if (sizeof($this->sel_data['SUB']) == 0) {
	    $this->Caption('【MIBサブツリーはありません】');
	} else {
	    $i = 1;
	    foreach ($mibdata['SUB'] as $k => $s) {
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

    public function getSubCreate() {
	
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
	$this->addList('所属グループ: ' . '【' . $mibdata['GROUP']['GROUP_OID'] . '】' . $mibdata['GROUP']['GROUP_NAME'] . '（' . $mibdata['GROUP']['GROUP_SUB_COUNT'] . '）');
	$this->addList('対象サブツリー' . $mibdata['GROUP']['GROUP_OID']);
	$this->addList('配下ノード数' . $mibdata['GROUP']['GROUP_NAME']);
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

    public function getAuth() {
	return fm_at();
    }

    public function getConfirm() {
	
    }

    public function getError() {
	$fm_fl = fm_fl('fm_fl', 'エラーが発生しました。', '以下をご確認ください。');
	$fm_fl->openList();
	$fm_fl->addList('データベースとの接続をご確認ください。');
	$fm_fl->addList('要求しているデータと実際のデータを比べ、記述や内容が正しいかどうかをご確認ください。');
	$fm_fl->addList('アカウントセッションが切れていると思われます。もう一度ログインし直してから再試行してください。');
	$fm_fl->closeList();
	$fm_fl->Button('bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt');
	return $fm_fl->Export();
    }

}
