<?php

/**
 * [Ajax] SNMPWALK・テーブルデータ表示
 * 
 * SNMPWALK専用のファンクションです。SNMP取得およびテーブル表示化を行います。
 * 
 * @author ClearNB
 * @package VirtualControl_scripts_snmp
 */
include_once __DIR__ . '/../agent/agentdata.php';
include_once __DIR__ . '/../agent/agentselect.php';
include_once __DIR__ . '/../snmp/snmp_walk.php';
include_once __DIR__ . '/analy_page.php';
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$loader = new loader();

//取得データ
$f_id = post_get_data('f_id');
$agent = post_get_data('sl_agt');
$sub = post_get_data('sl_sub');

$res = [];
$page = new AnalyPage();
$result_page = $page->getFail();

if ($f_id && session_chk() == 0) {
    $page->reset();

    if ($f_id == 51) { //AGENT SELECT
	initialize();
	$agentdata = AGENTData::get_agent_info();
	$sl = new AgentSelect($agentdata);
	$agentselect = $sl->getSelect();
	$result_page = $page->getAgentSelect($agentselect);
    } else if ($f_id == 52 && $agent) { //SNMPWALK
	$data = get_walk_result($agent);
	switch ($data['CODE']) {
	    case 0:
		session_create('gsc_analy_result', $data);
		$result_page = $page->getWalkResult($data['DATE'], $data['HOST'], $data['COMMUNITY'], $data['LIST'], $data['SIZE']);
		$res['CSV'] = $data['CSV'];
		break;
	    case 1:
	    case 2:
		$result_page = $page->getFailSNMPWALK($data['LOG']);
		break;
	}
    } else if ($f_id == 53 && $sub) {  //SUB RESULT
	$data = session_get('gsc_analy_result');
	if (isset($data['SUBDATA'][$sub])) {
	    $result_page = $page->getSubResult($data['HOST'], $data['COMMUNITY'], $data['SUBDATA'][$sub]['MIB'], $data['DATE'], $data['SUBDATA'][$sub]['TABLE'], $data['SUBDATA'][$sub]['ERROR'], $data['SUBDATA'][$sub]['SIZE']);
	}
    } else if ($f_id == 54) {  //BACK RESULT
	$data = session_get('gsc_analy_result');
	if ($data) {
	    $result_page = $page->getWalkResult($data['DATE'], $data['HOST'], $data['COMMUNITY'], $data['LIST'], $data['SIZE']);
	}
    } else if ($f_id == 55) {
	$agentdata = session_get('gsc_analy_result');
	if (isset($agentdata['AGENTID'])) {
	    $agentid = $agentdata['AGENTID'];
	    $data = get_walk_result($agentid);
	    switch ($data['CODE']) {
		case 0:
		    session_create('gsc_analy_result', $data);
		    $result_page = $page->getWalkResult($data['DATE'], $data['HOST'], $data['COMMUNITY'], $data['LIST'], $data['SIZE']);
		    $res['CSV'] = $data['CSV'];
		    break;
		case 1:
		case 2:
		    $result_page = $page->getFailSNMPWALK($data['LOG']);
		    break;
	    }
	}
    }
}
$res['PAGE'] = $result_page;
ob_get_clean();
echo json_encode($res);

function initialize() {
    session_unset_byid('gsc_analy_result');
}

function get_result() {
    return session_get('gsc_analy_result');
}
