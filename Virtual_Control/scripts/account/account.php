<?php
include_once __DIR__ . '/../general/session.php';
include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/account_get.php';
include_once __DIR__ . '/account_page.php';

session_action_scripts();

$funid = post_get_data('f_id');
$get = new AccountGet($funid);
$response = $get->run();
$page = new AccountPage($response['CODE'], $response['DATA']);
$res_data = ['PAGE' => $page->getPage()];
if($response['CODE'] == 12 || $response['CODE'] == 15) {
    $res_data['CODE'] = 2;
}
echo json_encode($res_data);