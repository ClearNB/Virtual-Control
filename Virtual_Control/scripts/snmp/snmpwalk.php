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
include_once __DIR__ . '/../session/session_chk.php';

session_action_scripts();

$loader = new loader();

//返却データ
$res = ['CODE' => 0, 'DATE' => '', 'HOST' => '', 'COMMUNITY' => '', 'SUB' => [''], 'LIST' => '', 'LOG' => '', 'CSV' => ''];
$res['DATE'] = date("Y-m-d H:i:s");
//Variables
$data = filter_input(INPUT_POST, 'sl_ag', FILTER_SANITIZE_STRING);

if ($data) {
    //Serialize
    $agentid = $data;
    $q01 = select(true, "GSC_AGENT", "AGENTHOST, COMMUNITY", "WHERE AGENTID = $agentid");
    $q02 = select(false, "GSC_AGENT_MIB", "SUBID", "WHERE AGENTID = $agentid");

    if ($q01 && $q02) {
	$subids = getArray($q02);
	$host = $q01['AGENTHOST'];
	$com = $q01['COMMUNITY'];
	$res['HOST'] = $host;
	$res['COMMUNITY'] = $com;
	$res['CSV'] = 'Virtual Control Data Convertion v 1.0.0\n取得時間,' . $res['DATE'] . '\nエージェントホスト,' . $host . '\nコミュニティ名,' . $com . '\n+----- 取得データ一覧 -----+\n';
	$res['CSV'] .= 'OID,データ項目名（英名）,データ項目名（日本語名）,データ (インデックス)\n';
	$flag = true;
	$i = 1;
	$res['LIST'] .= $loader->openListGroup();
	foreach ($subids as $subid) {
	    $s_data = $subid['SUBID'];
	    $sub = walk($host, $com, $s_data);
	    $flag &= ($sub['CODE'] == 0);
	    if ($flag) {
		$res['CSV'] .= '【' . $sub['SUB_OID'] . '】' . $sub['SUB_NAME'] . '\n' . $sub['SUB_CSV'];
		$id_f = 'sub_i' . $i;
		$res['SUB'][$id_f] = $sub['SUB'];

		$res['LIST'] .= $loader->addListGroup($id_f, $sub['SUB_OID'], 'poll-h', $sub['SUB_NAME'], '詳しくはクリック！');
		$i += 1;
	    } else {
		break;
	    }
	}
	$res['LIST'] .= $loader->closeListGroup();
	if (!$flag) {
	    $res['CODE'] = 1;
	    $log = ob_get_contents();
	    if (strpos($log, 'No response from') !== false) {
		$log = $host . " へのアドレス到達に失敗しました。";
	    }
	    $res['LOG'] = $log;
	}
    } else {
	$log = ob_get_contents();
	$res = ['CODE' => 1, 'LOG' => $log];
    }
} else {
    $res = ['CODE' => 2];
}
//ob_get_clean();
echo json_encode($res);

function walk($host, $com, $id) {
    $result = '';
    $sub_oid = '';
    $sub_name = '';
    $code = 0;
    $f01 = select(true, "GSC_MIB_SUB", "SUBOBJECTID, SUBNAME", "WHERE SUBID = $id");
    $f02 = select(false, "GSC_MIB_NODE a LEFT OUTER JOIN GSC_ICONS b ON a.ICONID = b.ICONID", "NODEOBJECTID, DESCR, JAPTLANS, ICON", "WHERE SUBID = $id ORDER BY NODEID");

    if ($f01 && $f02) {
	$sub_oid = $f01['SUBOBJECTID'];
	$sub_name = $f01['SUBNAME'];
	$sub_info = $sub_oid . " (" . $sub_name . ")";
	$sub_csv = '';
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
	    $sub_csv = $data['csv'];
	    $s_data = new SNMPTable('data', $data['res'], '結果一覧表');
	    $result .= $s_data->generate_table() . $un_data;
	} else {
	    $code = 1;
	}
    } else {
	$code = 0;
    }
    $res = ['CODE' => $code, 'SUB' => $result, 'SUB_OID' => $sub_oid, 'SUB_NAME' => $sub_name, "SUB_CSV" => $sub_csv];
    return $res;
}
