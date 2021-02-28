<?php

include_once __DIR__ . '/init_get.php';
include_once __DIR__ . '/init_page.php';
include_once __DIR__ . '/../general/session.php';

$f_id = post_get_data('f_id');

session_action_scripts();

$res_data = ['CODE' => 2, 'DATA' => '要求した内容は受け取れませんでした。'];

switch ($f_id) {
    case 51: //Page Get
	$res_data = ['CODE' => 0, 'DATA' => ''];
	break;
    case 52: //Post
	$init = new initGet();
	$res_data = $init->init();
	break;
}
ob_get_clean();

$page = new InitPage($res_data['CODE'], $res_data['DATA']);
echo json_encode(['PAGE' => $page->getPage()]);
