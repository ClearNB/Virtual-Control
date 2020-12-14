<?php

include_once ('./scripts/general/sqldata.php');
include_once ('./scripts/session/session_chk.php');
include_once ('./scripts/general/loader.php');
include_once ('./scripts/general/former.php');
include_once ('./scripts/general/init_c.php');

$d = new initDatabase();
$v = $d->init();

switch($v) {
    case 0:
	echo "成功！";
	break;
    case 1:
	echo "失敗...";
	break;
}