<?php

include_once __DIR__ . '/links_page.php';
include_once __DIR__ . '/../general/session.php';

session_action_scripts();

$f_id = post_get_data('f_id');
$page = new LinksPage($f_id);

echo json_encode(['PAGE' => $page->getPage()]);