<?php

include_once __DIR__ . '/../general/page.php';

class WarnPage extends Page {

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
     * [SET] WARNページデータ取得
     * 
     * レスポンスコードより識別される情報でページの分岐を行います<br>
     * 継承により分岐の拡張ができます
     */
    public function setPageFunc() {
	switch ($this->response_code) {
	    case 0: $this->getWarnResult();
		break;
	    case 1: $this->getSubResult();
		break;
	    case 2: $this->getFailWarn();
		break;
	    default: $this->getFail();
		break;
	}
    }

    /**
     * [SET] 警告結果画面取得
     * 
     * 警告ログデータの取得に成功した場合、その結果ページのトップページを出力します
     */
    public function setWarnResult() {
	$this->Button('bt_rs_bk', 'ホームに戻る', 'button', 'home');
	$this->SubTitle('警告ログ表示結果', '取得情報を以下に参照します。', 'exclamation-triangle');
	$this->openList();
	$this->openListElem('取得情報');
	$this->addList('取得日時: ' . $this->response_data['DATE']);
	$this->addList('ログ総数: ' . $this->response_data['COUNT']);
	$this->closeListElem();
	$this->closeList();
	$this->Button('bt_rs_rt', '最新の状態に更新する', 'button', 'sync-alt');
	$this->OpenCaption();
	$this->SubTitle('トラップ情報', 'ログは日別に管理されています。', 'calendar-week');
	$this->setHTML($this->response_data['LIST']);
	$this->CloseCaption();
	$this->OpenCaption();
	$this->SubTitle('結果CSVをダウンロード', '取得した情報を、コンマ区切りのCSVファイル（SJIS-winフォーマット）で出力することができます。<br>（※）OSによってはダウンロードできない場合があります。', 'file-csv');
	$this->Button('bt_rs_dl', 'ダウンロード', 'button', 'download');
	$this->CloseCaption();
    }

    /**
     * [SET] 警告結果（日別表示）設定
     * 
     * 日別ログの要求が合った場合に、その要求に応じた結果ログを設定します
     */
    public function setSubResult() {
	$this->Button('bt_sb_bk', '結果画面に戻る', 'button', 'chevron-circle-left');
	$this->Title('WARN - データ取得結果', 'poll-h');
	$this->openList();
	$this->openListElem('取得情報');
	$this->addList('取得日時: ' . $this->response_data['ID']);
	$this->addList('ログ総数: ' . $this->response_data['COUNT']);
	$this->closeListElem();
	$this->closeList();
	$this->OpenCaption();
	$this->SubTitle('警告データ', '以下は、ログより解釈された警告データ一覧です。', 'exclamation-triangle');
	$this->setHTML($this->response_data['TABLE']);
	$this->CloseCaption();
	$this->Button('bt_sb_bk', '結果画面に戻る', 'button', 'chevron-circle-left');
    }

    /**
     * [SET] WARNエラー設定
     * 
     * WARNによるデータ処理中にエラーが発生した場合はこのエラーページを設定します
     */
    protected function getFailWarn() {
	$this->response_data = !$this->response_data || is_array($this->response_data) ? '【ログの取得に失敗しました】' : $this->response_data;
	$logs = [
	    'ログの解析中にエラーが発生しました。',
	    '実際のログは以下に記載されているので、これを報告してください。',
	    $this->response_data
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'SNMPWALKエラーが発生しました');
    }

}
