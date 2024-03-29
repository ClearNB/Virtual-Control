<?php

/**
 * [Ajax] SNMPWALK・テーブルデータ表示
 * 
 * SNMPWALK専用のファンクションです。SNMP取得およびテーブル表示化を行います。
 * 
 * @author ClearNB
 * @package VirtualControl_scripts_snmp
 */
include_once __DIR__ . '/analy_page.php';
include_once __DIR__ . '/analy_get.php';
include_once __DIR__ . '/../general/session.php';

ini_set('display_errors', 0);
session_action_scripts();

$res = ['PAGE' => ''];

$f_id = post_get_data('f_id');
$get = new AnalyGet($f_id);
$response = $get->run();
$page = new AnalyPage($response['CODE'], $response['DATA']);
$res['PAGE'] = $page->getPage();
$res['CODE'] = 0;

if($response['CODE'] >= 10 && $response['CODE'] != 999) {
    $res['CODE'] = 1;
}

echo json_encode($res);
