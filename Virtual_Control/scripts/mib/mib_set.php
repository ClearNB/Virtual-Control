<?php

/**
 * 
 * エージェント情報に対する変更要求を受け取った際の処理をここで行います
 */
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$functionid = filter_input(INPUT_POST, 'functionid', FILTER_SANITIZE_STRING);
$pre_agentid = filter_input(INPUT_POST, 'pre_agentid', FILTER_SANITIZE_STRING);
$hostaddress = filter_input(INPUT_POST, 'in_ag_ip', FILTER_SANITIZE_STRING);
$community = filter_input(INPUT_POST, 'in_ag_cm', FILTER_SANITIZE_STRING);
$auth_pass = filter_input(INPUT_POST, 'in_ag_ps', FILTER_SANITIZE_STRING);
$mib = filter_input(INPUT_POST, 'in_ag_mb', FILTER_REQUIRE_ARRAY);

ob_get_clean();
echo json_encode($r);
