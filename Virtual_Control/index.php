<?php //Virtual Control : INDEX (VC-01) by Project GSC
include_once __DIR__ . '/scripts/general/loader.php';
include_once __DIR__ . '/scripts/general/session.php';

session_action_guest();

$loader = new loader();
echo $loader->getPage('Virtual Control', 'INDEX', '', 2, 'index.js', false);