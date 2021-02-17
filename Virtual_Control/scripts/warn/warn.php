<?php

include_once __DIR__ . '/warn_page.php';
include_once __DIR__ . '/warn_get.php';
include_once __DIR__ . '/../general/session.php';

session_action_scripts();

$f_id = post_get_data('f_id');

$get = new WarnGet($f_id);
$res = $get->run();

$page = new WarnPage($res['CODE'], $res['DATA']);
$response_page = $page->getPage();

$res_array = ['PAGE' => $response_page];
if ($res['CODE'] == 0) {
    $res_array['CSV'] = $res['DATA']['CSV'];
}

echo json_encode($res_array);