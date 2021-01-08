<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../session/session_chk.php';
include_once __DIR__ . '/agentdata.php';
include_once __DIR__ . '/agentselect.php';
include_once __DIR__ . '/../mib/mibdata.php';
include_once __DIR__ . '/../mib/mibselect.php';

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header('Location: ../../403.php');
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    $data = AGENTData::get_agent_info();
    $res = [];
    if($data) {
	$select = new AgentSelect($data);
	$data = $select->getSelect();
	$res = ["CODE" => 0, "DATA" => $data];
    } else {
	$log = ob_get_contents();
	$res = ["CODE" => 1, "LOG" => $log];
    }
    ob_get_clean();
    echo json_encode($res);
}