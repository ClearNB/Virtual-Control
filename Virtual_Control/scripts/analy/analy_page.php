<?php

include __DIR__ . '/../general/former.php';

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
	$this->addList('取得日時: ' . $date);
	$this->addList('ホストアドレス: ' . $host);
	$this->addList('コミュニティ名: ' . $community);
	$this->addList('データ数: ' . $size);
	$this->closeList();
	$this->Button('bt_rt_rf', '更新する', 'button', 'sync-alt');
	$this->SubTitle('取得SNMP情報', '以下のサブツリーから取得してください。', 'object-group');
	$this->Caption($list, false, 2);
	$this->SubTitle('結果CSVをダウンロード', '取得した情報を、コンマ区切りのCSVファイル（SJIS-winフォーマット）で出力することができます。<br>（※）OSによってはダウンロードできない場合があります。', 'file-csv');
	$this->Button('bt_rt_dl', 'ダウンロード', 'button', 'download');
	return $this->Export();
    }

    public function getSubResult($agenthost, $community, $mibinfo, $date, $table, $error, $size) {
	$this->Button('bt_sb_bk', '結果画面に戻る', 'button', 'chevron-circle-left');
	$this->Title('ANALY - データ取得結果', 'poll-h');
	$this->openList();
	$this->addList('エージェントホスト: ' . $agenthost);
	$this->addList('コミュニティ情報: ' . $community);
	$this->addList('対象MIB: ' . $mibinfo);
	$this->addList('データ総数: ' . $size);
	$this->addList('取得日時: ' . $date);
	$this->closeList();
	$this->Horizonal();
	$this->SubTitle('取得情報', '以下は、データベースのMIB情報より認識した情報の一覧です。', 'object-group');
	$this->Caption($table);
	$this->Horizonal();
	$this->SubTitle('取得エラー一覧', 'MIBとの紐付けを行っている際に起こったエラーはここに表示されます。', 'exclamation-circle');
	$this->ListCreate($error);
	$this->Button('bt_sb_bk', '結果画面に戻る', 'button', 'chevron-circle-left');
	return $this->Export();
    }

    public function getFail($log = '〈形式ログはなし〉') {
	$f = fm_fl('fm_fl', '手続きエラーが発生しました', '要求された処理は実行できません。以下をご確認ください。');
	$f->openList();
	$f->addList('不正な手続きとして認識し、これ以上の処理を中断した可能性があります。');
	$f->addList('正しい操作にも関わらずこの画面が現れる場合、現在あなたはセッション情報を破棄された可能性があります。');
	$f->addList($log);
	$f->closeList();
	$f->Button('bt_fl_rf', 'ページを再読込する', 'button', 'chevron-circle-left');
	return $f->Export();
    }
    
    public function getFailSNMPWALK($log = '〈形式ログはなし〉') {
	$f = fm_fl('fm_fl', 'SNMPWALKの処理中にが発生しました', '要求された処理は実行できません。以下をご確認ください。');
	$f->openList();
	$f->addList('手続き先のデータベースサーバに接続・または操作ができないため、これ以上の動作ができなくなった可能性があります。');
	$f->addList($log);
	$f->closeList();
	$f->Button('bt_fl_rf', 'ページを再読込する', 'button', 'chevron-circle-left');
	return $f->Export();
    }
}
