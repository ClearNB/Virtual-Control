<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../icons/icondata.php';
include_once __DIR__ . '/../icons/iconselect.php';
include_once __DIR__ . '/mibdata.php';
include_once __DIR__ . '/mibpage.php';
include_once __DIR__ . '/mib_get_class.php';
include_once __DIR__ . '/../session/session_chk.php';

include_once __DIR__ . '/group/mib_get.php';
include_once __DIR__ . '/node/mib_get.php';
include_once __DIR__ . '/sub/mib_get.php';

session_action_scripts();

//FORM
$request_id = post_get_data('request_id');
$request_data_id = post_get_data('request_data_id');

$response_data = ['PAGE' => ''];

if ($request_id) {
    $class = '';

    switch ($request_id) {
	case 20: case 21: case 22: case 23: case 24: case 25: case 26: //GROUP
	    $class = new MIBGroupGet($request_id, $request_data_id);
	    break;
	case 30: case 31: case 32: case 33: case 34: case 35: case 36: //SUB
	    $class = new MIBSubGet($request_id, $request_data_id);
	    break;
	case 40: case 41: case 42: case 43: case 44: //NODE
	    $class = new MIBNodeGet($request_id, $request_data_id);
	    break;
    }
    if ($class) {
	$response = $class->run();
    }
}

$page = new MIBPage();

if ($response['CODE'] == 3 || $response['CODE'] == 5) {
    $response_data['CODE'] = 2;
}
$response_data['PAGE'] = $page->get_mibpage_bycode($response['CODE'], $response['DATA']);

if (ob_get_contents()) {
    $data = ob_get_flush();
    $page->reset();
    
}

echo json_encode($response_data);
