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
include_once __DIR__ . '/../scripts/general/session.php';

if (session_chk() != 0) {
    header("Location: /error.php");
} else {
    $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
    if ($method === 'POST') {
	$data = post_get_data('download-data');
	$filename = post_get_data('file-name');
	if ($data && $filename) {
	    $date = date("YmdHis");
	    $c_filename = str_replace('[DATE]', $date, $filename);
	    $fil = new File(2, 0, '', $c_filename, $data);
	    $res = $fil->run();
	} else {
	    header("Location: /error.php");
	}
    } else {
	header("Location: /error.php");
    }
}
exit();
