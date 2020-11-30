<?php

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header('Location: ../../403.php');
    exit;
}

include_once ('../sqldata.php');
include_once ('../common.php');
include_once ('../dbconfig.php');
include_once ('./checkers.php');
include_once ('../session_chk.php');

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    $checklist = filter_input(INPUT_POST, 'index-s', FILTER_SANITIZE_STRING);
    $select = select(true, 'MKTK_USERS', 'USERINDEX, USERNAME', 'WHERE USERID = \'' . $checklist . '\'');
    $code = 0;
    if (!$select) {
	$code = 1;
    } else {
	if(session_chk() === 0) {
	    $index = $_SESSION['mktk_userindex'];
	    if($index == $select['USERINDEX']) {
		$code = 2;
	    } else {
		$select01 = select(true, 'MKTK_USERS', 'USERNAME', 'WHERE USERINDEX = ' . $index);
		if(!$select01) {
		    $code = 1;
		}
	    }
	} else {
	    $code = 1;
	}
    }
    $r = [
	'code' => $code,
	'data' => $select,
	'a_name' => $select01['USERNAME']
    ];
    ob_get_clean();
    echo json_encode($r);
}