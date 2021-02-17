<?php

include __DIR__ . '/../general/page.php';

class LinksPage extends Page {
    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $code レスポンスコードを指定します
     * @param string $data レスポンスデータを指定します（Default: ''）
     * @param string $id フォームIDを指定します（Default: 'fm_pg'）
     */
    public function __construct($code, $data = '', $id = 'fm_pg') {
	parent::__construct($code, $data, $id);
    }

    /**
     * [GET] WARNページデータ取得
     * 
     * レスポンスコードより識別される情報でページの分岐を行います<br>
     * 継承により分岐の拡張ができます
     * 
     * @return string レスポンスコードによって取得するページを分岐し、ページをHTMLの文字列で返します
     */
    public function setPageFunc(): string {
	$res_page = '';

	switch ($this->response_code) {
	    case 1: //INDEX
		$this->setIndex();
		break;
	    case 21: //DASH
		$this->setDash();
		break;
	    case 61: //OPTION
		$this->setOption();
		break;
	    default:
		$this->setFail();
		break;
	}
    }

    /**
     * [SET] INDEX設定
     * 
     * INDEXページの全体のページを設定します
     */
    public function setIndex() {
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

    /**
     * [SET] DASH設定
     * 
     * DASHページの全体のページを設定します
     */
    public function setDash() {
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
	} else {
	    $this->setFail();
	}
    }

    /**
     * [SET] OPTION設定
     * 
     * OPTION画面全体を設定します
     */
    public function setOption() {
	$this->Button('bt_pg_bk', 'ホームに戻る', 'button', 'home');
	$this->SubTitle('設定一覧', '設定したい項目を選んでください。', 'wrench');
	$this->openListGroup();
	$this->addListGroup('account', 'ACCOUNT', 'user', 'アカウント管理ページ', 'アカウント一覧／作成／編集／削除');
	$this->addListGroup('mib', 'MIB', 'database', 'MIB管理ページ', 'MIB一覧／作成／編集／削除');
	$this->addListGroup('agent', 'AGENT', 'server', 'エージェント管理ページ', 'エージェント一覧／作成／編集／削除');
	$this->closeListGroup();
    }
}
