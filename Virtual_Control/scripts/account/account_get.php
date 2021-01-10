<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../general/table.php';
include_once __DIR__ . '/accountdata.php';
include_once __DIR__ . '/accounttable.php';
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

session_unset_byid('gsc_authid');
$data = ACCOUNTData::get_all_users();
$res = [];
if ($data) {
    $table = new AccountTable($data);
    $res = ["code" => 0, "data" => $table->generate_table(), "a_data" => $data['VALUE']];
} else {
    $res = ["code" => 1, "data" => ob_get_contents()];
}
ob_get_clean();
echo json_encode($res);
