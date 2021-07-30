<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../general/session.php';
include_once __DIR__ . '/agent_get.php';

session_action_scripts();

$f_id = post_get_data('f_id');

$get = new AgentGet($f_id);
$res = $get->run();

$page = new AgentPage($res['CODE'], $res['DATA']);

$resdata = ['ID' => 'data_output', 'PAGE' => $page->getPage()];

if($res['CODE'] == 11 || $res['CODE'] == 12 || $res['CODE'] == 14) {
    $resdata['ID'] = 'fm_warn';
}

echo json_encode($resdata);