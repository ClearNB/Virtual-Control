<?php

include_once __DIR__ . '/mib_data.php';

/**
 * [CLASS] MIB_GET_CLASS
 * 
 * <h4>MIB GET 共通クラス</h4><hr>
 * OPTION - MIBの操作に関する共通のリテラルを定義したクラスです
 * 
 * @package VirtualControl_scripts_mib
 * @author ClearNB <clear.navy.blue.star@gmail.com>
 */
class MIBGet extends Get {
    
    public function __construct($request_code, $session_id = 'vc_mib') {
	$id_list = ['sel_id', 'data_oid', 'data_type', 'table_type', 'table_ifid', 'enname', 'jpname', 'iconid', 'descr'];
	$data_list = [];
	foreach($id_list as $id) {
	    array_push($data_list, post_get_data($id));
	}
	parent::__construct($request_code, $session_id);
    }
    
    public function run() {
	/** [RESPONSE]
	 * 0 .. 更新成功
	 * 1 .. 認証成功
	 * 2 .. 失敗（データベース接続問題）
	 * 3 .. 失敗（入力エラー｜存在エラー・遷移なし）
	 * 4 .. 失敗（認証切れ）
	 * 5 .. 失敗（認証失敗）
	 * 6 .. 認証が必要
	 * 7 .. 情報を確認
	 * 10 .. 初期ページ（認証が必要）
	 * 11 .. ポータルページ
	 * 12 .. MIB作成ページ
	 * 13 .. MIB作成（通常・データ用・遷移なし）
	 * 14 .. MIB作成（通常・テーブル用・遷移なし）
	 * 15 .. MIB作成（通常・テーブルデータ用・遷移なし）
	 * 16 .. MIB作成（トラップ・データ用・遷移なし）
	 * 17 .. MIB作成（トラップ・テーブル用・遷移なし）
	 * 18 .. MIB作成（トラップ・テーブルデータ用・遷移なし）
	 * 19 .. MIB編集（通常・データ用）
	 * 20 .. MIB編集（通常・テーブル用）
	 * 21 .. MIB編集（通常・テーブルデータ用）
	 * 999 .. リクエストエラー
	 */
	$response = ['CODE' => 999, 'DATA' => 'リクエストエラー、正しい手順で操作してください。'];
	switch($this->request_code) {
	    case 91: //メインページ（グループ選択）
		break;
	    case 92: //メインページ（戻る）
		break;
	    case 93: //
		break;
	    case 94: //
		break;
	    case 95: //
		break;
	    case 96: //
		break;
	    case 97: //
		break;
	    case 98: //
		break;
	}
	return $response;
    }
}
