<?php

include_once __DIR__ . '/../general/former.php';

class AnalyPage extends form_generator {

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
     * [GET] エージェントページ取得
     * 
     * @param string $agent AgentSelectで取得したエージェントリストを指定します
     * @return string 画面構成のHTMLを返します
     */
    public function getAgentSelect($agent): string {
	$this->Button('bt_sl_bk', 'ホームに戻る', 'button', 'home');
	$this->SubTitle('エージェント選択', '以下からエージェントを選択してください。', 'server');
	$this->Caption($agent);
	$this->Button('bt_sl_sb', '選択して情報を取得する', 'submit', 'home', true);
	if (session_per_chk()) {
	    $this->Button('bt_sl_st', 'エージェント設定', 'button', 'wrench');
	}
	return $this->Export();
    }

    /**
     * [GET] SNMPWALK 取得結果画面取得
     * 
     * SNMPWALKの取得結果を表示します。
     * MIBとの紐付けの結果も表示されるように、エラーがあった際の表示も行います。
     * 
     * @param string $date 全体の取得日時を指定します
     * @param string $host ホストアドレスを指定します
     * @param string $community コミュニティ名を指定します
     * @param string $list サブリストの一覧を指定します
     * @return string 画面構成のHTMLを返します
     */
    public function getWalkResult($date, $host, $community, $list, $size): string {
	$this->Button('bt_rt_bk', 'エージェント選択に戻る', 'button', 'caret-square-left');
	$this->SubTitle('ANALY', '取得情報を以下に参照します。', 'poll');
	$this->openList();
	$this->openListElem('全体情報');
	$this->addList('取得日時: ' . $date);
	$this->addList('ホストアドレス: ' . $host);
	$this->addList('コミュニティ名: ' . $community);
	$this->addList('データ数: ' . $size);
	$this->closeListElem();
	$this->closeList();
	$this->Button('bt_rt_rf', '更新する', 'button', 'sync-alt');
	$this->OpenCaption();
	$this->SubTitle('取得SNMP情報', '以下のサブツリーから取得してください。', 'object-group');
	$this->setHTML($list);
	$this->CloseCaption();
	$this->SubTitle('結果CSVをダウンロード', '取得した情報を、コンマ区切りのCSVファイル（SJIS-winフォーマット）で出力することができます。<br>（※）OSによってはダウンロードできない場合があります。', 'file-csv');
	$this->Button('bt_rt_dl', 'ダウンロード', 'button', 'download');
	return $this->Export();
    }

    public function getSubResult($agenthost, $community, $mibinfo, $date, $table, $error, $size) {
	$this->Button('bt_sb_bk', '結果画面に戻る', 'button', 'chevron-circle-left');
	$this->Title('ANALY - データ取得結果', 'poll-h');
	$this->openList();
	$this->openListElem('取得情報');
	$this->addList('エージェントホスト: ' . $agenthost);
	$this->addList('コミュニティ情報: ' . $community);
	$this->addList('対象MIB: ' . $mibinfo);
	$this->addList('データ総数: ' . $size);
	$this->addList('取得日時: ' . $date);
	$this->closeListElem();
	$this->closeList();
	$this->OpenCaption();
	$this->SubTitle('SNMPデータ', '以下は、データベースのMIB情報より認識した情報の一覧です。', 'object-group');
	$this->setHTML($table);
	$this->CloseCaption();
	$this->SubTitle('SNMPデータエラー', 'MIBとの紐付けを行っている際に起こったエラーはここに表示されます。', 'exclamation-circle');
	$this->ListCreate($error);
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
    
    public function getFailSNMPWALK($log = '〈形式ログはなし〉') {
	$logs = [
	    'SNMPWALKまたはデータの解析中にエラーが発生しました。',
	    '実際のログは以下に記載されているので、これを報告してください。',
	    $log
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'SNMPWALKエラーが発生しました');
	return $this->Export();
    }
}
