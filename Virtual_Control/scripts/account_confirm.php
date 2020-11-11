<?php

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg)
        ? strtolower($requestmg) : '';
if($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../403.php");
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'GET') {
    
    //1. データの取得
    $userid = filter_input(INPUT_GET, 'userid', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRING);
    $mailAddress = filter_input(INPUT_GET, 'mailAddress', FILTER_SANITIZE_STRING);
    $pass = filter_input(INPUT_GET, 'pass', FILTER_SANITIZE_STRING);
    $r_pass = filter_input(INPUT_GET, 'r-pass', FILTER_SANITIZE_STRING);
    $permission = filter_input(INPUT_GET, 'per-radio', FILTER_SANITIZE_STRING);
    
    //2. データの確認（不備）
    /* [判断基準]
     * 上記いずれかの値がなかった場合 ... 警告
     */
    
    
    //3. データの確認（SQL）
    
    /* [判断基準]
     * 1. UserIDに被りがあった場合 ... 警告
     * 2. 
     * 3. パスワードの確認が違った場合 ... 警告
     */
    
    //4. 認証要求を返す
    
}