<?php

function table_create($table_id, $t_id, $table_title_icon, $table_title) {
    return '<div id="' . $table_id . '-' . $t_id . '"><h3 class="text-left text-body"><i class="fas fa-fw fa-' . $table_title_icon . '"></i>' . $table_title . '</h3><table class="table table-hover"><tbody>';
}

function table_close() {
    return '</tbody></table></div>';
}

function var_num($oid, $icon, $descr, $japtlans, $value) {
    return '<tr>'
	    . '<td>' . '<h5>' . $oid . '</h5><i class="' . $icon . '"></i>' . $japtlans . '<br><small>' . $descr . '<br>' . '</td>'
	    . '<td>' . $value . '</td>'
	    . '</tr>';
}

function var_nums($table_id, &$t_id, &$i, $data) {
    //定義
    $s_i = $i;
    $d_id = 0;
    $oid = $data['OID'][$i];
    $m_size = count($data['OID']);
    $cr_check = $data['CHECK'][$oid];
    $sp_d = [];

    //1: detailの付加 [タイトルの作成]
    $title_jap = $data['JAPTLANS'][$oid];
    $title = "【" . $oid . "】" . $data['DESCR'][$oid] . " : " . $data['JAPTLANS'][$oid];
    $res = '<details><summary>' . $title . "</summary>";
    //2: データの加工（個別データに振り分け）
    while ($data['CHECK'][$oid] == $cr_check && $i < $m_size) {
	foreach ($data['VALUE'][$oid] as $v) {
	    $sp_d[$d_id][$oid] = $v;
	    $d_id += 1;
	}
	$i += 1;
	$oid = $data['OID'][$i];
    }
    //3: エンドポイントをもってくる
    $e_i = $i;

    //4: テーブル作成
    $res .= table_vertical_snmp($table_id, $t_id, $title_jap, $data, $sp_d, $s_i, $e_i);
    $res .= '</details>';

    $i -= 1;
    return $res;
}

function table_result($table_id, $table_title, $table_title_icon, $table_datas) {
    $t_id = 1;
    $result = table_create($table_id, $t_id, $table_title_icon, $table_title);
    $flag = true;
    for ($i = 0; $i < sizeof($table_datas['OID']); $i++) {
	$oid = $table_datas['OID'][$i];
	$check = $table_datas['CHECK'][$oid];
	if ($check != 0) {
	    if ($flag) {
		$flag = false;
		$result .= table_close();
	    }
	    $result .= var_nums($table_id, $t_id, $i, $table_datas);
	} else {
	    if (!$flag) {
		$flag = true;
		$t_id += 1;
		$result .= table_create($table_id, $t_id, $table_title_icon, $table_title);
	    }
	    $result .= var_num($oid, $table_datas['ICON'][$oid], $table_datas['DESCR'][$oid], $table_datas['JAPTLANS'][$oid], $table_datas['VALUE'][$oid]);
	}
    }
    $result .= table_close();
    return $result;
}

function table_vertical_snmp($table_id, &$t_id, $table_title, $head_data, $table_data, $start_id, $end_id) {
    $result = '';

    for ($i = 0; $i < sizeof($table_data); $i++) {
	$result .= '<details><summary>【' . intval($i + 1) . '】</summary>';
	$result .= table_create($table_id, $t_id, 'table', $table_title);
	for ($j = $start_id; $j < $end_id; $j++) {
	    $oid = $head_data['OID'][$j];
	    $result .= var_num($oid, $head_data['ICON'][$oid], $head_data['DESCR'][$oid], $head_data['JAPTLANS'][$oid], $table_data[$i][$oid]);
	}
	$result .= table_close();
	$result .= '</details>';
    }
    return $result;
}
