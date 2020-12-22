<?php

/**
 * 
 * アカウント情報に対する変更要求を受け取った際の処理をここで行います
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
    $pre_userid = filter_input(INPUT_POST, 'pre_userid', FILTER_SANITIZE_STRING);
    $userid = filter_input(INPUT_POST, 'in_ac_ui', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'in_ac_un', FILTER_SANITIZE_STRING);
    $permission = filter_input(INPUT_POST, 'in_ac_pr', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'in_ac_ps', FILTER_SANITIZE_STRING);
    $r_pass = filter_input(INPUT_POST, 'in_ac_ps_rp', FILTER_SANITIZE_STRING);
    
    ob_get_clean();
    echo json_encode($r);
}