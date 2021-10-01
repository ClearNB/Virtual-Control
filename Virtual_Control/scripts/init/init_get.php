<?php

include_once __DIR__ . '/../../data/new_database.php';
include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../mib/mib_data.php';

class initGet {

    private $userid;
    private $pass;

    public function init() {
	$tabledata = getDBSet();
	
	$tables = array_reverse(array_column($tabledata, 'NAME'));
	
	//1: テーブル削除
	$flag = dropAllTable($tables);

	if ($flag) {
	    //2: テーブル追加
	    
	    $check = $this->createSet($tabledata, ['NAME', 'COLUMN']);

	    //3: テーブルデータ挿入（USER）
	    $userdata = $this->addUserData(getUserData());
	    $check &= $this->insertSet($userdata);

	    //4: テーブルデータ挿入(AGENT)
	    $agentdata = getAgentData();
	    $check &= $this->insertSet($agentdata);

	    //5: テーブルデータ挿入(ICON)
	    $icondata = getIconData();
	    $check &= $this->insertSet($icondata);

	    //6: テーブルデータ挿入(MIB)
	    $mibdata = getMIB2Data();
	    $check &= $this->insertSet($mibdata);

	    //7: テーブルデータ挿入(MIB_GROUP)
	    $groupdata = getMIBGroupData();
	    foreach ($groupdata as $g) {
		$check &= createMIBGroup($g[0], $g[1]);
		if (!$check) {
		    break;
		}
	    }

	    //8: テーブルデータ挿入(MIB_CHANGE)
	    $changedata = getMIBChangeData();
	    $check &= $this->insertSelectSet($changedata);

	    //9: テーブルデータ挿入(AGENT_MIB)
	    $agentmibdata = getAgentMIBData();
	    $check &= $this->insertSelectSet($agentmibdata);

	    if (!$check) {
		dropAllTable($tables);
	    }
	    $res = ['CODE' => ($check) ? 1 : 2, 'DATA' => ($check) ? ['USERID' => $this->userid, 'PASS' => $this->pass] : ob_get_contents()];
	} else {
	    $res = ['CODE' => 2, 'DATA' => 'テーブルの削除に失敗しました。参照エラーです。'];
	}
	return $res;
    }

    private function createSet($data, $column_set = '') {
	$r_flag = true;
	foreach ($data as $var) {
	    if($column_set) {
		$var = [$var[$column_set[0]], $var[$column_set[1]]];
	    } 
	    $r_flag = create($var[0], $var[1]);
	    if (!$r_flag) {
		break;
	    }
	}
	return $r_flag;
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

    private function insertSelectSet($data) {
	$r_flag = true;
	foreach ($data as $var) {
	    $r_flag = insert_onvalue($var[0], $var[1], $var[2], $var[3]);
	    if (!$r_flag) {
		break;
	    }
	}
	return $r_flag;
    }

    private function addUserData($data) {
	$r_userid = random(2);
	$r_pass = random(5);
	$r_pass2 = random(6);
	$this->userid = str_replace('[USERID]', strval($r_userid), $data[0]);
	$this->pass = str_replace('[PASS1]', strval($r_pass2), str_replace('[PASS2]', strval($r_pass), $data[1]));
	$username = $data[2];
	$permission = $data[3];

	$salt = random(20);

	$pass_hash = hash('sha256', $this->pass . $salt);
	return [["VC_USER", ["USERID", "PASSWORDHASH", "USERNAME", "PERMISSION", "SALT"], [$this->userid, $pass_hash, $username, $permission, $salt]]];
    }

}
