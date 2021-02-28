<?php

include_once __DIR__ . '/session.php';

/**
 * Description of Get
 *
 * @author clearnb
 */
class Get {

    protected $request_code;
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
     * @return array|string|int セッションの取得に成功した場合はそのデータが、失敗した場合はnullが返されます
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
     * @return bool 追加に成功した場合はtrue、失敗した場合はfalseが返されます
     */
    protected function set_session($data): bool {
	return session_create($this->session_id, $data);
    }
    
    /**
     * [GET] セッションデータリセット・代入
     * 
     * セッションIDをもとに、現在のデータを一旦削除した上で、新たなデータを代入します
     * 
     * @param array|string|int $data セッションとして代入するデータを指定します
     * @return bool 再代入に成功した場合はtrue、失敗した場合はfalseが返されます
     */
    protected function reset_session($data): bool {
	session_unset_byid($this->session_id);
	return session_create($this->session_id, $data);
    }
    
    protected function is_session(): bool {
	return session_exists($this->session_id);
    }
}
