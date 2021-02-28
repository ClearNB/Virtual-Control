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
	$funid = post_get_data('f_id');
	$res_data = ['CODE' => 3, 'NAME' => 'INVALID', 'DATA' => ''];
	switch($funid) {
	    case 36: //ANALY
		$res_data = ['CODE' => 1, 'NAME' => 'vc-analy-getdata-[DATE].csv', 'DATA' => session_get('vc_analy')];
		break;
	    case 45: //WARN
		$res_data = ['CODE' => 2, 'NAME' => 'vc-warn-getdata-[DATE].csv', 'DATA' => session_get('vc_warn')];
		break;
	}
	if ($res_data['CODE'] != 3 && isset($res_data['DATA']['CSV'])) {
	    $date = date("YmdHis");
	    $c_filename = str_replace('[DATE]', $date, $res_data['NAME']);
	    $fil = new File(2, 0, '', $c_filename, $res_data['DATA']['CSV']);
	    $res = $fil->run();
	} else {
	    header("Location: /error.php");
	}
    } else {
	header("Location: /error.php");
    }
}
exit();
