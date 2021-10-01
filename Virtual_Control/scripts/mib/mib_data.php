<?php

include_once __DIR__ . '/../general/sqldata.php';

function getMIBGroup($rev = '') {
    $rev_t = ($rev && is_array($rev)) ? implode(', ', $rev) : '';
    $sel = select(false, 'VC_MIB_GROUP a INNER JOIN VC_MIB b ON a.GROUPID = b.GROUPID', 'a.GROUPID, a.GROUPNAME, a.GROUPOID, COUNT(*) AS GCOUNT', 'WHERE b.DATATYPE = 0' . (($rev_t) ? ' AND b.GROUPID IN (' . $rev_t . ')' : '') . ' GROUP BY a.GROUPID, a.GROUPNAME, a.GROUPOID');
    $res = null;
    if($sel) {
	$res = [];
	while($s = $sel->fetch_assoc()) {
	    $gid = $s['GROUPID'];
	    unset($s['GROUPID']);
	    $res[$gid] = $s;
	}
    }
    return $res;
}

/**
 * [GET] 全データ取得
 * データタイプ、テーブルタイプの関係なく、全てのデータを取得します
 * @return array|null 取得できる場合は、VC_MIB_GROUPのデータを配列で、そうでない場合はnullを返します
 */
function getAll() {
    $res = false;
    $change = getMIBChange();
    $sel = select(false, 'VC_MIB a LEFT OUTER JOIN (SELECT ICONID, CONCAT(CASE ICONGROUP WHEN 0 THEN \'fas fa-\' WHEN 1 THEN \'far fa-\' END, ICONNAME) AS ICON FROM VC_ICON) b ON a.ICONID = b.ICONID INNER JOIN VC_MIB_GROUP c ON a.GROUPID = c.GROUPID', 'c.GROUPOID, c.GROUPNAME, a.MIBID, a.DATAOID, a.DATATYPE, a.TABLETYPE, a.TABLEIFID, a.ENNAME, a.JPNAME, b.ICON, a.DESCR', 'GROUP BY c.GROUPOID, c.GROUPNAME, a.MIBID, a.DATAOID, a.DATATYPE, a.TABLETYPE, a.TABLEIFID, a.ENNAME, a.JPNAME, b.ICON, a.DESCR ORDER BY c.GROUPID, a.MIBID');
    if ($sel && $change) {
	$res = [];
	while ($vs = $sel->fetch_assoc()) {
	    $goid = $vs['GROUPOID'];
	    $mibid = $vs['MIBID'];
	    if (!isset($res[$goid])) {
		$res[$goid]['NAME'] = $vs['GROUPNAME'];
		$res[$goid]['VALUE'] = [];
	    }
	    $res[$goid]['VALUE'][$mibid] = ['DATAOID' => $vs['DATAOID'], 'DATATYPE' => $vs['DATATYPE'], 'TABLETYPE' => $vs['TABLETYPE'], 'TABLEIFID' => $vs['TABLEIFID'], 'ENNAME' => $vs['ENNAME'], 'JPNAME' => $vs['JPNAME'], 'ICON' => $vs['ICON'], 'DESCR' => $vs['DESCR']];
	    if (isset($change[$mibid])) {
		$res[$goid]['VALUE'][$mibid]['CHANGE'] = [];
		foreach ($change[$mibid] as $ch) {
		    if ($ch['TARGET'] == '' && $ch['TGTYPE'] == 2) {
			$ifid = $res[$goid]['VALUE'][$mibid]['TABLEIFID'];
			if (!isset($res[$goid]['VALUE'][$ifid]['ID_CHANGE'])) {
			    $res[$goid]['VALUE'][$ifid]['ID_CHANGE'] = [];
			}
			array_push($res[$goid]['VALUE'][$ifid]['ID_CHANGE'], $ch);
		    } else {
			unset($ch['MIBID']);
			if (!isset($res[$goid]['VALUE'][$mibid]['CHANGE'])) {
			    $res[$goid]['VALUE'][$mibid]['CHANGE'] = [];
			}
			array_push($res[$goid]['VALUE'][$mibid]['CHANGE'], $ch);
		    }
		}
	    }
	}
    }
    return $res;
}

/**
 * [GET] MIBID検索
 * 分野別に、MIBIDを検索します
 * 
 * @param string $oid OIDを指定します
 * @param int $type データタイプを選択します（Default:0, トラップの場合: 1）
 * @return int 検索できたらそのMIBIDを、できなかったら0を返します
 */
function searchMIBId($oid, $type = 0) {
    $res = 0;
    $sel = select(true, 'VC_MIB', 'MIBID', 'WHERE DATAOID = \'' . $oid . '\' AND DATATYPE = ' . $type);
    if ($sel) {
	$res = $sel['MIBID'];
    }
    return $res;
}

/**
 * [GET] SNMPWALK用フィルタデータ取得
 * getAll() のSNMPWALKフィルタを適用したバージョンです
 * @param int $agentid
 */
function getMIBWalkData($agentid) {
    $res = false;
    $change = getMIBChange();
    $sel = select(false, 'VC_MIB a LEFT OUTER JOIN (SELECT ICONID, CONCAT(CASE ICONGROUP WHEN 0 THEN \'fas fa-\' WHEN 1 THEN \'far fa-\' END, ICONNAME) AS ICON FROM VC_ICON) b ON a.ICONID = b.ICONID INNER JOIN (SELECT GROUPID, GROUPOID, GROUPNAME FROM VC_MIB_GROUP WHERE GROUPID IN (SELECT GROUPID FROM VC_AGENT_MIB WHERE AGENTID = ' . $agentid . ' ORDER BY GROUPID)) c ON a.GROUPID = c.GROUPID', 'c.GROUPOID, c.GROUPNAME, a.MIBID, a.DATAOID, a.TABLETYPE, a.TABLEIFID, a.ENNAME, a.JPNAME, b.ICON, a.DESCR', 'WHERE a.DATATYPE = 0 GROUP BY c.GROUPOID, c.GROUPNAME, a.MIBID, a.DATAOID, a.TABLETYPE, a.TABLEIFID, a.ENNAME, a.JPNAME, b.ICON, a.DESCR ORDER BY c.GROUPID, a.MIBID');
    if ($sel && $change) {
	$res = [];
	while ($vs = $sel->fetch_assoc()) {
	    $goid = $vs['GROUPOID'];
	    $mibid = $vs['MIBID'];
	    if (!isset($res[$goid])) {
		$res[$goid]['NAME'] = $vs['GROUPNAME'];
		$res[$goid]['VALUE'] = [];
	    }
	    $res[$goid]['VALUE'][$mibid] = ['DATAOID' => $vs['DATAOID'], 'TABLETYPE' => $vs['TABLETYPE'], 'TABLEIFID' => $vs['TABLEIFID'], 'ENNAME' => $vs['ENNAME'], 'JPNAME' => $vs['JPNAME'], 'ICON' => $vs['ICON'], 'DESCR' => $vs['DESCR']];
	    //$res[$goid]['OIDLIST'][$mibid] = $vs['DATAOID'];
	    if (isset($change[$mibid])) {
		foreach ($change[$mibid] as $ch) {
		    if ($ch['TARGET'] == '' && $ch['TGTYPE'] == 2) {
			$ifid = $res[$goid]['VALUE'][$mibid]['TABLEIFID'];
			if (!isset($res[$goid]['VALUE'][$ifid]['ID_CHANGE'])) {
			    $res[$goid]['VALUE'][$ifid]['ID_CHANGE'] = [];
			}
			array_push($res[$goid]['VALUE'][$ifid]['ID_CHANGE'], $ch);
		    } else {
			unset($ch['MIBID']);
			if (!isset($res[$goid]['VALUE'][$mibid]['CHANGE'])) {
			    $res[$goid]['VALUE'][$mibid]['CHANGE'] = [];
			}
			array_push($res[$goid]['VALUE'][$mibid]['CHANGE'], $ch);
		    }
		}
	    }
	}
    }

    return $res;
}

/**
 * [GET] MIB変換データ取得
 * MIDID別に、MIB変換するためのデータを取得します
 * @return array|null MIB変換データを配列で返します
 */
function getMIBChange() {
    $sel = select(false, 'VC_MIB_CHANGE', 'MIBID, TARGET, TGTYPE, RPTYPE, REPLACED');
    $res = [];
    if ($sel) {
	while ($d = $sel->fetch_assoc()) {
	    $u = $d['MIBID'];
	    if (!isset($res[$u])) {
		$res[$u] = [];
	    }
	    array_push($res[$u], $d);
	}
    }
    return $res;
}

/**
 * [GET] グループチェック
 * OIDをもとに、すでにデータにあるかどうか、またグループ対象となる子がすでにグループ化されていないかどうかを
 * @param string $check_oid 対象OIDを指定します
 * @param bool グループ登録できるかどうかを判定します（可: true, 不可: false）
 */
function checkMIBGroup($check_oid) {
    $result = true;
    if (preg_match('/[0-9][.]{1,}[1-9]$/u', $check_oid)) {
	$res = select(true, 'VC_MIB_GROUP', 'CASE COUNT(*) WHEN 0 THEN 1 ELSE 2 END AS EXFLAG', 'WHERE GROUPOID = \'' . $check_oid . '\' OR GROUPOID LIKE \'' . $check_oid . '%\' OR EXISTS (SELECT * FROM VC_MIB WHERE VC_MIB.DATAOID LIKE \'' . $check_oid . '%\' AND GROUPID <> 0)');
	if ($res) {
	    $result = ($res['EXFLAG'] == 1);
	}
    }
    return $result;
}

/**
 * [GET] グループ作成
 * グループが追加できるかをチェックした上で、指定されたOIDと名前でグループを登録します
 * 
 * @param string $oid グループOIDを指定します
 * @param string $name グループ名を指定します
 * @return bool 作成成功したらtrue、それ以外はfalseを返します
 */
function createMIBGroup($oid, $name) {
    $res = false;
    $check = checkMIBGroup($oid);

    if ($check) {
	$ins = insert('VC_MIB_GROUP', ['GROUPOID', 'GROUPNAME'], [$oid, $name]);
	$upd = update('VC_MIB', 'GROUPID', '(SELECT GROUPID FROM VC_MIB_GROUP WHERE GROUPOID = \'' . $oid . '\')', 2, 'WHERE DATAOID LIKE \'' . $oid . '.%\';');
	$res = ($ins && $upd);
    }
    return $res;
}

/**
 * [GET] グループ名変更
 * グループ名を変更します
 * 
 * @param int $groupid グループIDを指定します
 * @param string $name 変更後の名前を指定します
 * @return 変更できたらtrue、できなかったらfalseを返します
 */
function updateMIBGroupName($groupid, $name) {
    $upd = update('VC_MIB_GROUP', 'GROUPNAME', $name, 'WHERE GROUPID = ' . $groupid);
    return $upd;
}

/**
 * [GET] グループ削除
 * グループIDを指定して、該当する部分を削除します
 * 
 * @param int $groupid グループIDを指定します
 * @return bool 削除できたらtrue、できなかったらfalseを返します
 */
function deleteMIBGroup($groupid) {
    $res = false;
    $sel = select(true, 'VC_MIB_GROUP', 'GROUPOID', 'GROUPID = ' . $groupid);
    if ($sel) {
	$ins = delete('VC_MIB_GROUP', 'WHERE GROUPID = ' . $groupid);
	$upd = update('VC_MIB', 'GROUPID', 0, 'WHERE DATAOID LIKE \'' . $sel['GROUPOID'] . '.%\';');
	$res = ($ins && $upd);
    }
    return $res;
}
