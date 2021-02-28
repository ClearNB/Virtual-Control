<?php

include_once __DIR__ . '/../general/sqldata.php';

class AccountData {

    private static $set = [];
    private $userid;
    private $username;
    private $permission;
    private $loginstate;
    private $loginuptime;

    public function __construct($userid, $username, $permission, $loginstate, $loginuptime) {
	$this->userid = $userid;
	$this->username = $username;
	$this->permission = $this->get_permission_text($permission);
	$this->loginstate = $this->get_login_state($loginstate);
	$this->loginuptime = $this->get_time($loginuptime);
	array_push(self::$set, $this);
    }

    public static function get_all_users() {
	$q01 = select(false, 'VC_USERS', 'USERID, USERNAME, PERMISSION, LOGINSTATE, LOGINUPTIME');
	if ($q01) {
	    $result = [
		"COLUMN" => [
		    ["ユーザID", "ユーザ名", "権限", "ログイン状態", "最終ログイン日時"],
		    ["USERID", "USERNAME", "PERMISSION", "LOGINSTATE", "LOGINUPTIME"]
		],
		"VALUE" => []
	    ];
	    while ($var = $q01->fetch_assoc()) {
		new ACCOUNTData($var['USERID'], $var['USERNAME'], $var['PERMISSION'], $var['LOGINSTATE'], $var['LOGINUPTIME']);
	    }
	    foreach (self::$set as $var) {
		if(!empty($var)) {
		    $result['VALUE'][$var->userid] = $var->get_user_data();
		}
	    }
	    return $result;
	} else {
	    return '';
	}
    }

    private function get_user_data(): array {
	return ['USERID' => $this->userid,
	    'USERNAME' => $this->username,
	    'PERMISSION' => $this->permission,
	    'LOGINSTATE' => $this->loginstate,
	    'LOGINUPTIME' => $this->loginuptime];
    }

    private function get_permission_text($permission): string {
	$text = '';
	switch ($permission) {
	    case 0:
		$text = 'VCServer';
		break;
	    case 1:
		$text = 'VCHost';
		break;
	}
	return $text;
    }
    
    private function get_login_state($state): string {
	$text = '';
	switch ($state) {
	    case 0:
		$text = '未ログイン';
		break;
	    case 1:
		$text = 'ログイン中';
		break;
	}
	return $text;
    }

    private function get_time($date): string {
	if ($date) {
	    return $date;
	} else {
	    return '<未ログイン>';
	}
    }

}
