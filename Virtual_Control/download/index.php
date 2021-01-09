<?php

/**
 * [Ajax] SNMPWALK・テーブルデータCSVダウンロード
 * 
 * SNMP専用の取得データをCSVにエンコードし、ダウンロードします。
 * 
 * @author ClearNB
 * @package VirtualControl_scripts_snmp
 */
include_once __DIR__ . '/../scripts/general/output.php';
include_once __DIR__ . '/../scripts/session/session_chk.php';

if (session_chk() == 1) {
    header("Location: ../403.php");
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    $data = filter_input(INPUT_POST, 'download-data', FILTER_SANITIZE_STRING);
    $filename = filter_input(INPUT_POST, 'file-name', FILTER_SANITIZE_STRING);
    if ($data && $filename) {
	$date = date("YmdHis");
	$c_filename = str_replace('[DATE]', $date, $filename);
	$fil = new File(2, 0, '', $c_filename, $data);
	$res = $fil->run();
	exit;
    } else {
	header("Location: ../403.php");
    }
} else {
    header("Location: ../403.php");
}