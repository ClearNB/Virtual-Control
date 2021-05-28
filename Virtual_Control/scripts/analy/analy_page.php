<?php

include_once __DIR__ . '/../general/page.php';

class AnalyPage extends Page {

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
     * [SET] ANALYページデータ取得設定
     * 
     * レスポンスコードより識別される情報でページの分岐を行います<br>
     * 継承により分岐の拡張ができます
     */
    public function setPageFunc() {
	switch($this->response_code) {
	    case 0: //AGENT_SELECT
		$this->setAgentSelect();
		break;
	    case 1: //RESULT
		$this->setWalkResult();
		break;
	    case 2: //RESULT (SUB)
		$this->setSubResult();
		break;
	    case 3: //FAIL (WALK)
		$this->setFailWalk();
		break;
	    default: //FAIL (DEFAULT)
		$this->setFail();
		break;
	}
    }

    /**
     * [SET] エージェント選択画面設定
     * 
     * エージェント選択画面を設定します<br>
     * レスポンスデータにエージェント選択HTMLが必要です
     */
    private function setAgentSelect() {
	$this->Button('bt_sl_bk', 'ホームに戻る', 'button', 'home');
	$this->SubTitle('エージェント選択', '以下からエージェントを選択してください。', 'server');
	$this->Caption($this->response_data);
	$this->Button('bt_sl_sb', '選択して情報を取得する', 'submit', 'home', true);
	if (session_per_chk()) {
	    $this->Button('bt_sl_st', 'エージェント設定', 'button', 'wrench');
	}
    }

    /**
     * [SET] SNMPWALK 取得結果画面設定
     * 
     * SNMPWALKの取得結果を設定します<br>
     * レスポンスデータにSNMPWALKの取得結果を指定する必要があります
     */
    private function setWalkResult() {
	$this->Button('bt_rt_bk', 'エージェント選択に戻る', 'button', 'caret-square-left');
	$this->SubTitle('ANALY', '取得情報を以下に参照します。', 'poll');
	$this->openList();
	$this->openListElem('全体情報');
	$this->addList('取得日時: ' . $this->response_data['DATE']);
	$this->addList('ホストアドレス: ' . $this->response_data['HOST']);
	$this->addList('コミュニティ名: ' . $this->response_data['COM']);
	$this->addList('データ数: ' . $this->response_data['SIZE']);
	$this->closeListElem();
	$this->closeList();
	$this->Button('bt_rt_rf', '更新する', 'button', 'sync-alt');
	$this->OpenCaption();
	$this->SubTitle('取得SNMP情報', '以下のサブツリーから取得してください。', 'object-group');
	$this->setHTML($this->response_data['LIST']);
	$this->CloseCaption();
	$this->SubTitle('結果CSVをダウンロード', '取得した情報を、コンマ区切りのCSVファイル（SJIS-winフォーマット）で出力することができます。<br>（※）OSによってはダウンロードできない場合があります。', 'file-csv');
	$this->Button('bt_rt_dl', 'ダウンロード', 'button', 'download');
    }

    /**
     * [SET] サブデータ
     */
    private function setSubResult() {
	$sub_data = $this->response_data['SUBDATA']['SELECT'];
	$this->Button('bt_sb_bk', '結果画面に戻る', 'button', 'chevron-circle-left');
	$this->Title('ANALY - データ取得結果', 'poll-h');
	$this->openList();
	$this->openListElem('取得情報');
	$this->addList('エージェントホスト: ' . $this->response_data['HOST']);
	$this->addList('コミュニティ情報: ' . $this->response_data['COM']);
	$this->addList('対象MIB: ' . $sub_data['MIB']);
	$this->addList('データ総数: ' . $sub_data['SIZE']);
	$this->addList('取得日時: ' . $sub_data['DATE']);
	$this->closeListElem();
	$this->closeList();
	$this->OpenCaption();
	$this->SubTitle('SNMPデータ', '以下は、データベースのMIB情報より認識した情報の一覧です。', 'object-group');
	$this->setHTML($sub_data['TABLE']);
	$this->CloseCaption();
	$this->SubTitle('SNMPデータエラー', 'MIBとの紐付けを行っている際に起こったエラーはここに表示されます。', 'exclamation-circle');
	$this->ListCreate($sub_data['ERROR']);
	$this->Button('bt_sb_bk', '結果画面に戻る', 'button', 'chevron-circle-left');
    }
    
    /**
     * [SET] WARNエラー設定
     * 
     * WARNによるデータ処理中にエラーが発生した場合はこのエラーページを設定します
     */
    private function setFailWalk() {
	$this->response_data = !$this->response_data || is_array($this->response_data) ? '【ログの取得に失敗しました】' : $this->response_data;
	$logs = [
	    'ログの解析中にエラーが発生しました。',
	    '実際のログは以下に記載されているので、これを報告してください。',
	    $this->response_data
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'SNMPWALKエラーが発生しました');
    }
}
