<?php

/* SNMPWalk Launcher for Virtual Control.
 * SNMPWalk is supported for SNMP version 2.0.
 * To launch, need module: PDO_SNMP.
 */

include_once ('../general/sqldata.php');
include_once ('./snmptable.php');
include_once ('./snmpdata.php');

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../../403.php");
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    //Variables
    $host = filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING);
    $com = filter_input(INPUT_POST, 'community', FILTER_SANITIZE_STRING);
    $oid = filter_input(INPUT_POST, 'oid', FILTER_SANITIZE_STRING);

    //未入力の場合、負荷を抑えるために 1.3.6.1.2.1.1 のみ取得
    if (!$oid) {
	$oid = "1.3.6.1.2.1.1";
    }

    //→ OBJECTID, DESCR, JAPTLANS, ICON
    $f_f = select(false, "GSC_MIB_NODE", "OBJECTID, DESCR, JAPTLANS, ICON", "WHERE SUBOBJECTID = '$oid' ORDER BY NODEID");

    //OID別に格納（DESCR, JAPLTLANS, ICON, VALUE はOIDの連想配列として扱う）
    if ($f_f) {
	while ($var = $f_f->fetch_assoc()) {
	    $d = new SNMPData($var['OBJECTID'], $var['DESCR'], $var['JAPTLANS'], $var['ICON']);
	}

	$code = 0;
	$result = "<h3 class=\"text-left text-body\"><i class=\"fas fa-fw fa-info-circle\"></i>入力情報</h3>"
		. "<ul class=\"black-view\">"
		. "<li>Host: $host</li>"
		. "<li>Community: $com</li>"
		. "<li>OID: $oid</li>"
		. "</ul>"
		. "<hr>";
	//SNMPデータの取得
	$snmpdata = snmp2_real_walk($host, $com, $oid);
	if (!$snmpdata) {
	    $code = 1;
	} else {
	    //ある場合は、照合しながら進めていく
	    $un_data = '【MIBデータベースに登録されていない情報】<br>';
	    foreach ($snmpdata as $key => $v) {
		//keyの iso.~ を 1.~ に変換
		$k = str_replace('iso', '1', $key);

		if (!SNMPData::setValue($k, $v)) {
		    $un_data .= $k . " : " . $v . "<br>";
		}
	    }
	    $data = SNMPData::getDataArray();
	    //var_dump($data);
	    $s_data = new SNMPTable('data', $data, '結果一覧表');
	    $result .= $s_data->generate_table() . $un_data;
	}
	ob_get_clean();
	echo json_encode(['code' => $code, 'res' => $result]);
    } else {
	echo json_encode(['code' => 1]);
    }
}