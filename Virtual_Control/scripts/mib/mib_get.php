<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../icons/icondata.php';
include_once __DIR__ . '/../icons/iconselect.php';
include_once __DIR__ . '/mibdata.php';
include_once __DIR__ . '/mibpage.php';
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$request_id = post_get_data('request_id');
$request_data_id = post_get_data('request_data_id');

$code = 0;
$html = '';
$res_data = '';

if ($request_id) {
    $data = '';
    if($request_id == 20) {
	initialize();
    }
    if($request_id >= 20 && $request_id <= 25) {
	$data = data_get_group();
    } else if($request_id >= 30 && $request_id <= 35) {
	$data = data_get_sub();
    } else if($request_id >= 40 && $request_id <= 41) {
	$data = data_get_node();
    } else if($request_id == 42) {
	$data = data_get_icon();
    }
    if($data) {
	switch($request_id) {
	    //GROUP
	    case 20: $page = new MIBPage($data); $html = $page->getGroupSelect(); break;
	    //SUB
	    //NODE
	}
    } else {
	$code = 2;
    }
} else {
    $code = 2;
}
$res = ['CODE' => $code];
if($html && $res_data) {
    $res = ['CODE' => $code, 'DATA' => $res_data, 'PAGE' => $html];
} else if ($html) {
    $res = ['CODE' => $code, 'PAGE' => $html];
}

//ob_get_clean();
echo json_encode($res);

function initialize() {
    session_unset_byid('gsc_mib_option');
    $mibdata = new MIBData();
    $icondata = IconData::getAllIconData();
    $alldata = array_merge($mibdata->getMIBData(), $icondata);
    session_create('gsc_mib_option', $alldata);
}

function data_get_group() {
    return data_get('GROUP');
}

function data_get_sub() {
    return data_get('SUB');
}

function data_get_node() {
    return data_get('NODE');
}

function data_get_icon() {
    return data_get('ICON');
}

function data_get($id) {
    $data = session_get('gsc_mib_option');
    return (isset($data)) ? $data[$id] : '';
}