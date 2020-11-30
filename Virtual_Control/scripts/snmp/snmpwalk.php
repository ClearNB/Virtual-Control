<?php

/* SNMPWalk Launcher for Virtual Control.
 * SNMPWalk is supported for SNMP version 2.0.
 * To launch, need module: PDO_SNMP.
 */

include_once ('../sqldata.php');
include_once ('../common.php');
include_once ('../dbconfig.php');

function data_search($data, $oids) {
    $r = false;
    foreach($oids as $o) {
	if(strpos($data, $o) !== false) {
	    $r = $o;
	    break;
	}
    }
    return $r;
}

function table_vertical($table_id, $table_title, $table_title_icon, $table_datas) {
    $result = '<div id="' . $table_id . '">'
	    . '<h3 class="text-left text-body"><i class="fas fa-fw fa-' . $table_title_icon . '"></i>'
	    . $table_title . '</h3><table class="table table-hover"><tbody>';
    for($i = 0; $i < sizeof($table_datas['OID']); $i++) {
	$oid = $table_datas['OID'][$i];
	$result .= '<tr>';
        $result .= '<td>' . '<h5>' . $oid . '</h5><i class="' . $table_datas['ICON'][$oid] . '"></i>' . $table_datas['JAPTLANS'][$oid] . '<br><small>' . $table_datas['DESCR'][$oid] . '<br>' . '</td>';
	$result .= '<td>' . $table_datas['VALUE'][$oid] . '</td>';
	$result .= '</tr>';
    }
    $result .= '</tbody></table></div>';
    return $result;
}

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../403.php");
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    //Variables
    $host = filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING);
    $com = filter_input(INPUT_POST, 'community', FILTER_SANITIZE_STRING);
    $oid = filter_input(INPUT_POST, 'oid', FILTER_SANITIZE_STRING);

    $f_f = select(false, "GSC_MIB_SUB", "*", "WHERE OBJECTID LIKE '$oid.%'");

    $data = ["OID" => [], "DESCR" => [], "JAPTLANS" => [], "ICON" => [], "VALUE" => []];

    //OID別に格納（DESCR, JAPLTLANS, ICON, VALUE はOIDの連想配列として扱う）
    if ($f_f) {
	while ($row = $f_f->fetch_assoc()) {
	    $o_id = str_replace('*', '', $row['OBJECTID']);
	    array_push($data['OID'], $o_id);
	    $data['DESCR'][$o_id] = $row['DESCR'];
	    $data['JAPTLANS'][$o_id] = $row['JAPTLANS'];
	    $data['ICON'][$o_id] = $row['ICON'];
	    $data['VALUE'][$o_id] = '';
	}
    }

    //Functions
    $code = 0;
    $result = "<h3 class=\"text-left text-body\"><i class=\"fas fa-fw fa-info-circle\"></i>入力情報</h3>"
	    . "<ul class=\"black-view\">"
	    . "<li>Host: $host</li>"
	    . "<li>Community: $com</li>"
	    . "<li>OID: $oid</li>"
	    . "</ul>"
	    . "<hr>";
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
		if($r) {
		    $v = str_replace('"', '', str_replace('iso', '1', str_replace('OID: ', '', str_replace('Timeticks: ', '', str_replace('INTEGER: ', '', str_replace('STRING: ', '', $v))))));
		    
		    if($data['VALUE'][$r] != '') {
			$data['VALUE'][$r] .= ', ';
			$pre =  '<br>【' . (mb_substr_count($data['VALUE'][$r], ',') + 1) . '】';
		    } else {
			$pre =  '【1】';
		    }
		    $data['VALUE'][$r] .= $pre . $v;
		} else {
		    $un_data .= $k . " : " . $v . "<br>";
		}
	    }
	    $result .= table_vertical('data', '結果一覧表', 'table', $data) . $un_data;
	} else {
	    foreach ($snmpdata as $key => $v) {
		$result .= str_replace('iso', '1', $key) . " : " . $v . "<br>";
	    }
	}
    }
    //ob_get_clean();
    echo json_encode(['code' => $code, 'res' => $result]);
}