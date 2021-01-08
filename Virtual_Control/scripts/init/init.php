<?php

include_once ('../general/sqldata.php');
include_once ('../session/session_chk.php');
include_once ('../general/loader.php');
include_once ('../general/former.php');
include_once ('./init_c.php');

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../../403.php");
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    $d = new initDatabase();
    $v = $d->init();
    ob_get_clean();
    echo json_encode($v);
}