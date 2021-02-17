<?php

include_once __DIR__ . '/init_get.php';
include_once __DIR__ . '/init_page.php';
include_once __DIR__ . '/../general/session.php';

$f_id = post_get_data('f_id');

session_action_scripts();

$d = new initDatabase();
$v = $d->init();
ob_get_clean();

$page = new InitPage($v['CODE'], $v['DATA']);

echo json_encode(['PAGE' => $page->getPage()]);