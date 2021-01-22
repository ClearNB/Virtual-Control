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

//GROUP
$group_oid = post_get_data('in_gp_id');
$group_name = post_get_data('in_gp_nm');

//SUB
$sub_oid = post_get_data('in_sb_id');
$sub_name = post_get_data('in_sb_nm');

//NODE
$node_oid = post_get_data('in_nd_id');
$node_oid_sub = post_get_data('in_nd_id_sb');
$node_oid_table_id = post_get_data('in_nd_id_tb');
$node_descr = post_get_data('in_nd_ds');
$node_japtlans = post_getdata('in_nd_jp');
$node_type = post_get_data('in_nd_tp');
$node_option = post_get_data('in_nd_op');
$node_iconid = post_get_data('in_nd_ic');

$code = (!$request_id ||($request_id && $request_data_id == 999)) ? 1 : 0;
$html = '';
$res_data = '';

if ($code == 0) {
    $data = '';
    if ($request_id == 20) {
        initialize();
    }
    if (session_exists('gsc_mib_option')) {
	switch($request_id) {
	    case 20: //GROUP SELECT
		$data = data_get_from_group(0); break;
	    case 21:
		data_reset_from_element(1); $data = data_get_from_group(0); break;
	    case 22: case 23: case 24: case 25: case 26:
		$data = data_get_from_group(0, $request_data_id); break;
	    case 30:
		data_get_from_group(0, $request_data_id, true); $data = data_get_from_group(1, $request_data_id, true); break;
	    case 31:
		data_reset_from_element(2); $data = data_get_from_group(1); break;
	    case 32: case 33: case 34: case 35: case 36:
		$data = data_get_from_element_byid(1, $request_data_id); break;
	    case 40:
		data_get_from_group(1, $request_data_id, true); $data = data_get_from_group(2, $request_data_id, true); break;
	    case 41:
		$data = data_get_from_group(2, $request_data_id, true); break;
	    case 42: case 43:
		$data = data_get_from_element(); break;
	    case 44:
		$data = data_get_from_group(3, $request_data_id); break;
	}
    }
    if ($data) {
	$page = new MIBPage();
	switch ($request_id) {
	    //GROUP
	    case 20: case 21: $html = $page->getGroupSelect($data); break;
	    case 22: $html = $page->getGroupCreate(); break;
	    case 23: $html = $page->getGroupEditSelect($data); break;
	    case 24: $html = $page->getGroupEditOID($data); break;
	    case 25: $html = $page->getGroupEditName($data); break;
	    case 26: $html = $page->getGroupDelete($data); break;

	    //SUB
	    case 30: case 31: $html = $page->getSubSelect($data); break;
	    case 32: $html = $page->getSubCreate($data); break;
	    case 33: $html = $page->getSubEditSelect($data); break;
	    case 34: $html = $page->getSubEditOID($data); break;
	    case 35: $html = $page->getSubEditName($data); break;
	    case 36: $html = $page->getSubDelete($data); break;

	    //NODE
	    case 40: case 41: $html = $page->getNodeEditTop($data); break;
	    case 42: $html = $page->getNodeEditForm($data); break;
	    case 43: $html = $page->getNodeEditIconSelect($data); break;
	    
	    default: $html = $page->getError(); break;
	}
    } else {
	$code = 1;
    }
} else {
    $code = 1;
}
if(!$html && $code == 1) {
    $page = new MIBPage();
    $html = $page->getError();
}

$res = ['CODE' => $code, 'PAGE' => $html];

//ob_get_clean();
echo json_encode($res);

/**
 * [Function] MIB_OPTION データ初期化
 * 
 * セッションID 'gsc_mib_option' 内のデータをクリアし、MIB情報及びアイコン情報をもとにセッション情報を構築します
 */
function initialize() {
    session_unset_byid('gsc_mib_option');
    $mibdata = new MIBData();
    $icondata = IconData::getAllIconData();
    $alldata = array_merge($mibdata->getMIB(1), $icondata);
    session_create('gsc_mib_option', $alldata);
}

/**
 * [Function] MIB_OPTION データ取得
 * 
 * GROUP, SUB, NODE, ICON の4つのグループから欲しいデータを取得します<br>
 * 【データ取得方法】<br>
 * [GROUP] => [GROUPID]<br>
 * [SUB] => [PARENT_GROUPID]<br>
 * [NODE] => [PARENT_SUBID]<br>
 * [ICON] => [PAGEID]
 * 
 * @param int $type (0..GROUP, 1..SUB, 2..NODE, 3..ICON)
 * @param int|string $id そのタイプ内の分類されたIDを指定します（Default: ''）
 * @param bool $is_pap_el 選択項目情報としてセッションに登録した上でその情報を取得するかどうかを指定します（Default: false）
 * @return array|null $_SESSION['gsc_mib_option'][$type][$id]
 */
function data_get_from_group($type, $id = '', $is_pap_el = false) {
    $type_text = '';
    switch($type) {
	case 0: $type_text = 'GROUP'; break;
	case 1: $type_text = 'SUB'; break;
	case 2: $type_text = 'NODE'; break;
	case 3: $type_text = 'ICON'; break;
    }
    $data = ($type_text) ? data_get($type_text) : '';
    $res = ($id && $data) ? $data[$id] : $data;
    if($is_pap_el) {
	$res = data_set_from_element($type, $res);
    }
    return $res;
}

/**
 * [Function] MIB_OPTION 選択項目情報書き込み・取得
 * 
 * 今まで選択した情報をセッションに書き込み、またその情報を取得します<br>
 * $data[$id] → $_SESSION['gsc_mib_option']['SELECT'][$type]<br>
 * 【書き込みのタイミング】<br>
 * ・グループ情報を選択して「サブツリー選択」へ遷移した場合<br>
 * ・サブツリー情報を選択して「ノード編集」へ遷移した場合
 * 
 * @param int $type (0..GROUP, 1..SUB, 2..NODE)
 * @param string $data GROUP, SUB, NODE のいずれかのデータが指定されます
 * @return array|null $_SESSION['gsc_mib_option']['SELECT'][$type][$id]
 */
function data_set_from_element($type, $data) {
    $type_text = '';
    switch($type) {
	case 0: $type_text = 'GROUP'; break;
	case 1: $type_text = 'SUB'; break;
	case 2: $type_text = 'NODE'; break;
    }
    if($type_text && $data) {
	$data_set = session_get('gsc_mib_option');
	$data_set['SELECT'][$type_text] = $data;
	session_unset_byid('gsc_mib_option');
	session_create('gsc_mib_option', $data_set);
    }
    return data_get_from_element();
}

/**
 * [Function] 
 * [Function] MIB_OPTION 選択項目情報取得
 * 
 * GROUP, SUB, NODE, ICON の4つのグループデータを取得します<br>
 * 
 * @param int $type (0..GROUP, 1..SUB, 2..NODE)
 * @param string $id そのグループ中にあるIDを指定します
 * @param bool $is_pap_el 選択項目情報としてセッションに登録した上でその情報を取得するかどうかを指定します（Default: false）
 * 
 * @return array|null $_SESSION['gsc_mib_option']['SELECT'][$type (text)][$id]
 */
function data_get_from_element_byid($type, $id, $is_pap_el = false) {
    $data = data_get_from_element();
    $type_text = '';
    switch($type) {
	case 0: $type_text = 'GROUP'; break;
	case 1: $type_text = 'SUB'; break;
	case 2: $type_text = 'NODE'; break;
    }
    $res = ($data && $id && $type_text) ? $data[$type_text][$id] : '';
    if($is_pap_el) {
	$res = data_set_from_element($type, $res);
    }
    return $res;
}

/**
 * [Function] MIB_OPTION 選択項目情報取得
 * 
 * GROUP, SUB, NODE, ICON の4つのグループデータを取得します<br>
 * 
 * @return array|null $_SESSION['gsc_mib_option']['SELECT']
 */
function data_get_from_element() {
    return data_get('SELECT');
}

/**
 * [Function] MIB_OPTION 選択項目情報リセット
 * 
 * 指定したタイプの項目情報をセッションから削除します<br>
 * reset -> $_SESSION['gsc_mib_option']['SELECT'][$type]
 * 
 * @param int $type (0..GROUP, 1..SUB, 2..NODE)
 */
function data_reset_from_element($type) {
    $type_text = '';
    switch($type) {
	case 0: $type_text = 'GROUP'; break;
	case 1: $type_text = 'SUB'; break;
	case 2: $type_text = 'NODE'; break;
    }
    if($type_text) {
	$data_set = session_get('gsc_mib_option');
	unset($data_set['SELECT'][$type_text]);
	session_unset_byid('gsc_mib_option');
	session_create('gsc_mib_option', $data_set);
    }
}

/**
 * [Function] MIB_OPTION セッションデータ取得
 * 
 * セッションデータのMIBオプションから取得したいIDを指定してMIB・ICONデータを取得します。
 * 
 * @param string $id gsc_mib_optionに対するID
 * @return array|null $_SESSION['gsc_mib_option'][$id]
 */
function data_get($id) {
    $data = session_get('gsc_mib_option');
    return ($data && isset($data[$id])) ? $data[$id] : '';
}