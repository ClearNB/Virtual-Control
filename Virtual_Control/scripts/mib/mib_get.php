<?php

/**
 * [CLASS] MIB_GET_CLASS
 * 
 * <h4>MIB GET 共通クラス</h4><hr>
 * OPTION - MIBの操作に関する共通のリテラルを定義したクラスです
 * 
 * @package VirtualControl_scripts_mib
 * @author ClearNB <clear.navy.blue.star@gmail.com>
 */
class MIBGetClass {

    public $session_data;
    public $request_code;
    public $request_data_code;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param int $request_code リクエストコードを指定します
     * @param int $request_data_code データコードを指定します
     */
    public function __construct($request_code, $request_data_code) {
	$this->request_code = $request_code;
	$this->request_data_code = $request_data_code;
	$this->set_session();
    }

    /**
     * [SET] セッションデータ作成
     * 
     * セッションデータを作成します<br>
     * セッションデータは、MIBDataにて取得したデータです
     * 
     */
    public function create_session() {
	$data = new MIBData();
	$this->session_data = $data->getMIB(3);
	session_create('gsc_mib_option', $data);
    }

    /**
     * [SET] AuthIDリセット
     * 
     * 認証情報であるAuthIDを解除し、認証情報がないことを確認します
     */
    public function reset_authid() {
	session_unset_byid('gsc_authid');
    }
    
    /**
     * [SET] セッションデータリセット
     * 
     * セッションデータ 'gsc_mib_option' をリセットし、データを削除します
     */
    public function reset_session() {
	session_unset_byid('gsc_mib_option');
    }

    /**
     * [SET] セッションデータ取得
     * 
     * セッション情報 'gsc_mib_option' を取得します。<br>
     * この際、取得されたデータは$session_dataに格納されます。
     */
    public function set_session(): void {
	$this->session_data = session_get('gsc_mib_option');
    }
    
    /**
     * [GET] セッションデータ取得
     * 
     * セッション情報 'gsc_mib_option' を取得します。<br>
     * 
     * @return array 取得に成功した場合はその情報が、できない場合はnullが返されます
     */
    public static function get_session(): array {
	return session_get('gsc_mib_option');
    }

    /**
     * [SET] セッションデータ書き換え
     * 
     * gsc_mib_option のセッションデータを一旦削除し、また書き換えます
     */
    public function rewrite_session() {
	session_unset_byid('gsc_mib_option');
	session_create('gsc_mib_option', $this->session_data);
    }
    
    /**
     * [SET] 一時データリセット
     * 
     * gsc_mib_option 内の一時データを削除します
     * 
     * @param $type (0..GROUP, 1..SUB, 2..NODE)
     * @param $stype (0..INPUT, 1..STORE)
     */
    public function reset_types($type, $stype) {
	$type_text = $this->get_datatype($type);
	$s_type_text = $this->get_settype($stype);
	if ($this->is_set($type, $stype)) {
	    unset($this->session_data[$type_text][$s_type_text]);
	}
	$this->rewrite_session();
    }

    /**
     * [GET] データタイプ取得
     * 
     * GROUP, SUB, NODE をもつデータを取得します
     * 
     * @param int $type (0..グループ, 1..サブツリー, 2..ノード, それ以外..なし)
     * @return string タイプコードに従い、値を返します
     */
    public function get_datatype($type) {
	$type_text = '';
	switch ($type) {
	    case 0: $type_text = 'GROUP';
		break;
	    case 1: $type_text = 'SUB';
		break;
	    case 2: $type_text = 'NODE';
		break;
	}
	return $type_text;
    }
    
    /**
     * [GET] 一時設定データタイプ取得
     * 
     * INPUT, STORE, PARENTの持つデータを取得します
     * ・INPUT .. ユーザの入力データが格納されます
     * ・STORE .. 現在選択されているデータが格納されます
     * ・PARENT .. 親グループまたは親サブツリーにより選択されたデータが格納されます
     * 
     * @param int $type (0..INPUT, 1..STORE, 2..PARENT)
     * @return string タイプコードに従い、テキストを返します
     */
    public function get_settype($type) {
	$type_text = '';
	switch($type) {
	    case 0: $type_text = 'INPUT';
		break;
	    case 1: $type_text = 'STORE';
		break;
	    case 2: $type_text = 'PARENT';
		break;
	}
	return $type_text;
    }

    /**
     * [SET] セッションデータ一時保存データ格納
     * 
     * データく作成・編集・削除時に一時的にその手続きデータを格納します
     * 
     * @param int $type (0..GROUP, 1..SUB, 2..NODE） 
     * @param int $stype (0..INPUT, 1..STORE, 2..PARENT）
     * @param array $data キー配列つきの連想配列を渡します。keyとvalueをもとに格納されます。
     */
    public function set_data($type, $stype, $data) {
	$type_text = $this->get_datatype($type);
	$s_type_text = $this->get_settype($stype);
	if ($type_text && $s_type_text) {
	    $this->session_data[$type_text][$s_type_text] = $data;
	    $this->rewrite_session();
	}
    }

    /**
     * [GET] 一時データの内部データ取得
     * 
     * 一時データから取得したいkeyを指定しデータを取得します
     * 
     * @param int $type (0..GROUP, 1..SUB, 2..NODE） 
     * @param int $stype (0..INPUT, 1..STORE, 2..PARENT）
     * @param string $id データ内にあるIDを指定します
     * @return (array|string|int)|null 取得できる場合はそのデータを、できない場合はnullを指定します
     */
    public function get_typesdata($type, $stype, $id) {
	$type_text = $this->get_datatype($type);
	$s_type_text = $this->get_settype($stype);
	$data = '';
	if ($type_text && $s_type_text && isset($this->session_data[$type_text][$s_type_text][$id])) {
	    $data = $this->session_data[$type_text][$s_type_text][$id];
	}
	return $data;
    }
    
    /**
     * [GET] 設定確認
     * 
     * 各タイプにて、セッション状態を把握します
     * 
     * @param int $type (0..GROUP, 1..SUB, 2..NODE） 
     * @param int $stype (0..INPUT, 1..STORE）
     * @return bool issetの場合はtrue、そうでない場合はfalseを返します
     */
    public function is_set($type, $stype) {
	$type_text = $this->get_datatype($type);
	$s_type_text = $this->get_settype($stype);
	return $type_text && $s_type_text && is_array($this->session_data) && isset($this->session_data[$type_text][$s_type_text]);
    }
}
