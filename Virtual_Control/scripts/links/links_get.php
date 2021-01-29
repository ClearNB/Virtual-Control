<?php

include_once __DIR__ . '/links_page.php';
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$f_id = post_get_data('f_id');

$res = [];
$page = new LinksPage();

$res['PAGE'] = ($f_id) ? $page->getpage_bycode($f_id) : $page->getFail();
echo json_encode($res);