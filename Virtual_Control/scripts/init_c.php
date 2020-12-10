<?php

class initDatabase {

    function init() {
	//研修内容データのすべてのデータの吸出し
	$file = "/data/data_format.json";
	$format_data = loadfile($file);
	if (!$format_data) {
	    return 1;
	}
	$result = setTableStatus($format_data['tables']);
	if ($result) {
	    delete('GSC_USERS');
	    delete('GSC_MIB_SUB');
	    delete('GSC_MIB_NODE');
	    delete('GSC_MIB_GROUP');

	    reset_auto_increment('GSC_MIB_NODE');
	    reset_auto_increment('GSC_MIB_GROUP');

	    $user_data = [$this->generateUserData($format_data['USERS_SET'])];
	    
	    $e_s = [$user_data, 
		$format_data['MIB_GROUP_SET'],
		$format_data['MIB_SUB_SET'],
		$format_data['MIB_NODE_SYSTEM'],
		$format_data['MIB_NODE_INTERFACE'],
		$format_data['MIB_NODE_IP'],
		$format_data['MIB_NODE_SNMP']];
	    $f = 0;
	    foreach ($e_s as $e) {
		$f += $this->insertSet($e);
	    }
	    if ($f > 0) {
		$f = 1;
	    }
	    return $f;
	}
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
	$userid = $data[0];
	$pass = $data[1];
	$username = $data[2];
	$permission = $data[3];
	
	$salt = random(20);
	
	$pass_hash = hash('sha256', $pass . $salt);
	return ["GSC_USERS", ["USERID", "PASSWORDHASH", "USERNAME", "PERMISSION", "SALT"], [$userid, $pass_hash, $username, $permission, $salt]];
    }
}
