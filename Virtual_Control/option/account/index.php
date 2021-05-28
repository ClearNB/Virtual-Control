<?php //Virtual Control : OPTION - ACCOUNT (VC-07) by Project GSC
include_once __DIR__ . '/../../scripts/general/loader.php';
include_once __DIR__ . '/../../scripts/general/session.php';

session_action_vcserver();
$getdata = session_get_userdata();

$ld = new loader();
$ld->getPage('Virtual Control', 'OPTION - ACCOUNT', 'user', $getdata['PERMISSION'], 'option_account.js');