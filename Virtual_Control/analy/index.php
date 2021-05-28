<?php //Virtual Control : ANALY (VC-04) by Project GSC
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/general/session.php';

session_action_user();
$getdata = session_get_userdata();

$ld = new loader();
$ld->getPage('Virtual Control', 'ANALY', 'chart-bar', $getdata['PERMISSION'], 'analy.js');