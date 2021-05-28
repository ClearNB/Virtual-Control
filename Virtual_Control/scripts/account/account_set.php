<?php

class AccountSet {
    /**
     * [VAR] リザルトフォーム
     * 
     * [0] = (0) 成功<br>
     * [1] = (1) データベースエラー<br>
     * [2] = (2) 手続きエラー（ファンクションと内容が違う）<br>
     * [3] = (2) チェックエラー（入力チェック）<br>
     * [4] = (1) 認証エラー<br>
     * [5] = (3) ログインエラー（編集・削除）<br>
     * [6] = (4) 認証ハック<br>
     * [7] = (5) 認証エラー<br>
     * [8] = (6) 確認ハック<br>
     * [9] = (7) ユーザエラー（ログイン）<br>
     * 
     * @var array
     */
    private $result_form = [
	['CODE' => 0], //0
	['CODE' => 1, 'DATA' => '<ul class="black-view"><li>手続きに失敗しました。</li><li>データベースとの接続中にエラーが発生しました。</li></ul>'], //1
	['CODE' => 2, 'DATA' => '<ul class="black-view"><li>手続きに失敗しました。手続き上で正しく入力してください。</li><li>システム上の正しい動作のため、UI上の操作以外での通信は拒否されます。</li></ul>'], //2
	['CODE' => 2, 'DATA' => '<ul class="black-view"><li>入力チェックエラーです。以下の入力したデータをご確認ください。</li>'], //3
	['CODE' => 1, 'DATA' => '認証情報に異常が見つかりました。VCServer権限のみ実行可能な処理のため、認証情報が異なるユーザでは処理することができません。'], //4
	['CODE' => 3], //5
	['CODE' => 4], //6
	['CODE' => 5, 'DATA' => '<ul class="black-view"><li>認証に失敗しました。もう一度入力してください。</li></ul>'], //7
	['CODE' => 6, 'CONFIRM_DATA' => ''], //8
    ];
    private $userid;
    private $pre_userid;
    private $username;
    private $pass;
    private $r_pass;
    private $a_pass;
    private $per;
    private $funid;

    /**
     * 実際の登録情報を格納します
     * @param int    $functionid    ファンクションID
     * @param string $pre_userid    変更時の対象ユーザID
     * @param string $userid	    ユーザID（作成・変更・削除）
     * @param string $username	    ユーザ名（作成・変更）
     * @param string $a_pass	    認証時のパスワード（認証）
     * @param string $pass	    パスワード（作成・変更）
     * @param string $r_pass	    確認用のパスワード（作成・変更）
     * @param int    $per	    権限（作成）
     */
    public function __construct($functionid, $pre_userid, $userid, $username, $a_pass, $pass, $r_pass, $per) {
	$this->pre_userid = $pre_userid;
	$this->userid = $userid;
	$this->username = $username;
	$this->a_pass = $a_pass;
	$this->pass = $pass;
	$this->r_pass = $r_pass;
	$this->per = $per;
	$this->funid = $functionid;
	$this->check_functionid();
    }

    /**
     * [GET] ファンクションID調査
     * 
     * ファンクションIDの調査を行います。<br>
     * 実際送られたデータと、送付されているファンクションIDが正しいか、元のデータをたどって確認します。<br>
     * [返却値について]<hr>
     * 1. 作成
     * 2. 変更（ユーザID）
     * 3. 変更（ユーザ名）
     * 4. 変更（パスワード）
     * 5. 削除
     * 6. 手続きエラー
     * 
     * @param int $functionid	実際のファンクションIDです
     */
    private function check_functionid() {
	if (!session_auth() && $this->a_pass) {
	    $set_fun = 999;
	} else {
	    $set_fun = $this->check_correct_functionid();
	    if ($set_fun != $this->funid) {
		$set_fun = 8;
	    }
	}
	$this->funid = $set_fun;
    }

    private function check_correct_functionid() {
	$set_fun = 0;
	$flag1 = $this->userid && $this->username && $this->pass && $this->r_pass;
	$flag2 = isset($this->per);
	if ($flag1 && $flag2) {
	    $set_fun = 2;
	} else if ($this->pre_userid && $this->userid) {
	    $set_fun = 4;
	} else if ($this->pre_userid && $this->username) {
	    $set_fun = 5;
	} else if ($this->pre_userid && $this->pass && $this->r_pass) {
	    $set_fun = 6;
	} else if ($this->pre_userid) {
	    $set_fun = 7;
	}
	return $set_fun;
    }

    /**
     * [GET] 実行ファンクション
     * 
     * 実行ファンクションを起こします。<br>
     * CODEについて<hr>
     * 0 = 正常終了<br>
     * 1 = 異常終了（原因あり）<br>
     * 2 = 認証が必要（認証を呼び出す）
     * 
     * @return array ["CODE" => .., "ERR_TEXT" => '..']
     */
    public function run() {
	$result_code = 0;
	switch ($this->funid) {
	    case 2: $result_code = $this->create();
		break;
	    case 4: case 5: case 6: $result_code = $this->edit();
		break;
	    case 7: $result_code = $this->delete();
		break;
	    case 8: $result_code = 2;
		break;
	    case 999: $result_code = $this->auth();
		break;
	}
	return $this->result_form[$result_code];
    }

    /**
     * アカウントを作成します（作成フラグは以下参照）
     * @return int (0..完了, 1..アカウント認証が必要, 2..セッション切れにより更新中止, 3..データベース障害が発生)
     */
    private function create(): int {
	$chk = $this->check();
	$res_code = ($chk != 0) ? $chk : ((!session_auth()) ? 6 : 0);
	if ($res_code == 0) {
	    $salt = random(20);
	    $pass_hash = hash('sha256', $this->pass . $salt);
	    $res = insert('VC_USERS', ['USERID', 'PASSWORDHASH', 'USERNAME', 'PERMISSION', 'SALT'], [$this->userid, $pass_hash, $this->username, $this->per, $salt]);
	    $res_code = ($res) ? 0 : 1;
	}
	return $res_code;
    }

    private function edit(): int {
	$chk = $this->check();
	$chk_login = $this->check_user_login();
	$me = $this->check_users_me();

	$res_code = ($chk != 0) ? 3 : (($chk_login && $me) ? 5 : ((!session_auth()) ? 6 : 0));
	if ($res_code == 0) {
	    $query = $this->editQuery();
	    if ($query && $this->funid == 4 && !$me) {
		$res_code = (session_create('gsc_userid', $this->userid)) ? 0 : 1;
	    }
	}
	return $res_code;
    }

    private function delete(): int {
	$chk_login = $this->check_user_login();
	$res_code = ($chk_login) ? 5 : ((!session_auth()) ? 6 : 0);
	if ($res_code == 0) {
	    $res = delete("VC_USERS", "WHERE USERID = '$this->pre_userid'");
	    $res_code = ($res) ? 0 : 1;
	}
	return $res_code;
    }

    private function check() {
	$chk_text = '[DATA]</ul>';
	$chk = '';
	switch ($this->funid) {
	    case 2: //作成（ユーザID・ユーザ名・パスワード・権限確認）
		$chk .= $this->check_userid() . $this->check_username() . $this->check_password() . $this->check_permission();
		break;
	    case 4: //編集1（ユーザID確認）
		$chk .= $this->check_userid();
		break;
	    case 5: //編集2（ユーザ名確認）
		$chk .= $this->check_username();
		break;
	    case 6: //編集3（パスワード確認）
		$chk .= $this->check_password();
		break;
	}
	if ($chk) {
	    $chk_text = str_replace('[DATA]', $chk, $chk_text);
	    $this->result_form[3]['DATA'] .= $chk_text;
	    return 3;
	} else {
	    return 0;
	}
    }

    private function auth() {
	$res = 0;
	$s_userid = session_get_userid();
	switch (session_auth_check($s_userid, $this->a_pass, true)) {
	    case 0: $res = 8;
		$this->result_form[$res]['CONFIRM_DATA'] = $this->generateList();
		break;
	    case 1: $res = 1;
		break;
	    case 2: $res = 7;
		break;
	}
	return $res;
    }

    private function editQuery() {
	$res = [];
	switch ($this->funid) {
	    case 4: $res = ['VC_USERS', ['USERID'], [$this->userid]];
		break;
	    case 5: $res = ['VC_USERS', ['USERNAME'], [$this->username]];
		break;
	    case 6:
		$salt = random(20);
		$pass_hash = hash('sha256', $this->pass . $salt);
		$res = ['VC_USERS', ['PASSWORDHASH', 'SALT'], [$pass_hash, $salt]];
		break;
	}
	$flag = true;
	for ($i = 0; $i < sizeof($res[1]); $i++) {
	    $r01 = update($res[0], $res[1][$i], $res[2][$i], "WHERE USERID = '$this->pre_userid'");
	    if (!$r01) {
		$flag = false;
		break;
	    }
	}
	return $flag;
    }

    private function generateList() {
	$list_text = '<ul class="black-view">';
	$func = '<li>ファンクション: [FUNCTION]</li>';
	$column_list = ['<li>対象のユーザ: ' . $this->pre_userid . '</li>', '<li>新しいユーザID: [NEW_USERID]</li>', '<li>新しいユーザ名: [NEW_USERNAME]</li>', '<li>新しいパスワード: [表示できません]</li>', '<li>権限: [NEW_PERMISSION]</li>'];
	$columns = [];
	switch ($this->check_correct_functionid()) {
	    case 2: $func = str_replace('[FUNCTION]', 'ユーザ作成', $func);
		$columns = [1, 2, 3, 4];
		break;
	    case 4: $func = str_replace('[FUNCTION]', 'ユーザ編集（ユーザID）', $func);
		$columns = [0, 1];
		break;
	    case 5: $func = str_replace('[FUNCTION]', 'ユーザ編集（ユーザ名）', $func);
		$columns = [0, 2];
		break;
	    case 6: $func = str_replace('[FUNCTION]', 'ユーザ編集（パスワード）', $func);
		$columns = [0, 3];
		break;
	    case 7: $func = str_replace('[FUNCTION]', 'ユーザ削除', $func);
		$columns = [0];
		break;
	}
	$list_text .= $func;
	foreach ($columns as $col) {
	    $text = $column_list[$col];
	    switch ($col) {
		case 1: $text = str_replace('[NEW_USERID]', $this->userid, $text);
		    break;
		case 2: $text = str_replace('[NEW_USERNAME]', $this->username, $text);
		    break;
		case 4: $text = str_replace('[NEW_PERMISSION]', $this->get_permission_text($this->per), $text);
		    break;
	    }
	    $list_text .= $text;
	}
	$list_text .= '</ul>';
	return $list_text;
    }

    private function get_permission_text($permission): string {
	$text = '';
	switch ($permission) {
	    case 0: $text = 'VCServer';
		break;
	    case 1: $text = 'VCHost';
		break;
	}
	return $text;
    }

    /**
     * [GET] 指定ユーザセッション一致確認
     * 
     * 削除しようとしている情報が自分のユーザであるか確認します
     * ユーザが自分である場合はfalseを返し、それ以外はtrueを返します
     * 
     * @return bool
     */
    private function check_users_me(): bool {
	$flag = (session_chk() == 0 && !($this->userid == session_get_userid()));
	return $flag;
    }

    /**
     * [GET] ユーザログインチェック
     * 
     * 指定したユーザがログイン中かどうかを判定します<br>
     * ログイン中である場合は true, でない場合は false が返されます
     * 
     * @return bool
     */
    private function check_user_login(): bool {
	$result = select(true, 'VC_USERS', 'LOGINSTATE', 'WHERE USERID = \'' . $this->userid . '\'');
	$res = ($result && $result['LOGINSTATE'] == 1);
	return $res;
    }

    /**
     * [GET] ユーザ名確認
     * 
     * ユーザ名の記述についてチェックします。<br>
     * 【判定条件】ユーザ名が最大50バイトを超えていないか かつ 1文字以上書いているか<br>
     * <br>（※）表示形式はUTF-8のため、UTF-8でのバイト数で数えます。
     * 
     * @return string|null 何らかのエラーがあれば、その原因のエラーを出し、何もなければnullを返します。
     */
    private function check_username(): string {
	$res = '';
	$len = strlen(mb_convert_encoding($this->username, 'SJIS', 'UTF-8'));
	if ($len > 30 || $len < 1) {
	    $res .= '<li>ユーザ名が最大半角文字数30文字を超えています。</li>';
	}
	return $res;
    }

    /**
     * [GET] ユーザID確認
     * 
     * ユーザIDの記述について確認します。<br>
     * 【判定条件】記入しようとしているユーザIDがすでに登録されているか かつ 半角英数字[数字・英字組み合わせ]で5-20文字を遵守しているか<br>
     * （※）ユーザIDは大文字・小文字が区別されます。
     * 
     * @return string|null 何らかのエラーがあれば、その原因のエラーを出し、何もなければnullを返します。
     */
    private function check_userid(): string {
	$res = '';
	$result = select(true, 'VC_USERS', 'COUNT(*) AS USERCOUNT', 'WHERE USERID = \'' . $this->userid . '\'');
	if ($result && $result['USERCOUNT'] >= 1) {
	    $res = '<li>ユーザIDが重複しています。</li>';
	}
	if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{5,20}+\z/', $this->userid)) {
	    $res .= '<li>ユーザID入力ルールに違反しています。</li>';
	}
	return $res;
    }

    /**
     * [GET] パスワード確認
     * 
     * パスワードの記述について確認します。<br>
     * 【判定条件】半角英数字・記号($, _ のみ)を用いて10-30文字の範囲で記述しているか
     * 
     * @return string|null 何らかのエラーがあれば、その原因のエラーを出し、何もなければnullを返します。
     */
    private function check_password() {
	$res = '';
	if (!preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)(?=.*?[$_])[a-zA-Z\d$_]{10,30}+\z/', $this->pass)) {
	    $res .= '<li>パスワードルールに違反しています。</li>';
	}
	if ($this->pass != $this->r_pass) {
	    $res .= '<li>確認用パスワードが間違っています。</li>';
	}
	return $res;
    }

    /**
     * [GET] 権限確認
     * 
     * 権限の記述について確認します。
     * 【判定条件】0（VCServer）, 1（VCHost）のいずれかが代入されていること
     * 
     * @return string|null 何らかのエラーがあれば、その原因のエラーを出し、何もなければnullを返します。
     */
    private function check_permission() {
	$res = '';
	if (!isset($this->per) && (($this->per == 0) || ($this->per == 1))) {
	    $res .= '<li>権限を選択してください。</li>';
	}
	return $res;
    }
}