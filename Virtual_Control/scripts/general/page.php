<?php

include_once __DIR__ . '/former.php';

/**
 * [CLASS] Page
 * 
 * <h3>【クラス概要】</h3>
 * ページの構成の設定を行う汎用クラスです<br>
 * 継承先としてFormerを用いています
 * 
 * @package VirtualControl_scripts_general
 * @author ClearNB<clear.navy.blue.star@gmail.com>
 * @category class
 */
class Page extends Former {
    protected $response_code;
    protected $response_data;
    
    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです<br>
     * Formerを継承します
     * 
     * @param int $code 該当Getから取得したレスポンスIDを指定します
     * @param int $data 該当Getから取得したレスポンスデータを指定します
     * @param string $id フォームIDを指定します（Default: 'fm_pg'）
     */
    public function __construct($code, $data, $id = 'fm_pg') {
	parent::__construct($id);
	$this->response_code = $code;
	$this->response_data = $data;
    }
    
    /**
     * [GET] ページデータ取得（汎用）
     * 
     * レスポンスコードより識別される情報でページの分岐を行います<br>
     * 継承により分岐の拡張ができます
     */
    public function setPageFunc() {
	$page = '';
	switch($this->response_code) {
	    default:
		$page = getFail((is_array($this->response_data)) ? 'データなし' : $this->response_data);
	}
    }
    
    /**
     * [GET] ページ取得
     * 
     * レスポンスコードとレスポンスデータをもとにページ生成処理を実行します
     * 
     * @return string 生成したHTMLデータを文字列として返します
     */
    public function getPage(): string {
	$this->setPageFunc();
	if(!$this->data) {
	    $this->setFail();
	}
	return $this->Export();
    }
    
    /**
     * [SET] 失敗画面設定
     * 
     * ページ内の要求・応答において失敗した場合のエラー画面を設定します<br>
     * レスポンスデータが文字列の場合、そのエラー構文をともに出力します<br>
     * (Button) id: bt_fl_rt , output : ページを再読込する , type: button
     * 
     */
    protected function setFail() {
	if(!$this->response_data || !is_string($this->response_data)) {
	    $this->response_data = 'データなし';
	}
	$logs = [
	    'データベースとの接続をご確認ください。',
	    '要求しているデータと実際のデータを比べ、記述や内容が正しいかどうかをご確認ください。',
	    'アカウントセッションが切れていると思われます。もう一度ログインし直してから再試行してください。',
	    $this->response_data
	];
	$this->fm_fl($logs, ['bt_fl_rt', 'ページを再読込する', 'button', 'sync-alt'], 'エラーが発生しました');
    }
    
    /**
     * [SET] 成功画面設定
     * 
     * 設定の処理が完了した場合に成功画面を設定します<br>
     * （Button） id: bt_cs_bk , output: 設定トップ画面に戻る , type: button
     */
    protected function setCorrect() {
	$this->SubTitle('更新に成功しました！', 'ボタンを押して変更が反映したか確認しましょう！', 'check-square');
	$this->Button('bt_cs_bk', '設定トップ画面に戻る', 'button', 'chevron-circle-left');
	return $this->Export();
    }
    
    /**
     * [SET] 更新確認画面設定
     * 
     * 入力〜認証までの段階をクリアした場合、入力された情報をもう一度確認する画面を設定します<br>
     * 作成時にはレスポンスデータに確認用のデータ（各）を組み込む必要です
     */
    protected function setConfirm() {
	$this->SubTitle('入力確認', '入力事項が正しければ「更新する」を押してください。<br>（※）「キャンセル」の場合、アカウント選択画面に遷移します。', 'user-check');
	$this->Caption($this->response_data);
	$this->Button('bt_cf_sb', '更新する', 'button', 'sign-in-alt');
	$this->Button('bt_cf_bk', '入力に戻る', 'button', 'chevron-circle-left');
    }
}