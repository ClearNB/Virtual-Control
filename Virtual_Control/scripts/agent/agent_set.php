<?php

/**
 * 
 * エージェント情報に対する変更要求を受け取った際の処理をここで行います
 */
include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/agentfunction.php';
require_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$functionid = filter_input(INPUT_POST, 'functionid', FILTER_SANITIZE_STRING);
$pre_agentid = filter_input(INPUT_POST, 'pre_agentid', FILTER_SANITIZE_STRING);
$agenthost = filter_input(INPUT_POST, 'in_ag_hs', FILTER_SANITIZE_STRING);
$community = filter_input(INPUT_POST, 'in_ag_cm', FILTER_SANITIZE_STRING);
$a_pass = filter_input(INPUT_POST, 'in_at_ps', FILTER_SANITIZE_STRING);
$mib = filter_input(INPUT_POST, 'sl_mb', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

$set = new AgentSet($functionid, $a_pass, $agenthost, $pre_agentid, $community, $mib);
$set->check_functionid();
$r = $set->run();

//ob_get_clean();
echo json_encode($r);
