<?php

class AccountSet {
    private $userid;
    private $username;
    private $pass;
    private $r_pass;
    private $per;
    
    public function __construct($functionid, $userid, $username, $pass, $r_pass, $per) {
	$this->userid = $userid;
	$this->username = $username;
	$this->pass = $pass;
	$this->r_pass = $r_pass;
	$this->per = $per;
	switch($functionid) {
	    case 0:
		break;
	    case 1:
		break;
	    case 2:
		break;
	}
    }
    
    /**
     * アカウントを作成します（作成フラグは以下参照）
     * @return int (0..完了, 1..アカウント認証が必要, 2..セッション切れにより更新中止, 3..データベース障害が発生)
     */
    public function create():int {
	//1: 全ての値に関してチェックを行う
	
	//2: authidを確認する
	
	//3: INSERT文の実行
	
	//4: 全てのチェックの完了
    }
    
    public function edit() {
	
    }
    
    public function delete() {
	
    }
    
    public function check($checkid) {
	switch($checkid) {
	    case 0: //all
		break;
	    case 1: //edit
		break;
	    case 2: //delete
		break;
	}
    }
}

function check_username($data) {
    if(strlen(mb_convert_encoding($data, 'SJIS', 'UTF-8')) > 50) {
        return '・ユーザ名が最大半角文字数30文字をを超えています。';
    } else {
        return null;
    }
}

function check_userid($data) {
    $result = select(true, "GSC_USERS", "COUNT(*) AS USERCOUNT", "WHERE USERID = '$data'");
    if($result['USERCOUNT'] == 1) {
        return '・ユーザIDが重複しています。';
    } else {
        return null;
    }
    
    if(!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{5,20}+\z/', $data)) {
        return '・ユーザID入力ルールに違反しています。';
    } else {
        return null;
    }
}

function check_password($data) {
    if(!preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{10,30}+\z/', $data)) {
        return '・パスワードルールに則っていません。';
    } else {
        return null;
    }
}

function check_conf_password($data1, $data2) {
    if($data1 != $data2) {
        return '・確認用パスワードが間違っています。';
    } else {
        return '';
    }
}

function check_permission($data) {
    if(!isset($data)) {
        return '・権限を選択してください。';
    } else {
        return '';
    }
}