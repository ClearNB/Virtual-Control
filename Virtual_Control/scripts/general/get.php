<?php

include_once __DIR__ . '/session.php';

/**
 * Description of Get
 *
 * @author clearnb
 */
class Get {

    protected $request_code;
    protected $get_data;
    protected $session_id;

    /**
     * [SET] CONSTRUCTOR
     * 
     * オブジェクトコンストラクタです
     * 
     * @param string $request_code リクエストコードを指定します
     * @param string $session_id ページ共通セッションIDを指定します
     */
    public function __construct($request_code, $session_id) {
	$this->request_code = $request_code;
	$this->session_id = $session_id;
    }

    /**
     * [GET] 要求処理
     * 
     * 要求に従い処理を行います
     * 
     * @return array CODE, DATAが連想の配列を返します
     */
    public function run(): array {
	return ['CODE' => 1, 'DATA' => '要求された行為は受け取れませんでした'];
    }
    
    /**
     * [SET] 取得データ設定
     * 引数によって取得したデータを指定します
     * 
     * @param array $column 
     * @param array $value
     */
    public function setGetData($column, $value) {
	$this->get_data = [];
	for($i = 0; $i < sizeof($column); $i++) {
	    $this->get_data[$column[$i]] = $value[$i];
	}
    }

    /**
     * [GET] セッション初期化
     * 
     * 指定したページ共通セッションIDをもとにセッションを解除します
     * 
     * @return bool セッションの初期化に成功した場合はtrue、それ以外の場合はfalseを返します
     */
    protected function initialize() {
	return session_unset_byid($this->session_id);
    }

    /**
     * [GET] セッションデータ取得
     * 
     * 事前に指定された共通セッションIDをもとにセッションデータを取得します
     * 
     * @return array|string|int セッションの取得に成功した場合はそのデータ、失敗した場合はnullを返します
     */
    protected function get_session() {
	return session_get($this->session_id);
    }

    /**
     * [GET] セッションデータ代入・上書き
     * 
     * セッションIDをもとに、現在のデータを代入・上書きします
     * 
     * @param array|string|int $data セッションとして代入するデータを指定します
     * @return bool 追加に成功した場合はtrue、失敗した場合はfalseがを返します
     */
    protected function set_session($data, $is_regenerated = true): bool {
	return session_create($this->session_id, $data, $is_regenerated);
    }

    /**
     * [GET] セッションデータリセット・代入
     * 
     * セッションIDをもとに、現在のデータを一旦削除した上で、新たなデータを代入します
     * 
     * @param array|string|int $data セッションとして代入するデータを指定します
     * @return bool 再代入に成功した場合はtrue、失敗した場合はfalseを返します
     */
    protected function reset_session($data): bool {
	session_unset_byid($this->session_id);
	return session_create($this->session_id, $data);
    }

    /**
     * [GET] セッションデータ確認
     * 
     * Getオブジェクトで設定されたセッションIDのデータが存在することを確認します
     * 
     * @return bool 設定されたセッションIDのデータが存在すればtrue、それ以外はfalseを返します
     */
    protected function is_session(): bool {
	return session_exists($this->session_id);
    }

    /**
     * [GET] 選択データ一時保存
     * 
     * 選択されたデータをセッション内に一時保存しておくSELECTキーを作成し、その中に作成します
     * 
     * @param string $id 選択するデータのIDを指定します
     * @param string $data_id セッション内の選択データIDより前に設定するキーを指定します（Default: ''）
     * @param bool $isconfirm ログ上でデータの中身を確認するかどうかを設定します（Default: false）
     * @return bool 選択データを登録できていればtrue、そうでない場合はfalseを返します
     */
    protected function set_select($id, $data_id = '', $isconfirm = false) {
	$session = $this->get_session();
	if($isconfirm) {
	    var_dump($session);
	}
	if ($data_id) {
	    $session['SELECT'] = $session[$data_id][$id];
	} else {
	    $session['SELECT'] = $session[$id];
	}
	$res = '';
	if($this->reset_session($session)) {
	    $res = $session;
	}
	return $res;
    }

    /**
     * [GET] 選択データ取得
     * 
     * セッション内に選択データがあるかどうかを確認した上で、選択データを取得します
     * 
     * @return string|array 選択データがある場合はそのデータを、ない場合はnullを返します
     */
    protected function get_select() {
	$session = $this->get_session();
	return isset($session['SELECT']) ? $session['SELECT'] : '';
    }

    /**
     * [GET] ユーザ選択データリセット
     * 
     * 現在あるユーザ選択データをリセットし、選択されていない状態にします
     * 
     * @return bool ユーザ選択データをリセットできていればture、それ以外はfalseを返します
     */
    protected function reset_select() {
	$session = $this->get_session();
	unset($session['SELECT']);
	return $this->reset_session(session);
    }

    /**
     * [SET] ユーザ入力データ一時保存
     * 
     * 入力されたデータをセッション内に一時保存します
     * 
     * @param string|array $value 入力データを指定します
     * @return bool 入力データを登録できていればtrue、そうでない場合はfalseを返します
     */
    protected function set_input($value) {
	$session = $this->get_session();
	$session['INPUT'] = $value;
	return $this->reset_session($session);
    }

    /**
     * [GET] 入力データ取得
     * 
     * セッション内に入力データがあるかどうかを確認した上で、入力データを取得します
     * 
     * @return string|array 入力データがある場合はそのデータを、ない場合はnullを返します
     */
    protected function get_input() {
	$session = $this->get_session();
	return isset($session['INPUT']) ? $session['INPUT'] : '';
    }

    /**
     * [GET] ユーザ入力データリセット
     * 
     * 現在あるユーザ入力データをリセットし、入力されていない状態にします
     * 
     * @return bool ユーザ入力データをリセットできていればtrue、それ以外はfalseを返します
     */
    protected function reset_input() {
	$session = $this->get_session();
	unset($session['INPUT']);
	return $this->reset_session(session);
    }

}
