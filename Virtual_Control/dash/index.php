<?php //Virtual Control : DASH (VC-03) by Project GSC
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/session/session_chk.php';

session_action_user();
$getdata = session_get_userdata();

$ld = new loader();
echo $ld->getPage('Virtual Control', 'DASH', 'align-justify', $getdata['PERMISSION'], 'dash.js');