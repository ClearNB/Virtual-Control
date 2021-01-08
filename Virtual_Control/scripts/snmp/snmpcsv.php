<?php

/**
 * [Ajax] SNMPWALK・テーブルデータCSVダウンロード
 * 
 * SNMP専用の取得データをCSVにエンコードし、ダウンロードします。
 * 
 * @author ClearNB
 * @package VirtualControl_scripts_snmp
 */
include_once __DIR__ . '/../general/output.php';

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../../403.php");
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    $data = filter_input(INPUT_POST, 'csvdata', FILTER_SANITIZE_STRING);
    $date = date("YmdHis");
    $filename = 'csvdata-' . $date . '.csv';
    $fil = new File(2, 0, '', $filename, $data);
    $res = $fil->run();
    echo json_encode(['CODE' => 0, 'DATA' => $res, 'NAME' => $filename]);
}