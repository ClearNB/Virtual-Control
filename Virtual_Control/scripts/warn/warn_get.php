<?php

include_once __DIR__ . '/../general/get.php';
include_once __DIR__ . '/warn_data.php';
include_once __DIR__ . '/warn_table.php';

class WarnGet extends Get {
    private $sub_data;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $request_code リクエストコードを指定します
     */
    public function __construct($request_code) {
	parent::__construct($request_code, 'gsc_warn_result');
	$this->sub_data = post_get_data('sub');
    }

    /**
     * [GET] WARN処理
     * 
     * クラス内のデータをもとにデータ処理を行います
     * 
     * @return array CODE, DATAによる連想配列で返します（CODEはレスポンスコード、DATAはレスポンスデータとなります）
     */
    public function run(): array {
	$res = ['CODE' => 1, 'DATA' => '要求データを受け取れませんでした'];
	if (session_chk() == 0) {
	    switch ($this->request_code) {
		case 81:
		    initialize();
		    $data = WarnData::getWarn();
		    $table = new WarnTable($data);
		    $res_data = $table->getHTML();
		    $res['CODE'] = 0;
		    $res['DATA'] = ['SUB' => $res_data['SUB'], 'DATE' => $data['DATE'], 'LIST' => $res_data['LIST'], 'CSV' => $data['CSV'], 'COUNT' => $res_data['COUNT']];
		    $this->set_session($res['DATA']);
		    break;
		case 83:
		    $data = get_session();
		    $res['CODE'] = ($data) ? 0 : $res['CODE'];
		    $res['DATA'] = ($data) ? $data : $res['DATA'];
		    break;
		case 82:
		    if ($this->sub_data) {
			$data = $this->get_session_byid($this->sub_data);
			$res['CODE'] = ($data) ? 1 : $res['CODE'];
			$res['DATA'] = ($data) ? $data : $res['DATA'];
		    }
		    break;
	    }
	    if (ob_get_contents()) {
		$res['CODE'] = 2;
		$res['DATA'] = ob_get_contents();
		ob_clean();
	    }
	}
	
	return $res;
    }

    /**
     * [GET] サブデータ取得
     * 
     * セッションよりデータを取得し、ログデータ内のサブ情報を取り出し返します
     * 
     * @param string $id サブ情報を判別するIDを指定します
     * @return array|null 正しく指定されている場合はその情報を、指定できていない場合はnullを返します
     */
    private function get_session_byid($id) {
	$data = $this->get_session();
	return (isset($data['SUB'][$id])) ? $data['SUB'][$id] : '';
    }
}