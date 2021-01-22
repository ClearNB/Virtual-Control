<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../general/loader.php';
include_once __DIR__ . '/../general/former.php';
include_once __DIR__ . '/init_c.php';
require_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$d = new initDatabase();
$v = $d->init();
ob_get_clean();
echo json_encode($v);