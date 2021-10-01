<?php

include_once __DIR__ . '/../general/sqldata.php';

function getWarnData() {
    $sel = select(false, 'VC_TRAP a LEFT OUTER JOIN VC_MIB b1 ON a.TRAPMIBID = b1.MIBID LEFT OUTER JOIN VC_MIB b2 ON a.SYSTEMMIBID = b2.MIBID LEFT OUTER JOIN VC_AGENT c ON a.AGENTID = c.AGENTID', 'a.TRAPID, c.HOSTADDRESS, c.COMMUNITY, b1.DATAOID AS TRAPOID, b1.ENNAME AS TRAPENNAME, b1.JPNAME AS TRAPJPNAME, b1.DESCR AS TRAPDESCR, b2.DATAOID AS SYSTEMOID, b2.ENNAME AS SYSTEMENNAME, b2.JPNAME AS SYSTEMJPNAME, b2.DESCR AS SYSTEMDESCR, a.INTERFACEID, a.INFO, a.SYSTIME, a.TRAPTIME', 'ORDER BY TRAPID');
    $res = false;
    if($sel) {
	$res = ['VALUE' => [], 'DATE' => date('Y/m/d H:i:s'), 'COUNT' => 0, 'UPDATED_LOG' => []];
	$csv_data = ['+-Virtual Control - WARN DATA--------------------------------+',
	    '取得時刻: ' . date('Y/m/d H:i:s'),
	    'エージェント情報,トラップ情報,システム情報,トラップ説明,システム説明,その他情報,システム稼働時間,トラップ発生時刻'];
	while($d = $sel->fetch_assoc()) {
	    $date = new DateTime($d['TRAPTIME']);
	    $ti = $date->format('H:i:s');
	    $de = $date->format('Y/m/d');
	    if(!isset($res['VALUE'][$de])) {
		$res['VALUE'][$de] = [];
	    }
	    
	    
	    $ds = ['AGENT' => ($d['HOSTADDRESS'] != '' && $d['COMMUNITY'] != '') ? $d['HOSTADDRESS'] . ' (' . $d['COMMUNITY'] . ')' : '〈不明〉',
		'TRAP' => ($d['TRAPOID'] != '' && $d['TRAPENNAME'] != '' && $d['TRAPJPNAME'] != '') ? '【' . $d['TRAPOID'] . '】' . $d['TRAPJPNAME'] . ' - ' . $d['TRAPENNAME'] : '〈不明〉',
		'SYSTEM' => ($d['SYSTEMOID'] != '' && $d['SYETMENNAME'] != '' && $d['SYSTEMJPNAME'] != '') ? '【' . $d['SYSTEMOID'] . '】' . $d['SYSTEMJPNAME'] . ' - ' . $d['SYSTEMENNAME'] : '〈不明〉',
		'TRAP_DESCR' => $d['TRAPDESCR'],
		'SYSTEM_DESCR' => $d['SYSTEMDESCR'],
		'OTHER' => $d['INFO'],
		'SYSTIME' => $d['SYSTIME'],
		'TRAPTIME' => $ti
	    ];
	    array_push($res['VALUE'][$de], $ds);
	    
	    array_push($csv_data, str_replace('|', ',', (str_replace(',', ' ', implode('|', $ds)))));
	    $res['UPDATED_LOG'][$de] = $ds['TRAPTIME'] . ' : ' . $ds['AGENT'] . ' -> ' . $ds['TRAP'];
	    $res['COUNT']++;
	}
	$res['CSV'] = implode('\n', $csv_data);
    }
    return $res;
}