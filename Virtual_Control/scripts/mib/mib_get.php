<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/mibdata.php';
include_once __DIR__ . '/mibselect.php';
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$data = AGENTData::get_agent_info();
$res = [];
if ($data) {
    $select = new AgentSelect($data);
    $res = $select->getSelect();
} else {
    $log = ob_get_contents();
    $res = ["CODE" => 1, "LOG" => $log];
}
ob_get_clean();
echo json_encode($res);
