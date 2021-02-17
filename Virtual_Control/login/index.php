<?php //Virtual Control : LOGIN (VC-02) by Project GSC
include_once __DIR__ . '/../scripts/general/loader.php';
include_once __DIR__ . '/../scripts/general/session.php';

session_action_guest();

$ld = new loader();
$ld->getPage('Virtual Control', 'LOGIN', 'sign-in-alt', 2, 'login.js');