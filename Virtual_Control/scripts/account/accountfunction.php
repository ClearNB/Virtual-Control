<?php

class AccountSet {

    private $result_form = [
	['CODE' => 0, []],
	['CODE' => 1, 'ERR_TEXT' => "データベースサーバへの接続に失敗しました。<br>データベースサーバに正しく接続できていないか、データベースの接続が拒否されている可能性があります。<br>設定および接続を確認してください。"],
	['CODE' => 1, 'ERR_TEXT' => "手続きに失敗しました。<br>手続き上で正しく入力してください。<br>システム上の正しい動作のため、UI上の操作以外での通信は拒否されます。"],
	['CODE' => 1, 'ERR_TEXT' => "入力チェックエラーです。<br>以下の入力したデータをご確認ください。"],
	['CODE' => 1, 'ERR_TEXT' => "認証情報に異常が見つかりました。<br>VCServer権限のみ実行可能な処理のため、認証情報が異なるユーザでは処理することができません。"],
	['CODE' => 2, 'ERR_TEXT' => ""],
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
	$this->funid = $this->check_functionid($functionid);
    }

    /**
     * ファンクションIDの調査を行います。
     * 実際送られたデータと、送付されているファンクションIDが正しいか、元のデータをたどって確認します。
     * [返却値について]
     * 1 : 作成
     * 2 : 変更（ユーザID）
     * 3 : 変更（ユーザ名）
     * 4 : 変更（パスワード）
     * 5 : 削除
     * 6 : 手続きエラー
     * @param int $functionid	実際のファンクションIDです
     * @return int
     */
    public function check_functionid($functionid) {
	//1: データを確認する
	$set_fun = 0;

	if ($this->userid && $this->username && $this->pass && $this->r_pass && $this->per) {
	    $set_fun = 1;
	} else if ($this->pre_userid && $this->userid) {
	    $set_fun = 2;
	} else if ($this->pre_userid && $this->username) {
	    $set_fun = 3;
	} else if ($this->pre_userid && $this->pass && $this->r_pass) {
	    $set_fun = 4;
	} else if ($this->pre_userid) {
	    $set_fun = 5;
	} else if (!session_auth()) {
	    $set_fun = 6;
	}
	//2: もとのデータと比較して正しいか確認する
	if ($set_fun != $functionid) {
	    $set_fun = 7;
	}
	return $set_fun;
    }

    /**
     * 実行ファンクションを起こします。
     * 結果は ["CODE" => .., "ERR_TEXT" => '..']
     * CODEについて
     * （0）.. 正常終了
     * （1）.. 異常終了（原因あり）
     * （2）.. 認証が必要（認証を呼び出す）
     * @return array
     */
    public function run() {
	$result_code = 0;
	switch ($this->funid) {
	    case 0:
		$result_code = $this->create();
		break;
	    case 1:
	    case 2:
	    case 3:
	    case 4:
		$result_code = $this->edit();
		break;
	    case 5:
		$result_code = $this->delete();
		break;
	    case 6:
		$result_code = $this->auth();
		break;
	    case 7:
		$result_code = 2;
		break;
	}
	return self::$result_form[$result_code];
    }

    /**
     * アカウントを作成します（作成フラグは以下参照）
     * @return int (0..完了, 1..アカウント認証が必要, 2..セッション切れにより更新中止, 3..データベース障害が発生)
     */
    private function create(): int {
	//1: 全ての値に関してチェックを行う
	//2: authidを確認する
	//3: INSERT文の実行
	//4: 全てのチェックの完了
    }

    private function edit(): int {
	//1: フィルタリングで値を確認する
	//2: authidを確認する
	//3: INSERT文の実行
	//4: 全てのチェックの完了
    }

    private function delete(): int {
	$res_code = 0;
	//1: ユーザ情報を確認する（そのユーザがログイン状態 かつ 自分の場合は削除できません）
	if(!check_users_me()) {
	    return 0;
	}
	//2: authidを確認する
	if (session_auth()) {
	    $res = delete("GSC_USERS", "WHERE USERID = '$this->userid'");
	    if($res) {
		$res_code = 0;
	    } else {
		$res_code = 1;
	    }
	} else {
	    $res_code = 5;
	}
	return $res_code;
    }

    private function check() {
	$chk_text = '<ul class="black-view">[ERROR_LOG]</ul>';
	$chk = '';
	switch ($this->funid) {
	    case 1:
		$chk .= check_userid($this->userid);
		$chk .= check_username($this->username);
		$chk .= check_password($this->pass, $this->r_pass);
		break;
	    case 2:
		$chk .= check_userid($this->userid);
		break;
	    case 3:
		$chk .= check_username($this->username);
		break;
	    case 4:
		$chk .= check_password($this->pass, $this->r_pass);
		break;
	}
	if($chk) {
	    $chk_text = str_replace('[ERROR_LOG]', $chk, $chk_text);
	    $this->result_form[4]['ERR_TEXT'] .= $chk_text;
	    return 4;
	} else {
	    return 0;
	}
    }

}

/**
 * 削除しようとしている情報が自分のユーザであるか確認します。
 * ユーザが自分である場合はfalseを返し、それ以外はtrueを返します。
 * @param string $userid    手続き元のユーザIDを指定します
 * @return bool
 */
function check_users_me($userid): bool {
    if (session_chk()) {
	$session_userid = $_SESSION['gsc_userid'];
	return ($userid == $session_userid);
    } else {
	return false;
    }
}

/**
 * 指定したユーザがログイン中かどうかを判定します。
 * ログイン中である場合は true, でない場合は false が返されます。
 * @return bool
 */
function check_user_login($userid): bool {
    $sql = select(true, 'GSC_USERS', 'LOGINSTATE', "WHERE USERID = '$userid'");
    $res = false;
    if ($sql) {
	$res = ($sql['LOGINSTATE'] == 1);
    }
    return $res;
}

function check_username($data) {
    if (strlen(mb_convert_encoding($data, 'SJIS', 'UTF-8')) > 50) {
	return '<li>ユーザ名が最大半角文字数30文字をを超えています。</li>';
    } else {
	return null;
    }
}

function check_userid($userid) {
    $result = select(true, "GSC_USERS", "COUNT(*) AS USERCOUNT", "WHERE USERID = '$userid'");
    if ($result['USERCOUNT'] == 1) {
	return '<li>ユーザIDが重複しています。</li>';
    }

    if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{5,20}+\z/', $userid)) {
	return '<li>ユーザID入力ルールに違反しています。</li>';
    } else {
	return null;
    }
}

/**
 * パスワードを確認します
 * [半角英数字記号] 10-30文字
 * @param type $pass	パスワード（手続き元）
 * @param type $r_pass	パスワードの確認（手続き元）
 * @return string
 */
function check_password($pass, $r_pass) {
    if (!preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{10,30}+\z/', $pass)) {
	return '<li>パスワードルールに則っていません。</li>';
    }
    if ($pass != $r_pass) {
	return '</li>確認用パスワードが間違っています。</li>';
    } else {
	return null;
    }
}

/**
 * 権限を確認します
 * 権限が正しく選択されていれば良いものとします
 * （0..VCServer, 1..VCHost）
 * @param int $per  権限値（手続き元）
 * @return string
 */
function check_permission($per) {
    if (!isset($per) && (($per == 0) || ($per == 1))) {
	return '<li>権限を選択してください。</li>';
    } else {
	return null;
    }
}
