<?php

include_once __DIR__ . '/warndata.php';
include_once __DIR__ . '/warntable.php';
include_once __DIR__ . '/../general/loader.php';
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

WarnData::load_data();
$data = WarnData::getArray();
$table = new WarnTable($data['VALUE']);
$res_data = $table->getHTML();
$date = date("Y-m-d H:i:s");
$res = ['CODE' => 0, 'SUB' => $res_data['SUB'], 'DATE' => $data['DATE'], 'SELECT' => $res_data['SELECT'], 'CSV' => $data['CSV']];
ob_get_clean();
echo json_encode($res);
