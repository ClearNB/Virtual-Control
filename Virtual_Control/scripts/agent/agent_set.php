<?php

/**
 * 
 * エージェント情報に対する変更要求を受け取った際の処理をここで行います
 */

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
    $functionid = filter_input(INPUT_POST, 'functionid', FILTER_SANITIZE_STRING);
    $pre_agentid = filter_input(INPUT_POST, 'pre_agentid', FILTER_SANITIZE_STRING);
    $hostaddress = filter_input(INPUT_POST, 'in_ag_ip', FILTER_SANITIZE_STRING);
    $community = filter_input(INPUT_POST, 'in_ag_cm', FILTER_SANITIZE_STRING);
    $auth_pass = filter_input(INPUT_POST, 'in_ag_ps', FILTER_SANITIZE_STRING);
    $mib = filter_input(INPUT_POST, 'in_ag_mb', FILTER_REQUIRE_ARRAY);
    
    ob_get_clean();
    echo json_encode($r);
}