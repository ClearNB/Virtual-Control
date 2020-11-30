<?php

include_once ('./scripts/session_chk.php');
include_once ('./scripts/common.php');
include_once ('./scripts/sqldata.php');
include_once ('./scripts/loader.php');
include_once ('./scripts/former.php');
include_once ('./scripts/init_c.php');

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