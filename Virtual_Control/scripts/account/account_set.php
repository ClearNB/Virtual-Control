<?php

/**
 * [AJAX] Account Setting
 * 
 * アカウント情報に対する変更要求を受け取った際の処理をここで行います
 * 
 * @package VirtualControl_scripts_account
 */

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header('Location: ../../403.php');
    exit;
}

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/accountfunction.php';
include_once __DIR__ . '/../session/session_chk.php';

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    $functionid = filter_input(INPUT_POST, 'functionid', FILTER_SANITIZE_STRING);
    $pre_userid = filter_input(INPUT_POST, 'pre_userid', FILTER_SANITIZE_STRING);
    $userid = filter_input(INPUT_POST, 'in_ac_id', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'in_ac_nm', FILTER_SANITIZE_STRING);
    $permission = filter_input(INPUT_POST, 'in_ac_pr', FILTER_SANITIZE_STRING);
    $a_pass = filter_input(INPUT_POST, 'in_at_ps', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_POST, 'in_ac_ps', FILTER_SANITIZE_STRING);
    $r_pass = filter_input(INPUT_POST, 'in_ac_ps_rp', FILTER_SANITIZE_STRING);
    
    $set = new AccountSet($functionid, $pre_userid, $userid, $username, $a_pass, $pass, $r_pass, $permission);
    
    $set->check_functionid();
    
    $data = $set->run();
    
    //ob_get_clean();
    echo json_encode($data);
}