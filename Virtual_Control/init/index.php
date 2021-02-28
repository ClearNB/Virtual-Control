<?php //Virtual Control : INIT (VC-10) by Project GSC
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/general/session.php';

session_action_init();

$ld = new loader();
$ld->getPage('Virtual Control', 'INIT', 'align-justify', 999, 'init.js');