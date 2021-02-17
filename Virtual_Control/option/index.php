<?php //Virtual Control : OPTION (VC-06) by Project GSC
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/general/session.php';

session_action_vcserver();
$getdata = session_get_userdata();

$ld = new loader();
echo $ld->getPage('Virtual Control', 'OPTION', 'wrench', $getdata['PERMISSION'], 'option.js');