<?php

/**
 * [Ajax] SNMPWALK・テーブルデータ表示
 * 
 * SNMPWALK専用のファンクションです。SNMP取得およびテーブル表示化を行います。
 * 
 * @author ClearNB
 * @package VirtualControl_scripts_snmp
 */
include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../general/loader.php';
include_once __DIR__ . '/snmptable.php';
include_once __DIR__ . '/snmpdata.php';
include_once __DIR__ . '/ipdata.php';

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../../403.php");
    exit;
}

$loader = new loader();

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    //返却データ
    $res = ['CODE' => 0, 'DATE' => '', 'HOST' => '', 'COMMUNITY' => '', 'SUB' => [''], 'LIST' => '', 'LOG' => ''];
    //Variables
    $data = filter_input(INPUT_POST, 'sl_ag', FILTER_SANITIZE_STRING);

    //Serialize
    $sl = explode('+', $data);
    $agentid = $sl[0];
    $subids = explode('_', $sl[1]);

    if ($agentid && $subids) {
	//エージェント情報取得
	$q01 = select(true, "GSC_AGENT", "AGENTHOST, COMMUNITY", "WHERE AGENTID = $agentid");
	if ($q01) {
	    $host = $q01['AGENTHOST'];
	    $com = $q01['COMMUNITY'];
	    $res['HOST'] = $host;
	    $res['COMMUNITY'] = $com;
	    $flag = true;
	    $i = 1;
	    $res['LIST'] .= $loader->openListGroup();
	    foreach ($subids as $subid) {
		$sub = walk($host, $com, $subid);
		$flag &= ($sub['CODE'] == 0);
		if($flag) {
		    $id_f = 'sub_i' . $i;
		    $res['SUB'][$id_f] = $sub['SUB'];
		    $res['LIST'] .= $loader->addListGroup($id_f, $sub['SUB_OID'], 'poll-h', $sub['SUB_NAME'], '詳しくはクリック！');
		    $i += 1;
		} else {
		    break;
		}
	    }
	    $res['LIST'] .= $loader->closeListGroup();
	    if(!$flag) {
		$res['CODE'] = 1;
		$log = ob_get_contents();
		if(strpos($log, 'No response from') !== false) {
		    $log = $host . " へのアドレス到達に失敗しました。";
		}
		$res['LOG'] = $log;
	    }
	    $res['DATE'] = date("Y-m-d H:i:s");
	} else {
	    $log = ob_get_contents();
	    $res = ['CODE' => 1, 'LOG' => $log];
	}
    } else {
	$log = ob_get_contents();
	$res = ['CODE' => 1, 'LOG' => $log];
    }
    ob_get_clean();
    echo json_encode($res);
}

function walk($host, $com, $id) {
    $result = '';
    $sub_oid = '';
    $sub_name = '';
    $code = 0;
    $f01 = select(true, "GSC_MIB_SUB", "SUBOBJECTID, SUBNAME", "WHERE SUBID = $id");
    $f02 = select(false, "GSC_MIB_NODE", "NODEOBJECTID, DESCR, JAPTLANS, ICON", "WHERE SUBID = $id ORDER BY NODEID");
    
    if ($f01 && $f02) {
	$sub_oid = $f01['SUBOBJECTID'];
	$sub_name = $f01['SUBNAME'];
	$sub_info = $sub_oid . " (" . $sub_name . ")";
	SNMPData::resetStatic();
	while ($var = $f02->fetch_assoc()) {
	    new SNMPData($var['NODEOBJECTID'], $var['DESCR'], $var['JAPTLANS'], $var['ICON']);
	}
	$snmpdata = snmp2_real_walk($host, $com, $sub_oid);
	if ($snmpdata) {
	    $result = "<h3 class=\"text-left text-body\"><i class=\"fas fa-fw fa-info-circle\"></i>取得情報</h3>"
		    . "<ul class=\"black-view\">"
		    . "<li>エージェントホスト: $host</li>"
		    . "<li>コミュニティ: $com</li>"
		    . "<li>対象OID: $sub_info</li>"
		    . "<li>取得日時: " . date("Y-m-d H:i:s") . "</li>"
		    . "</ul><hr>";
	    $un_data = '【MIBデータベースに登録されていない情報】<br>';
	    foreach ($snmpdata as $key => $v) {
		$k = str_replace('iso', '1', $key);

		if (!SNMPData::setValue($k, $v)) {
		    $un_data .= $k . " : " . $v . "<br>";
		}
	    }
	    $data = SNMPData::getDataArray();
	    $s_data = new SNMPTable('data', $data, '結果一覧表');
	    $result .= $s_data->generate_table() . $un_data;
	} else {
	    $code = 1;
	}
    } else {
	$code = 0;
    }
    $res = ['CODE' => $code, 'SUB' => $result, 'SUB_OID' => $sub_oid, 'SUB_NAME' => $sub_name];
    return $res;
}
