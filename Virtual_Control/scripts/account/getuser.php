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
include_once ('./table_generator.php');

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    $index = filter_input(INPUT_POST, 'index-s', FILTER_SANITIZE_STRING);
    $code = 0;
    $select01 = select(true, 'GSC_USERS', 'USERINDEX, USERNAME', 'WHERE USERINDEX = ' . $index);
    if (!$select01) {
	$code = 1;
    }
    $r = [
	'code' => $code,
	'data' => $select01,
	'a_name' => $select01['USERNAME']
    ];
    ob_get_clean();
    echo json_encode($r);
}