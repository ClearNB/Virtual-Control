<?php

include_once __DIR__ . '/../general/session.php';
include_once __DIR__ . '/../icons/icon_data.php';
include_once __DIR__ . '/../icons/icon_select.php';
include_once __DIR__ . '/mib_data.php';
include_once __DIR__ . '/mib_page.php';
include_once __DIR__ . '/mib_get.php';

session_action_scripts();

$request_id = post_get_data('f_id');
$request_data_id = post_get_data('f_did');
$get = new MIBGet($request_id, $request_data_id);
$page = new MIBPage($response['CODE'], $response['DATA']);

$response_data = ['DATA' => ''];

if ($response['CODE'] == 3 || $response['CODE'] == 5) {
    $response_data['CODE'] = 2;
}
$response_data['PAGE'] = $page->getPage();

echo json_encode($response_data);