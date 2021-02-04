<?php

include_once __DIR__ . '/warn_page.php';
include_once __DIR__ . '/warndata.php';
include_once __DIR__ . '/warntable.php';
include_once __DIR__ . '/../general/loader.php';
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$f_id = post_get_data('f_id');
$s_id = post_get_data('sub');

$res = ['CODE' => 3, 'DATA' => '手続きの要求は受け取れませんでした。'];
$page = new WarnPage();

switch ($f_id) {
    case 81:
	initialize();
    case 83:
	$data = get_session();
	$res['CODE'] = ($data) ? 0 : $res['CODE'];
	$res['DATA'] = ($data) ? $data : $res['DATA'];
	break;
    case 82:
	$data = get_session_byselect($s_id);
	$res['CODE'] = ($data) ? 1 : $res['CODE'];
	$res['DATA'] = ($data) ? $data : $res['DATA'];
	break;
}
$response_page = $page->get_page_byid($res['CODE'], $res['DATA']);

$res_array = ['PAGE' => $response_page];
if ($f_id == 81 || $f_id == 83) {
    $res_array['CSV'] = $res['DATA']['CSV'];
}

ob_clean();

echo json_encode($res_array);

function initialize() {
    session_unset_byid('warn_data');
    WarnData::setData();
    WarnData::load_data();
    $data = WarnData::getArray();
    $table = new WarnTable($data);
    $res_data = $table->getHTML();
    $res = ['SUB' => $res_data['SUB'], 'DATE' => $data['DATE'], 'LIST' => $res_data['LIST'], 'CSV' => $data['CSV'], 'COUNT' => $res_data['COUNT']];
    session_create('warn_data', $res);
}

function get_session() {
    return session_get('warn_data');
}

function get_session_byselect($id) {
    $data = get_session();
    return (isset($data['SUB'][$id])) ? $data['SUB'][$id] : '';
}
