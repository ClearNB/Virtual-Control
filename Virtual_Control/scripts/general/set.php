<?php

class Set {

    protected $result_form = [
	['CODE' => 0],
	['CODE' => 1, 'DATA' => '認証情報に異常が見つかりました。VCServer権限のみ実行可能な処理のため、認証情報が異なるユーザでは処理することができません。'],
	['CODE' => 1, 'DATA' => '<ul class="black-view"><li>手続きに失敗しました。</li><li>データベースとの接続中にエラーが発生しました。</li></ul>'],
	['CODE' => 2, 'DATA' => '<ul class="black-view"><li>手続きに失敗しました。手続き上で正しく入力してください。</li><li>システム上の正しい動作のため、UI上の操作以外での通信は拒否されます。</li></ul>'],
	['CODE' => 2, 'DATA' => '<ul class="black-view"><li>入力チェックエラーです。以下の入力したデータをご確認ください。</li>'],
	['CODE' => 3, 'DATA' => '<ul class="black-view"><li>認証に失敗しました。もう一度入力してください。</li></ul>'],
	['CODE' => 4],
	['CODE' => 5],
	['CODE' => 6, 'DATA' => '']
    ];
    protected $runid;
    protected $funid;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $funid FunctionIDを指定します
     */
    public function __construct($funid) {
	$this->funid = $funid;
	$this->runid = 1;
    }

    protected function setRunID() {
	$this->runid = 1;
    }

    public function run() {
	$this->setRunID();
	return $this->result_form[$this->runid];
    }

}
