<?php

include_once __DIR__ . '/../general/former.php';

class WarnPage extends form_generator {

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです<br>
     * Formerのコンストラクタも含まれます
     * 
     * @param string $id ページのIDです（Default: 'fm_pg'）
     */
    public function __construct($id = 'fm_pg') {
	parent::__construct($id);
    }
    
    /**
     * [GET] WARN画面取得
     * 
     * @param int $code ページコードを指定します（0..RESULT, 1..SUB, 2..FAIL(WARN), OTHER..FAIL(OTHER)）
     * @param string $data ページデータを指定します
     * @return string ページ情報のHTMLを文字列で返します
     */
    public function get_page_byid($code, $data) {
	$page = '';
	switch($code) {
	    case 0: //RESULT
		$page = $this->getWarnResult($data);
		break;
	    case 1: //SUB
		$page = $this->getSubResult($data);
		break;
	    case 2: //FAIL (WARN)
		$page = $this->getFailWarn($data);
		break;
	    default: //FAIL (OTHER)
		$page = $this->getFail($data);
		break;
	}
	$this->reset();
	return $page;
    }

    public function getWarnResult($warndata): string {
	$this->Button('bt_rs_bk', 'ホームに戻る', 'button', 'home');
	$this->SubTitle('警告ログ表示結果', '取得情報を以下に参照します。', 'exclamation-triangle');
	$this->openList();
	$this->openListElem('取得情報');
	$this->addList('取得日時: ' . $warndata['DATE']);
	$this->addList('ログ総数: ' . $warndata['COUNT']);
	$this->closeListElem();
	$this->closeList();
	$this->Button('bt_rs_rt', '最新の状態に更新する', 'button', 'sync-alt');
	$this->OpenCaption();
	$this->SubTitle('トラップ情報', 'ログは日別に管理されています。', 'calendar-week');
	$this->setHTML($warndata['LIST']);
	$this->CloseCaption();
	$this->OpenCaption();
	$this->SubTitle('結果CSVをダウンロード', '取得した情報を、コンマ区切りのCSVファイル（SJIS-winフォーマット）で出力することができます。<br>（※）OSによってはダウンロードできない場合があります。', 'file-csv');
	$this->Button('bt_rs_dl', 'ダウンロード', 'button', 'download');
	$this->CloseCaption();
	return $this->Export();
    }

    public function getSubResult($warndata) {
	$this->Button('bt_sb_bk', '結果画面に戻る', 'button', 'chevron-circle-left');
	$this->Title('ANALY - データ取得結果', 'poll-h');
	$this->openList();
	$this->openListElem('取得情報');
	$this->addList('ログ総数: ' . $warndata['COUNT']);
	$this->closeListElem();
	$this->closeList();
	$this->OpenCaption();
	$this->SubTitle('警告データ', '以下は、ログより解釈された警告データ一覧です。', 'exclamation-triangle');
	$this->setHTML($warndata['TABLE']);
	$this->CloseCaption();
	$this->Button('bt_sb_bk', '結果画面に戻る', 'button', 'chevron-circle-left');
	return $this->Export();
    }

    public function getFail($log = '〈ログなし〉') {
	$logs = [
	    'データベースとの接続をご確認ください。',
	    '要求しているデータと実際のデータを比べ、記述や内容が正しいかどうかをご確認ください。',
	    'アカウントセッションが切れていると思われます。もう一度ログインし直してから再試行してください。',
	    $log
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'エラーが発生しました');
	return $this->Export();
    }

    public function getFailWarn($log = '〈形式ログはなし〉') {
	$logs = [
	    'ログの解析中にエラーが発生しました。',
	    '実際のログは以下に記載されているので、これを報告してください。',
	    $log
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'SNMPWALKエラーが発生しました');
	return $this->Export();
    }

}
