<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/agentdata.php';
include_once __DIR__ . '/agentselect.php';
include_once __DIR__ . '/../mib/mibdata.php';
include_once __DIR__ . '/../mib/mibselect.php';
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$data = AGENTData::get_agent_info();
$res = [];
if ($data) {
    $select = new AgentSelect($data);
    $data = $select->getSelect();
    $res = ["CODE" => 0, "DATA" => $data];
} else {
    $log = ob_get_contents();
    $res = ["CODE" => 1, "LOG" => $log];
}
ob_get_clean();
echo json_encode($res);
