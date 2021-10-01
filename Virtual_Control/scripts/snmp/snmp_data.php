<?php

include_once __DIR__ . '/index_data.php';
include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../general/loader.php';
include_once __DIR__ . '/snmp_table.php';
include_once __DIR__ . '/../mib/mib_data.php';
include_once __DIR__ . '/../agent/agent_data.php';

//getSNMPData(1);

/**
 * [GET] SNMPデータ取得
 * | - [VALUE]
 * | -> [GROUPID]
 * | - -> [INFO]
 * | - -> [COUNT]
 * | - -> [MIBID] -> [DATAOID]:[ENNAME]:[JPNAME]:[ICON]:[ISTABLE]:[TABLEID]:[VALUE]
 * | - [DATE]
 * | - [COUNT]
 * | - [CSV]
 */
function getSNMPData($agentid, $gid = 0) {
    $data = null;
    $agent = getAgent($agentid);
    $agent_mib = getMIBWalkData($agentid);
    $getid = $gid;
    $date = date('Y/m/d H:i:s');
    $loader = new loader();
    if ($gid == 0) {
	$getid = insertGetSelectData(date('Y/m/d H:i:s'), $agentid);
    } else {
	$date = selectGetDateTime($getid);
    }
    $past = selectGetDateData($agentid);
    if ($agent && $getid && $past) {
	$data = ['AGENTID' => $agentid, 'SUB' => [], 'CODE' => 1, 'DATA' => [], 'DATE' => $date, 'COUNT' => 0, 'CSV' => '', 'AGENT' => '【' . $agent['HOSTADDRESS'] . '】' . $agent['COMMUNITY'], 'PAST_DATA' => $past, 'MEM_PAST' => $gid, 'LIST' => $loader->openListGroup()];
	foreach ($agent_mib as $i => $group_data) {
	    $snmpwalk = '';
	    if ($gid != 0) {
		$snmpwalk = getPastGet($getid, $i); //PastData
	    } else {
		$snmpwalk = snmp2_real_walk($agent['HOSTADDRESS'], $agent['COMMUNITY'], $i); //SNMPWALK
	    }
	    if ($snmpwalk) {
		$data['COUNT'] += sizeof($snmpwalk);
		$data['DATA'][$i] = getSNMPConvertData($snmpwalk, $group_data, $getid, ($gid == 0));
		$table = new SNMPTable($i, $data['DATA'][$i]);
		$data['LIST'] .= $loader->addListGroup('sub_i' . $i, '【' . $i . '】' . $group_data['NAME'], 'object-ungroup', '取得数: ' . sizeof($snmpwalk) . ' (エラー数: ' . sizeof($data['DATA'][$i]['ERROR']) . ')', '詳しくはここをクリック！');
		$data['SUB']['sub_i' . $i] = ['AGENT' => '【' . $agent['HOSTADDRESS'] . '】' . $agent['COMMUNITY'], 'TABLE' =>  $table->generateTable(), 'MIB' => '【' . $i . '】' . $group_data['NAME'], 'COUNT' => sizeof($snmpwalk), 'DATE' => $date, 'ERROR' => $data['DATA'][$i]['ERROR']];
	    } else {
		$data = ['CODE' => 3, 'DATA' => 'SNMPWALK::' . $agent['HOSTADDRESS'] . '(' . $agent['COMMUNITY'] . ') -> ' . $i];
		break;
	    }
	}
	if ($data['CODE'] == 3) {
	    delete('VC_GET_SELECT', 'WHERE GETID = ' . $getid);
	} else {
	    $data['LIST'] .= $loader->closeListGroup();
	    $data['CSV'] = getAnalyCSVData($data['DATA'], $agent['HOSTADDRESS'], $agent['COMMUNITY'], $data['DATE'], $data['COUNT']);
	}
    } else {
	$data = ['CODE' => 3, 'DATA' => 'データベースへのアクセスに失敗しました'];
    }
    return $data;
}

/**
 * [GET] SNMP変換
 * SNMPWALKで取得したデータを、MIB情報とともに変換して加工します
 * @param type $group_walk_data 実際に取得したデータを指定します 
 * @param type $group_mib_data それに対するグループ用MIBを指定します
 * @param int $getid 過去履歴取得用のGETIDを指定します
 */
function getSNMPConvertData($group_walk_data, $group_mib_data, $getid,
	$is_insert = true) {
    $vdata = ['VALUE' => [], 'MIB' => $group_mib_data, 'ERROR' => []];

    //WALKデータを手順に格納していく
    foreach ($group_walk_data as $key => $data) {
	//OIDキーを変える(iso → 1, 先頭が0を削除)
	$k = preg_replace('/[.]0$/', '', str_replace('iso', '1', $key));

	//OIDを検索し、MIBIDが存在することを確認する
	$set = searchOID($k, $vdata['MIB']['VALUE']);

	//存在する場合
	if ($set) {
	    //存在した箇所のMIBデータを取り出す
	    $setdata = $vdata['MIB']['VALUE'][$set];

	    //インデックスを取り出す
	    $index = preg_replace('/^./', '', str_replace($setdata['DATAOID'], '', str_replace('iso', '1', $key)));

	    //テーブルタイプを取り出す
	    $iftype = $setdata['TABLETYPE'];

	    //インデックスがあり、かつテーブルタイプが2のとき
	    if ($index && $iftype == 2) {
		//元テーブルのMIBIDを取り出す
		$ifid = $setdata['TABLEIFID'];

		//テーブルの値に入れるのはインデックスなので、falseと判定してしまう0の箇所に入らないようにDUMMYを予め入れた配列を作っておく
		if (!isset($vdata['VALUE'][$ifid])) {
		    $vdata['VALUE'][$ifid] = ['DUMMY'];
		}

		//インデックスがすでに存在するか確認する（あったら最初に見つかった箇所のキー、なかったらfalse）
		$inid = array_search($index, $vdata['VALUE'][$ifid]);
		if (!$inid) {
		    //ない場合は、追加する
		    array_push($vdata['VALUE'][$ifid], $index);
		    $inid = array_search($index, $vdata['VALUE'][$ifid]);

		    //新しく追加されたインデックスはデータを含む可能性があるかもしれないので、あらかじめチェンジデータが存在するか確認する
		    if (isset($vdata['MIB']['VALUE'][$ifid]['ID_CHANGE'])) {
			//ある場合は、ID_CHANGE内に存在する手順に従ってインデックスをデータに移す処理を行う
			$id_change = $vdata['MIB']['VALUE'][$ifid]['ID_CHANGE'];
			$index_side = 0;
			foreach ($id_change as $ic) {
			    //var_dump($vdata['MIB']['VALUE'][$ic['MIBID']]['DATAOID'] . '<br>');
			    $ch_ic = changeMIBData('', $index, $ic['TARGET'], $ic['TGTYPE'], $ic['RPTYPE'], $ic['REPLACED'], $index_side);
			    if ($ch_ic['CODE'] == 0) {
				$vdata['VALUE'][$ic['MIBID']][$inid] = convertSimpleData($ch_ic['RES'], $vdata['MIB']['VALUE'][$ic['MIBID']]);
				$index_side = $ch_ic['INDEX_SIDE'];
				//インデックスサイズを変更する
			    } else {
				//抽出に失敗したらエラーを抽出する
				array_push($vdata['ERROR'], $ch_ic['RES']);
			    }
			}
		    }
		}
		if ($is_insert) {
		    insertGetData($getid, $set, $data, $index, $inid);
		}
		//データを挿入する
		$vdata['VALUE'][$set][$inid] = convertSimpleData($data, $setdata);
	    } else if ($iftype == 0) { //通常データの場合
		if ($is_insert) {
		    insertGetData($getid, $set, $data);
		}
		//データを挿入する
		$vdata['VALUE'][$set] = convertSimpleData($data, $setdata);
	    }
	} else {
	    array_push($vdata['ERROR'], '「' . $k . ' -> ' . $data . '」に紐づくMIBがありません');
	}
    }
    return $vdata;
}

/**
 * [GET] オブジェクト検索
 * 予め容易されたOIDリストを用いて、オブジェクト検索を行います
 * 
 * @param int $oid 検索対象OIDを指定します
 * @param array $oidlist MIBID別にOIDが格納されている配列をしています
 * @return int OIDが見つかったら、そのMIBIDを、見つからなかったら0を返します
 */
function searchOID($oid, $oidlist) {
    $index = 0;
    foreach ($oidlist as $i => $j) {
	if (preg_match('/' . $j['DATAOID'] . '.*/', $oid)) {
	    $index = $i;
	}
    }
    return $index;
}

/**
 * [GET] 単データ変換ファンクション
 * 1つのデータに対して、オプションをもとに変換します
 * 
 * @param string $data データを指定します
 * @param array $mib 本データに対するMIBを指定します
 * @return string $dataを変換した値を返します
 */
function convertSimpleData($data, $mib) {
    //型情報を取り除き、記号等も不要であれば削除
    $res = preg_replace('/(^"|"$)/', '', preg_replace('/^.+:\s/', '', $data));
    //チェンジデータがある場合
    if (isset($mib['CHANGE'])) {
	foreach ($mib['CHANGE'] as $ch) {
	    $res = changeMIBData($res, 0, $ch['TARGET'], $ch['TGTYPE'], $ch['RPTYPE'], $ch['REPLACED']);
	}
    }
    return $res;
}

/**
 * [GET] MIBデータ変換
 * MIBで取得したデータを視覚化しわかりやすいフォーマットで変換します
 * 
 * @param string $target_data 変換前のデータを指定します（ない場合は空白）
 * @param string $index 変換対象のインデックスを指定します（ない場合は空白か0）
 * @param string|int $target 対象ターゲットの値を指定します（ない場合は空白） 
 * @param string $tgtype 対象ターゲットのタイプを指定します（）
 * @param type $rptype 置換ターゲットのタイプを指定します（）
 * @param type $replaced 置換値を指定します（）
 * @param type $index_side インデックスの現在値を指定します（インデックスでない場合は0）
 * @return type
 */
function changeMIBData($target_data, $index, $target, $tgtype, $rptype,
	$replaced, $index_side = 0) {
    $res = '';

    switch ($tgtype) {
	case 1: $res = $target_data;
	    switch ($rptype) {
		case 0: $res = str_replace($target, $replaced, $res);
		    break;
		case 1: $res = implode(' ', str_split(substr($target, 0, $target), 1));
		    break;
	    }
	    break;
	case 2: $index_array = explode('.', $index);
	    $res = ['CODE' => 1, 'RES' => $index, 'INDEX_SIDE' => $index_side];
	    $res_data = getDataFromIndex($replaced, $rptype, $index_array, $index_side);
	    $res['CODE'] = $res_data['CODE'];
	    $res['RES'] = $res_data['DATA'];
	    $res['INDEX_SIDE'] = $res_data['DEM'] + $index_side;
	    break;
    }
    return $res;
}

/**
 * @param type $data
 * @param type $host
 * @param type $com
 * @param type $date
 * @param type $count
 * @return string
 */
function getAnalyCSVData($data, $host, $com, $date, $count) {
    $res = "Virtual Control SNMP Data CSV Generator v 1.5.0\n取得日付,$date\nホストアドレス,$host\nコミュニティ名,$com\n取得数,$count\nデータOID,英語名,日本語名,説明,値\n";

    foreach ($data as $gk => $gdata) {
	$res .= '+=============================================+';
	$res .= '【' . $gk . '】' . $gdata['MIB']['NAME'] . '\n';
	$val = $gdata['VALUE'];
	$mib = $gdata['MIB']['VALUE'];
	$error = $gdata['ERROR'];
	foreach ($val as $vk => $v) {
	    if (isset($mib[$vk])) {
		$mibdata = $mib[$vk];
		$res .= $mibdata['DATAOID'] . ',' . $mibdata['ENNAME'] . ',' . $mibdata['JPNAME'] . ',' . $mibdata['DESCR'] . ',';
		if (is_array($v)) {
		    if (in_array('DUMMY', $v)) {
			unset($v[0]);
		    }
		    $res .= str_replace('、', ',', str_replace(',', ' ', implode('、', $v)));
		} else {
		    $res .= str_replace('、', ',', str_replace(',', ' ', $v));
		}
		$res .= '\n';
	    }
	}
	$res .= 'エラー数,' . sizeof($error) . '\n';
	foreach ($error as $e) {
	    $res .= $e . '\n';
	}
    }

    return $res;
}

/**
 * 
 * @param type $getid
 * @param type $mibid
 * @param type $data
 * @param type $index
 * @param type $index_rk
 * @return bool
 */
function insertGetData($getid, $mibid, $data, $index = 0, $index_rk = 0): bool {
    $ins = insert('VC_GET', ['GETID', 'MIBID', 'MIBINDEX', 'MIBINDEXRK', 'DATA'], [$getid, $mibid, $index, $index_rk, $data]);
    return $ins;
}

/**
 * [GET] エージェント別過去履歴セレクトデータ挿入
 * エージェント別に、過去に取得した履歴のデータを日付とエージェントIDを使って挿入し、GETIDを取得します
 * 
 * @param date $date 取得した日時を指定します
 * @param int $agentid エージェントIDを指定します
 * @return null|int 挿入・取得に失敗したらnull、GETIDが取得できたら、そのIDを返します
 */
function insertGetSelectData($date, $agentid) {
    $res = insert('VC_GET_SELECT', ['GETTIME', 'AGENTID'], [$date, $agentid]);
    if ($res) {
	$sel = select(true, 'VC_GET_SELECT', 'GETID', 'WHERE GETTIME = \'' . $date . '\' AND AGENTID = ' . $agentid);
	if ($sel) {
	    $res = $sel['GETID'];
	} else {
	    $res = false;
	}
    }
    return $res;
}

/**
 * [GET] エージェント別過去履歴セレクトデータ取得
 * エージェント別に、過去に取得した履歴のデータを日付とGETIDで取得します
 * 
 * @param int $agentid エージェントIDを指定します
 * @return array|null 取得できたらGETIDとGETTIMEの連想配列、取得できなかったらnullが返されます
 */
function selectGetDateData($agentid, $is_select = true) {
    $res = null;
    $sel = select(false, 'VC_GET_SELECT', 'GETID, GETTIME', 'WHERE AGENTID = ' . $agentid);
    if ($sel) {
	$res = ($is_select) ? ['(選択)'] : [];
	while ($p = $sel->fetch_assoc()) {
	    $res[$p['GETID']] = $p['GETTIME'];
	}
    }
    return $res;
}

function getPastSelect($agentid) {
    $agent = getAgent($agentid);
    $past = selectGetDateData($agentid, false);
    $res = ['CODE' => 999, 'DATA' => 'エージェント選択に失敗しました'];
    if($agent && $past) {
	$res = ['CODE' => 4, 'DATA' => ['AGENT' => '【' . $agent['HOSTADDRESS'] . '】' . $agent['COMMUNITY'], 'PAST_DATA' => $past]];
    } else if($agent && !$past) {
	$res = ['CODE' => 4, 'DATA' => ''];
    }
    return $res;
}

/**
 * [GET] エージェント別過去履歴セレクト日付取得
 * エージェント別に、過去に取得した履歴のデータの日時を取得します
 * 
 * @param int $getid GETIDを指定します
 * @return string 日時を返します
 */
function selectGetDateTime($getid) {
    $res = null;
    $sel = select(true, 'VC_GET_SELECT', 'GETTIME', 'WHERE GETID = ' . $getid);
    if ($sel) {
	$res = $sel['GETTIME'];
    }
    return $res;
}

/**
 * [GET] GETID, GROUPID別過去履歴データ取得
 * 
 * @param int $getid GETIDを指定します
 * @param string $groupoid グループOIDを指定します
 * @return array|null SNMPWALKデータに類似した連想配列データを返します
 */
function getPastGet($getid, $groupoid) {
    $res = null;
    $sel = select(false, 'VC_GET a INNER JOIN VC_MIB b ON a.MIBID = b.MIBID INNER JOIN VC_MIB_GROUP c ON b.GROUPID = c.GROUPID', 'CONCAT (b.DATAOID, \'.\', a.MIBINDEX) AS OID, a.DATA', 'WHERE a.GETID = ' . $getid . ' AND c.GROUPOID = \'' . $groupoid . '\'');
    if ($sel) {
	$res = [];
	while ($s = $sel->fetch_assoc()) {
	    $res[$s['OID']] = $s['DATA'];
	}
    }
    return $res;
}
