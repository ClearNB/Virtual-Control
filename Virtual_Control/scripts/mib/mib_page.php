<?php

include_once __DIR__ . '/../general/page.php';

class MIBPage extends Page {

    private static $ruleData = [
	'GROUP_OID' => '【判定条件】半角数字（0〜9）と記号（.）を用いて255文字まで, グループOIDは一意で、配下に依存していない必要があります<br>【例】1.3.6.1.2.1',
	'GROUP_NAME' => '【判定条件】半角英数字・一部記号（a〜z, A〜Z, 0〜9, -, _）50文字まで',
	'SUB_OID' => '【判定条件】半角数字（0〜9）のみで32ビット整数まで, グループOIDに続く一意の数字を指定する必要があります。<br>【例】1.3.6.1.2.1 + 1',
	'SUB_NAME' => '【判定条件】半角英数字（a〜z, A〜Z, 0〜9）50文字まで, サブツリー名はANALYにて表示されます。',
	'NODE_OID' => '【判定条件】半角数字のみ（符号なし）, グループOID + サブツリーOIDに続く1つの数字を指定する必要があります。<br>【例】1.3.6.1.2.1.1 + 1',
	'NODE_SUB' => '【判定条件】OIDサブ情報, 半角数字（0〜9）と記号（.）を用いて11文字まで, ノードOID指定より交尾で追加可能な追加OID情報を指定します（先頭に . を付ける必要はありません）<br>【例】1.3.6.1.2.1.1 + 1 + 1',
    ];
    private static $iconData = [
	'GROUP_OID' => 'id-badge',
	'GROUP_NAME' => 'sliders-h',
	'SUB_OID' => 'id-badge',
	'SUB_NAME' => 'sliders-h'
    ];

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
     * [GET] MIBページデータ取得
     * 
     * レスポンスコードより識別される情報でページの分岐を行います<br>
     * 継承により分岐の拡張ができます
     * 
     * @return string レスポンスコードによって取得するページを分岐し、ページをHTMLの文字列で返します
     */
    public function setPageFunc() {
	$res_page = '';
	switch ($this->response_code) {
	    //GENERAL
	    case 0: $res_page = $this->getCorrect();
		break;     //COMPLETE
	    case 1: $res_page = $this->getError();
		break;     //ERROR (GENERAL)
	    case 2: $res_page = $this->getQueryError();
		break;     //ERROR (QUERY)
	    case 3: case 5: $res_page = $this->response_data;
		break;     //ERROR (INPUT, AUTH)
	    case 4: $res_page = $this->getAuth();
		break;     //AUTH
	    case 6: $res_page = $this->getConfirm();
		break;     //CONFIRM
	    
	    case 11: $res_page = $this->getMainPage();
	}
	return $res_page;
    }
    
    private function getMainPage() {
	$this->Button('bt_ac_bk', '設定一覧に戻る', 'button', 'list');
	$this->SubTitle('MIBメインターミナル', '各グループ・サブツリー・ノードは階層別に管理できます。<br>新しく作る場合は「グループ」は以下の「グループ作成」ボタン、「サブツリー」は各グループ内の「サブツリー作成」ボタン、「ノード」は各グループ・サブツリー内の「ノード作成」ボタンから作成してください。', 'object-group');
	$this->FormStart('MIBグループルート', '');
	$this->Button('グループ作成', '', 'button', '');
	if(is_array($this->response_data)) {
	    
	}
	$this->FormEnd();
    }

}
