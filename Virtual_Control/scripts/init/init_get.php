<?php

include_once __DIR__ . '/../general/output.php';

class initGet {
    private $userid;
    private $pass;

    public function init() {
	$file_path = __DIR__ . '/../../data';
	$filename = 'data_format.json';
	$fi = new File(0, 1, $file_path, $filename, '');
	$format_data = $fi->run();
	$f = (isset($format_data));
	if ($f) {
	    $res01 = dropAllTable($format_data['TABLES']);
	    $res02 = setTableStatus($format_data['TABLES']);
	    if ($res01 && $res02) {
		$user_data = [$this->generateUserData($format_data['USERS_SET'])];
		$e_s = [$user_data, $format_data['MIB_GROUP_SET'], $format_data['MIB_SUB_SET'], $format_data['MIB_NODE_SET'], $format_data['AGENT_SET'], $format_data['AGENT_MIB_SET'], $format_data['ICONS']];
		foreach ($e_s as $e) {
		    $f &= $this->insertSet($e);
		    if(!$f) {
			break;
		    }
		}
	    }
	}
	$res = ['CODE' => ($f) ? 1 : 2, 'DATA' => ($f) ? ['USERID' => $this->userid, 'PASS' => $this->pass] : ob_get_contents()];
	return $res;
    }

    private function insertSet($data) {
	$r_flag = true;
	foreach ($data as $var) {
	    $r_flag = insert($var[0], $var[1], $var[2]);
	    if (!$r_flag) {
		break;
	    }
	}
	return $r_flag;
    }

    private function generateUserData($data) {
	$r_userid = random(2);
	$r_pass = random(5);
	$this->userid = str_replace('[USERID]', strval($r_userid), $data[0]);
	$this->pass = str_replace('[PASS]', strval($r_pass), $data[1]);
	$username = $data[2];
	$permission = $data[3];

	$salt = random(20);

	$pass_hash = hash('sha256', $this->pass . $salt);
	return ["VC_USERS", ["USERID", "PASSWORDHASH", "USERNAME", "PERMISSION", "SALT"], [$this->userid, $pass_hash, $username, $permission, $salt]];
    }
}
