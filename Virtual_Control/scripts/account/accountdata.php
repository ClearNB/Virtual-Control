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
	$this->loginuptime = $loginuptime;
	array_push(self::$set, $this);
    }

    public static function get_all_users(): array {
	//SQL Data
	$q01 = select('false', 'GSC_USERS', 'USERID, USERNAME, LOGINUPTIME');
	if ($q01) {
	    
	} else {
	    $result = [];
	    foreach (self::$set as $us) {
		$data = $us->get_user_data();
		array_push($result, $data);
	    }
	    return $result;
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
    }

}
