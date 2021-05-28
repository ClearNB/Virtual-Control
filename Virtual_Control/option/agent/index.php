<?php //Virtual Control : OPTION - AGENT (VC-08) by Project GSC
include_once __DIR__ . '/../../scripts/general/loader.php';
include_once __DIR__ . '/../../scripts/general/session.php';

session_action_vcserver();
$getdata = session_get_userdata();

$ld = new loader();
$ld->getPage('Virtual Control', 'OPTION - AGENT', 'server', $getdata['PERMISSION'], 'option_agent.js');