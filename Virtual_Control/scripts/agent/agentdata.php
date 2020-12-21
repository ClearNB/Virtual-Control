<?php

include_once ('../general/sqldata.php');

class ACCOUNTData {

    private static $set = [];
    private $userid;
    private $username;
    private $permission;
    private $loginuptime;

    public function __construct($userid, $username, $permission, $loginuptime) {
	$this->userid = $userid;
	$this->username = $username;
	$this->permission = $this->get_permission_text($permission);
	$this->loginuptime = $this->get_time($loginuptime);
	array_push(self::$set, $this);
    }

    public static function get_all_users() {
	$q01 = select(false, 'GSC_USERS', 'USERID, USERNAME, PERMISSION, LOGINUPTIME');
	if ($q01) {
	    $result = [
		"COLUMN" => [
		    ["ユーザID", "ユーザ名", "権限", "最終ログイン日時"],
		    ["USERID", "USERNAME", "PERMISSION", "LOGINUPTIME"]
		],
		"VALUE" => []
	    ];
	    while ($var = $q01->fetch_assoc()) {
		new ACCOUNTData($var['USERID'], $var['USERNAME'], $var['PERMISSION'], $var['LOGINUPTIME']);
	    }
	    foreach (self::$set as $var) {
		if(!empty($var)) {
		    array_push($result['VALUE'], $var->get_user_data());
		}
	    }
	    return $result;
	} else {
	    return false;
	}
    }

    private function get_user_data(): array {
	return ['USERID' => $this->userid,
	    'USERNAME' => $this->username,
	    'PERMISSION' => $this->permission,
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

    private function get_time($date): string {
	if ($date) {
	    return $date;
	} else {
	    return '<未ログイン>';
	}
    }
}