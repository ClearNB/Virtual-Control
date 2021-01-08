<?php

include_once __DIR__ . '/../general/output.php';

class initDatabase {

    private $userid;
    private $pass;

    function init() {
	//研修内容データのすべてのデータの吸出し
	$res = [];
	$f = 0;

	$file_path = __DIR__ . '/../../data';
	$filename = 'data_format.json';
	$fi = new File(0, 1, $file_path, $filename, '');
	$format_data = $fi->run();
	if (!$format_data) {
	    return 1;
	}
	$res01 = dropAllTable($format_data['TABLES']);
	$res02 = setTableStatus($format_data['TABLES']);
	if ($res01 && $res02) {
	    $user_data = [$this->generateUserData($format_data['USERS_SET'])];
	    $e_s = [$user_data, $format_data['MIB_GROUP_SET'], $format_data['MIB_SUB_SET'], $format_data['MIB_NODE_SET'], $format_data['AGENT_SET'], $format_data['AGENT_MIB_SET'], $format_data['ICONS']];
	    foreach ($e_s as $e) {
		$f += $this->insertSet($e);
	    }
	    if ($f > 0) {
		$f = 1;
	    }
	} else {
	    $f = 1;
	}
	switch ($f) {
	    case 0: $res = ['CODE' => $f, 'USERID' => $this->userid, 'PASS' => $this->pass];
		break;
	    case 1: $res = ['CODE' => $f, 'ERROR' => ob_get_contents()];
		break;
	}
	return $res;
    }

    function insertSet($data) {
	$r_flag = true;
	foreach ($data as $var) {
	    $r_flag = insert($var[0], $var[1], $var[2]);
	    if (!$r_flag) {
		break;
	    }
	}
	if (!$r_flag) {
	    return 1;
	} else {
	    return 0;
	}
    }

    function generateUserData($data) {
	$r_userid = random(2);
	$r_pass = random(5);
	$this->userid = str_replace('[USERID]', strval($r_userid), $data[0]);
	$this->pass = str_replace('[PASS]', strval($r_pass), $data[1]);
	$username = $data[2];
	$permission = $data[3];

	$salt = random(20);

	$pass_hash = hash('sha256', $this->pass . $salt);
	return ["GSC_USERS", ["USERID", "PASSWORDHASH", "USERNAME", "PERMISSION", "SALT"], [$this->userid, $pass_hash, $username, $permission, $salt]];
    }

}
