<?php

include __DIR__ . '/../general/former.php';

class LinksPage extends form_generator {

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです<br>
     * Formerのコンストラクタも含まれます
     * 
     * @param string $id ページのIDです（Default: 'fm_pg'）
     */
    public function __construct() {
	parent::__construct('fm_pg');
    }

    public function getpage_bycode($response_code) {
	$res_page = '';

	switch ($response_code) {
	    case 61: //INDEX
		$res_page = $this->getIndex();
		break;
	    case 62: //DASH
		$res_page = $this->getDash();
		break;
	    case 63: //INIT
		$res_page = $this->getInit();
		break;
	    case 64: //OPTION
		$res_page = $this->getOption();
		break;
	    default:
		$res_page = $this->getFail();
		break;
	}
	$this->reset();
	return $res_page;
    }

    public function getIndex() {
	$this->removeBack();
	$this->BackGround(1);
	$this->SubTitle('革命的な監視を', 'Virtual Control は、SNMPを利用したネットワークアクセス監視を実現できる監視ツールです。<br><br>アクセス監視は運用・保守の専門職を問わず、誰でも監視できる環境を整えなければならない時代に差し掛かっています。その状況の中で、私たちは「標準化」を目的に、Webアプリケーションで監視が可能なアプリケーションを開発しました。', 'server');
	$this->BackGround(0, true);
	$this->SubTitle('使いやすく、そしてわかりやすく', 'Virtual Control は、できるだけ利用しやすい環境として HTML5 (+ CSS, JavaScript), PHP の2言語を使用しております。', 'users');
	$this->Button('bt_go_gh', 'GitHubを開く', 'button', 'fab fa-github-square');
	$this->BackGround(1, true);
	$this->SubTitle('もっと気軽に', '参照しなければ何の項目かわからないOIDを、標準MIBに準拠した日本語メッセージを搭載！安心して気になった項目を監視できます。', 'laptop-code', 'カスタマイズ');
	$this->BackGround(0, true);
	$this->SubTitle('ログインが必要です', 'ここから先はログインユーザのみ閲覧できる情報です。<br>ログインして、監視を行いましょう。', 'user-check', 'WARNING');
	$this->Button('bt_go_lg', 'ログインする', 'button', 'sign-in-alt');
	return $this->Export();
    }

    public function getDash() {
	$getdata = session_get_userdata();
	if ($getdata) {
	    $this->SubTitle($getdata['USERNAME'] . 'さん', 'アクセス監視をしましょう。行動を選択してください。', 'user', false, $getdata['PERMISSION_TEXT']);
	    $this->openListGroup();
	    $this->addListGroup('analy', 'アナリティクス', 'chart-pie', 'アクセス状況をエージェント別に監視します', '詳しくはクリック！');
	    $this->addListGroup('warn', '警告情報', 'file-excel', '日別に発生したトラップログを表示します', '詳しくはクリック！');
	    if ($getdata['PERMISSION'] == 0) {
		$this->addListGroup('option', 'オプション', 'wrench', '監視のための設定を行います', '詳しくはクリック！');
	    }
	    $this->closeListGroup();
	    return $this->Export();
	} else {
	    return $this->getFail();
	}
    }

    public function getInit() {
	
    }

    public function getOption() {
	
    }

    public function getFail() {
	$logs = [
	    '要求したページは表示されません。',
	    '要求しているデータと実際のデータを比べ、記述や内容が正しいかどうかをご確認ください。',
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'エラーが発生しました');
	return $this->Export();
    }

}
