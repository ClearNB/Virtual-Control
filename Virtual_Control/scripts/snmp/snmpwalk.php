<?php

/* SNMPWalk Launcher for Virtual Control.
 * SNMPWalk is supported for SNMP version 2.0.
 * To launch, need module: PDO_SNMP.
 */

include_once ('../sqldata.php');
include_once ('../common.php');
include_once ('../dbconfig.php');
include_once ('./table_snmp.php');

function data_search($data, $oids) {
    $r = false;
    foreach ($oids as $o) {
	if (strpos($data, $o) !== false) {
	    $r = $o;
	    break;
	}
    }
    return $r;
}

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

    //未入力の場合、負荷を抑えるために 1.3.6.1.2.1 のみ取得
    if (!$oid) {
	$oid = "1.3.6.1.2.1";
    }

    $f_f = select(false, "GSC_MIB_NODE", "*", "WHERE OBJECTID LIKE '$oid.%'");

    $data = ["OID" => [], "DESCR" => [], "JAPTLANS" => [], "ICON" => [], "VALUE" => [], "CHECK" => []];

    //OID別に格納（DESCR, JAPLTLANS, ICON, VALUE はOIDの連想配列として扱う）
    if ($f_f) {
	$c = 0;
	$edata = '';
	while ($row = $f_f->fetch_assoc()) {
	    if (mb_substr_count($row['OBJECTID'], '*') == 1) {
		//値の加工・チェックし、CHECKにグループCHECK値を加える
		//1. * を取り除く
		$o_id = str_replace('*', '', $row['OBJECTID']);

		//2. 先頭の3つの数字部分を取り除く
		$o_id_s = preg_replace('/([0-9][.][0-9][.])$/', '', $o_id);

		//3. グループ比較する（一致しない場合はチェック値をインクリメントし、別グループとして扱う）
		if ($o_id_s != $edata) {
		    $c += 1;
		    $edata = $o_id_s;
		}

		//4. OIDを入れ、CHECKにグループ値を加える
		array_push($data['OID'], $o_id);
		$data['CHECK'][$o_id] = $c;
	    } else {
		//それ以外はOBJECTIDをそのまま入れて、CHECKに0を加える
		$o_id = $row['OBJECTID'];
		array_push($data['OID'], $o_id);
		$data['CHECK'][$o_id] = 0;
	    }
	    //OIDは順序配列、その他の要素はOIDによる連想配列
	    $data['DESCR'][$o_id] = $row['DESCR'];
	    $data['JAPTLANS'][$o_id] = $row['JAPTLANS'];
	    $data['ICON'][$o_id] = $row['ICON'];
	}
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
	//指定OIDがデータベースにあるか確認
	if ($f_f) {
	    //ある場合は、照合しながら進めていく
	    $un_data = '【MIBデータベースに登録されていない情報】<br>';
	    foreach ($snmpdata as $key => $v) {

		//keyの iso.~ を 1.~ に変換
		$k = str_replace('iso', '1', $key);

		//データを検索
		$r = data_search($k, $data['OID']);

		if ($r) {
		    //データ値の加工（型なし, 値のみ）
		    $v = str_replace('"', '', str_replace('iso', '1', preg_replace('/(OID|Timeticks|INTEGER|STRING)[\:][\s]/', '', $v)));
		    if (empty($data['VALUE'][$r])) {
			$data['VALUE'][$r] = [];
		    }
		    array_push($data['VALUE'][$r], $v);
		} else {
		    $un_data .= $k . " : " . $v . "<br>";
		}
	    }
	    $result .= table_result('data', '結果一覧表', 'table', $data) . $un_data;
	} else {
	    foreach ($snmpdata as $key => $v) {
		$result .= str_replace('iso', '1', $key) . " : " . $v . "<br>";
	    }
	}
    }
    //ob_get_clean();
    echo json_encode(['code' => $code, 'res' => $result]);
}