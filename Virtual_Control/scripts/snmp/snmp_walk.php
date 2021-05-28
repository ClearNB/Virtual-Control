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
include_once __DIR__ . '/../general/session.php';
include_once __DIR__ . '/snmp_table.php';
include_once __DIR__ . '/snmp_data.php';
include_once __DIR__ . '/../mib/mib_data.php';

session_action_scripts();

function get_walk_result($agentid) {
    $res = [];

    //データベース情報取得
    $q01 = select(false, 'VC_AGENT_MIB a INNER JOIN VC_AGENT b ON a.AGENTID = b.AGENTID', 'a.SID, b.AGENTHOST, b.COMMUNITY', 'WHERE a.AGENTID = ' . $agentid);

    if ($q01) {
	//サブツリーデータの加工
	$subdata = ['AGENTHOST' => '', 'COMMUNITY' => '', 'SID' => []];
	while ($var = $q01->fetch_assoc()) {
	    $subdata['AGENTHOST'] = $var['AGENTHOST'];
	    $subdata['COMMUNITY'] = $var['COMMUNITY'];
	    if (!in_array($var['SID'], $subdata['SID'])) {
		array_push($subdata['SID'], $var['SID']);
	    }
	}
	$mibdata = new MIBData();
	$alldata = $mibdata->getMIB(2, 1, $subdata['SID']);
	$res = convert_data($subdata['AGENTHOST'], $subdata['COMMUNITY'], $alldata);
	$res['AGENTID'] = $agentid;
    } else {
	$res = ['CODE' => 3, 'DATA' => ob_get_contents()];
    }
    return $res;
}

function convert_data($agenthost, $community, $mib) {
    $loader = new loader();
    $date = date("Y-m-d H:i:s");
    $res = [
	'CODE' => 1,
	'DATE' => $date,
	'HOST' => $agenthost,
	'COM' => $community,
	'CSV' => 'Virtual Control Data Convertion v 1.0.0\n取得時間,' . $date . '\nエージェントホスト,' . $agenthost . '\nコミュニティ名,' . $community . '\n+----- 取得データ一覧 -----+\nOID,データ項目名（英名）,データ項目名（日本語名）,データ (インデックス)\n',
	'LIST' => $loader->openListGroup(),
	'SUBDATA' => [],
	'SIZE' => 0
    ];
    $i = 1;

    foreach ($mib['GROUP'] as $g) {
	$res['LIST'] .= $loader->SubTitle('【' . $g['GROUP_OID'] . '】' . $g['GROUP_NAME'], 'グループ配下の' . $g['GROUP_SUB_COUNT'] . '個のデータを確認できます。', 'object-group');
	foreach ($mib['SUB'][$g['GROUP_ID']] as $s) {
	    $sub = walk($agenthost, $community, $s, $mib['NODE'][$s['SUB_ID']]);
	    if ($sub['CODE'] == 0) {
		$id_f = 'sub_i' . $i;
		$res_data = $sub['DATA'];
		$res['CSV'] .= '【' . $res_data['MIB'] . '】\n' . $res_data['CSV'];
		$res['SUBDATA'][$id_f] = ['SIZE' => $res_data['SIZE'], 'MIB' => $res_data['MIB'], 'TABLE' => $res_data['TABLE'], 'ERROR' => $res_data['ERROR'], 'DATE' => $res_data['DATE']];
		$res['LIST'] .= $loader->addListGroup($id_f, $res_data['MIB'], 'poll-h', $res_data['SIZE'] . '個データ取得 | ' . $res_data['ERR_SIZE'] . '個エラー', '詳しくはクリック！');
		$res['SIZE'] += $res_data['SIZE'];
		$i += 1;
	    } else {
		$res = ['CODE' => 3, 'DATA' => $sub['DATA']];
		break;
	    }
	}
	if ($res['CODE'] == 0) {
	    $res['LIST'] .= $loader->closeListGroup();
	} else {
	    break;
	}
    }

    return $res;
}

function walk($host, $com, $subdata, $submib) {
    $res = '';
    $code = 0;
    $snmpdata = snmp2_real_walk($host, $com, $subdata['SUB_OID']);
    if ($snmpdata) {
	SNMPData::resetStatic();
	SNMPData::setMIBData($submib);
	foreach ($snmpdata as $key => $v) {
	    $k = str_replace('iso', '1', $key);
	    new SNMPData(1, $k, $v);
	}
	$data = SNMPData::getDataArray();
	$s_data = new SNMPTable('data', $data['DATA']);
	$result = $s_data->generateTable();

	$err_size = sizeof($data['ERROR']);
	if ($err_size == 1 && in_array('〈該当データなし〉', $data['ERROR'])) {
	    $err_size = 0;
	}

	$res = [
	    'SIZE' => sizeof($snmpdata),
	    'DATE' => date("Y-m-d H:i:s"),
	    'TABLE' => $result,
	    'MIB' => $subdata['SUB_OID'] . " (" . $subdata['SUB_NAME'] . ")",
	    'CSV' => $data['CSV'],
	    'ERROR' => $data['ERROR'],
	    'ERR_SIZE' => $err_size
	];
    } else {
	$code = 1;
	if (strpos(ob_get_contents(), 'No response from') !== false) {
	    $res = '【' . $com . '】' . $host . ' へのアドレス到達・コミュニティ認証に失敗しました。<br>エージェントの設定をご確認ください。';
	} else {
	    $res = ob_get_contents();
	}
	ob_get_clean();
    }
    return ['CODE' => $code, 'DATA' => $res];
}
