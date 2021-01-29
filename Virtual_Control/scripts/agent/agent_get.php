<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/agentdata.php';
include_once __DIR__ . '/agent_page.php';
include_once __DIR__ . '/agentselect.php';
include_once __DIR__ . '/agentfunction.php';
include_once __DIR__ . '/../session/session_chk.php';
include_once __DIR__ . '/../mib/mibdata.php';
include_once __DIR__ . '/../mib/mibselect.php';

session_action_scripts();

$f_id = post_get_data('f_id');
$d_tp = post_get_data('d_tp');
$p_id = post_get_data('p_id');
$agenthost = post_get_data('in_ag_hs');
$community = post_get_data('in_ag_cm');
$mib = post_get_data_array('sl_mb');
$a_pass = post_get_data('in_at_ps');

$res = ['SELECTED' => 0, 'PAGE' => ''];

$page = new AgentPage();
$result_page = $page->getFail();

if ($f_id && session_chk() == 0) {
    $mibdata = new MIBData();
    $subdata = $mibdata->getMIB(0);

    if ($subdata) {
	$code = 0;
	switch ($f_id) {
	    case 11: //SELECT
		initialize();
		$data = AgentData::get_agent_info();
		if ($data) {
		    session_create('gsc_agent', $data);
		    $a_s = new AgentSelect($data);
		    $agent_select = $a_s->getSelect();
		    $result_page = $page->getSelect($agent_select);
		}
		break;
	    case 12: //CREATE
		if ($d_tp == 1) {
		    $data = get_sdata();
		    $res = set_function(0, $data, ['F_ID' => $f_id, 'P_ID' => $p_id, 'AGENTHOST' => $agenthost, 'COMMUNITY' => $community, 'MIB' => $mib, 'A_PASS' => $a_pass]);
		} else if ($d_tp == 0) {
		    $s = new MIBSubSelect($subdata);
		    $select = $s->getSubSelectClear();
		    $result_page = $page->getCreate($select);
		}
		break;
	    case 13: //EDIT SELECT
		if ($d_tp == 0 && $p_id) {
		    $data = set_selectdata(1, $p_id);
		    if ($data) {
			$result_page = $page->getEditSelect($data['SELECT']);
		    }
		} else if ($d_tp == 0) {
		    $data = get_sdata();
		    if (isset($data['SELECT'])) {
			$result_page = $page->getEditSelect($data['SELECT']);
		    }
		}
		break;
	    case 14: //EDIT HOST
	    case 15: //EDIT COMMUNITY
	    case 16: //EDIT OID
		$data = get_sdata();
		if ($d_tp == 1) {
		    $res = set_function(1, $data, ['F_ID' => $f_id, 'P_ID' => $data['SELECT']['P_ID'], 'AGENTHOST' => $agenthost, 'COMMUNITY' => $community, 'MIB' => $mib, 'A_PASS' => $a_pass]);
		} else if ($d_tp == 0) {
		    $s = new MIBSubSelect($subdata);
		    $select = $s->getSubSelectOnAgent($data['SELECT']['SUBID']);
		    $result_page = $page->getEdit($f_id, $data['SELECT'], $select);
		}
		break;
	    case 17; //DELETE
		$data = get_sdata();
		if ($d_tp == 1) {
		    $res = set_function(2, $data, ['F_ID' => $f_id, 'P_ID' => $data['SELECT']['P_ID'], 'AGENTHOST' => $agenthost, 'COMMUNITY' => $community, 'MIB' => $mib, 'A_PASS' => $a_pass]);
		} else if ($d_tp == 0 && $p_id) {
		    $data = set_selectdata(2, $p_id);
		    $result_page = $page->getDelete($data['SELECT']);
		}
		break;
	}
    }
}
if ($res['SELECTED'] != 1) {
    $res['PAGE'] = $result_page;
}

if (isset($res['SELECTED'])) {
    unset($res['SELECTED']);
}

//ob_get_clean();
echo json_encode($res);

function initialize() {
    session_unset_byid('gsc_agent');
    session_unset_byid('gsc_authid');
}

function get_sdata() {
    return session_get('gsc_agent');
}

function get_datatype($type) {
    $type_text = '';
    switch ($type) {
	case 0:
	    $type_text = 'CREATE';
	    break;
	case 1:
	    $type_text = 'EDIT';
	    break;
	case 2:
	    $type_text = 'DELETE';
	    break;
    }
    return $type_text;
}

function set_selectdata($type, $id) {
    $type_text = get_datatype($type);
    $data = get_sdata();
    $data['SELECT'] = (isset($data['VALUE'][$id])) ? $data['VALUE'][$id] : '';
    if (isset($data['SELECT'])) {
	$data['SELECT'][$type_text]['P_ID'] = $id;
	$data['SELECT']['P_ID'] = $id;
    }
    session_unset_byid('gsc_agent');
    session_create('gsc_agent', $data);
    return $data;
}

function set_agentdata($type, $value) {
    $type_text = get_datatype($type);
    $data = '';
    if ($type_text) {
	$data = get_sdata();
	foreach ($value as $k => $v) {
	    if (!isset($data['SELECT'][$type_text][$k]) || $v) {
		$data['SELECT'][$type_text][$k] = $v;
	    }
	}
	session_unset_byid('gsc_agent');
	session_create('gsc_agent', $data);
    }
    return $data;
}

function set_function($type, $data, $values) {
    $res = '';
    $type_text = get_datatype($type);
    $page = new AgentPage();
    if (($type == 0 || isset($data['SELECT'])) && $type_text) {
	$si_data = set_agentdata($type, $values);
	if ($si_data) {
	    $s_data = $si_data['SELECT'][$type_text];
	    $set = new AgentSet($s_data['F_ID'], $s_data['A_PASS'], $s_data['AGENTHOST'], $s_data['P_ID'], $s_data['COMMUNITY'], $s_data['MIB']);
	    $set->check_functionid();
	    $runner = $set->run();
	    switch ($runner['CODE']) {
		case 0: $res = ['SELECTED' => 1, 'PAGE' => $page->getCorrect()];
		    break;
		case 1: $res = ['SELECTED' => 1, 'PAGE' => $page->getFail('データベースサーバへの接続に失敗しました。')];
		    break;
		case 2: $res = ['SELECTED' => 1, 'ID' => 'fm_warn', 'CODE' => 2, 'PAGE' => $runner['ERR_TEXT']];
		    break;
		case 3: $res = ['SELECTED' => 1, 'PAGE' => $page->getFail()];
		    break;
		case 4: $res = ['SELECTED' => 1, 'PAGE' => $page->fm_at()];
		    break;
		case 5: $res = ['SELECTED' => 1, 'ID' => 'fm_warn', 'CODE' => 2, 'PAGE' => '認証エラーが発生しました。'];
		    break;
		case 6:
		    $agt_data = ($type == 1 || $type == 2) ? '【' . $si_data['SELECT']['AGENTHOST'] . '】' . $si_data['SELECT']['COMMUNITY'] : '';
		    $runner['CONFIRM_DATA'] = str_replace('[AGENT_INFO]', $agt_data, $runner['CONFIRM_DATA']);
		    $res = ['SELECTED' => 1, 'PAGE' => $page->getConfirm($runner['CONFIRM_DATA'])];
		    break;
	    }
	}
    }
    return $res;
}
