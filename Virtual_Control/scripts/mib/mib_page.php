<?php

include_once __DIR__ . '/../general/page.php';

class MIBPage extends Page {

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

	    case 11: $res_page = $this->setMainPage();
	}
	return $res_page;
    }

    /**
     * [SET] メインページ作成
     * 
     * 登録されているMIBシリーズを閲覧できるページです
     */
    private function setMainPage() {
	/**
	 *  - {--} .. グループ選択リスト（ID : gp_sl_**, VALUE: gp）
	 */
	$this->Button('bt_mb_sl_bk', '設定一覧に戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('MIBポータル', 'MIBを作成するときはページ上部の「MIB作成」から行います<br>グループ関係は、グループ作成またはグループ編集、削除を行います');
	$this->Button('bt_mb_sl_cr', 'MIB作成', 'button', 'plus-square');
	$this->Horizonal();
	$this->FormStart('グループ選択', 'users');
	$this->setHTML($this->response_data);
	$this->Button('bl_mb_gp_sl', 'グループ選択', 'button', 'vote-yea', true);
	$this->Button('bl_mb_gp_cr', 'グループ作成', 'button', 'folder-plus');
	$this->FormEnd();
    }

    private function setMIBGroupSelect() {
	/**
	 *  - [GROUPOID] .. (String)
	 *  - [GROUPNAME] .. (String)
	 *  - [MIBSELECT] .. (button ID : bt_gp_++)
	 */
	$this->Button('bt_mb_gp_bk', 'MIBポータルに戻る', 'button', 'chevron-circle-left');
	$this->FormStart('グループ情報', 'folder');

	$this->FormEnd();
    }

    private function setMIBCreatePage() {
	$this->Button('bt_cr_bk', 'MIBポータルに戻る', 'button', 'chevron-circle-left');
	$this->SubTitle('MIB作成', '必須事項を入力して、MIBを作成しましょう！', 'bars');
	$this->Input('data_oid', 'データOID', '【条件】(数字).(数字).(数字) ... (-255文字)<br>（※）データとして参照できない分岐用OIDを指定しないでください', 'object-ungroup');
	$this->FormStart('データタイプ選択', 'border-style');
	$this->Caption('ANALYでSNMPWALKのために利用するMIBか、WARNでOIDを認識するために利用するMIBかを指定します。', false);
	$this->Check(1, 'data_type_0', 'data_type', 0, '0: 通常データ（ANALY）', true, true);
	$this->Check(1, 'data_type_1', 'data_type', 1, '1: トラップデータ（WARN）', true, true);
	$this->FormEnd();
    }

    private function setMIBCreatePageNormal() {
	
    }

    private function setMIBCreatePageTable() {
	
    }

}
