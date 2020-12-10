<?php

function table_create($table_id, $t_id, $table_title_icon, $table_title) {
    return '<div id="' . $table_id . '-' . $t_id . '"><h3 class="text-left text-body"><i class="fas fa-fw fa-' . $table_title_icon . '"></i>' . $table_title . '</h3><table class="table table-hover"><tbody>';
}

function table_sub_create($table_id, $t_id, $table_title_icon, $table_title) {
    return '<div id="' . $table_id . '-' . $t_id . '"><h4 class="text-left text-body"><i class="fas fa-fw fa-' . $table_title_icon . '"></i>' . $table_title . '</h4><table class="table table-hover"><tbody>';
}

function table_create_next($table_id, $t_id) {
    return '<div id="' . $table_id . '-' . $t_id . '"><table class="table table-hover"><tbody>';
}

function table_close() {
    return '</tbody></table></div>';
}

function var_num($oid, $icon, $descr, $japtlans, $value) {
    return '<tr>'
	    . '<td>' . '<h5>' . $oid . '</h5><i class="' . $icon . ' fa-fw"></i>' . $japtlans . '<br><small>' . $descr . '<br>' . '</td>'
	    . '<td>' . $value . '</td>'
	    . '</tr>';
}

function var_nums($table_id, &$t_id, &$i, $data) {
    //定義
    $s_i = $i + 1;
    $oid = $data['OID'][$s_i];
    $m_size = count($data['OID']);
    $cr_check = $data['CHECK'][$oid];
    $sp_d = [];

    //1: detailの付加 [タイトルの作成]
    $title_oid = $data['OID'][$s_i - 1];
    $title_jap = $data['JAPTLANS'][$title_oid];
    $title = '【' . $title_oid . '】' . $data['DESCR'][$title_oid] . ' : ' . $data['JAPTLANS'][$title_oid];
    $res = '<details class="main"><summary class="summary">' . $title . '</summary><div class="details-content">';
    //2: データの加工（個別データに振り分け）
    while ($data['CHECK'][$oid] == $cr_check) {
	$d_id = 0;
	if (isset($data['VALUE'][$oid])) {
	    foreach ($data['VALUE'][$oid] as $v) {
		$sp_d[$d_id][$oid] = $v;
		$d_id += 1;
	    }
	}
	$i += 1;
	if ($i < $m_size) {
	    $oid = $data['OID'][$i];
	} else {
	    break;
	}
    }
    //3: エンドポイントをもってくる
    $e_i = $i;

    //4: テーブル作成
    $res .= table_vertical_snmp($table_id, $t_id, $title_jap, $data, $sp_d, $s_i, $e_i);
    $res .= '</div></details>';

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
		$result .= table_create_next($table_id, $t_id);
	    }
	    $value = "<データなし>";
	    if(isset($table_datas['VALUE'][$oid][0])) {
		$value = $table_datas['VALUE'][$oid][0];
	    }
	    $result .= var_num($oid, $table_datas['ICON'][$oid], $table_datas['DESCR'][$oid], $table_datas['JAPTLANS'][$oid], $value);
	}
    }
    if ($flag) {
	$result .= table_close();
    }
    return $result;
}

function table_vertical_snmp($table_id, &$t_id, $table_title, $head_data, $table_data, $start_id, $end_id) {
    $result = '';

    for ($i = 0; $i < sizeof($table_data); $i++) {
	$start_oid = $head_data['OID'][$start_id - 1];
	$index = $i + 1;
	if(isset($head_data['SUB_VALUE'][$start_oid])) {
	    $index = $head_data['SUB_VALUE'][$start_oid][$i];
	}
	$result .= '<details class="sub"><summary class="summary-sub">【' . $index . '】</summary><div class="details-content-sub">';
	$result .= table_sub_create($table_id, $t_id, 'table', $table_title . "($index)");
	for ($j = $start_id; $j < $end_id; $j++) {
	    $oid = $head_data['OID'][$j];
	    $value = '<データなし>';
	    if (isset($table_data[$i][$oid])) {
		$value = $table_data[$i][$oid];
	    }
	    $result .= var_num($oid, $head_data['ICON'][$oid], $head_data['DESCR'][$oid], $head_data['JAPTLANS'][$oid], $value);
	}
	$t_id += 1;
	$result .= table_close();
	$result .= '</div></details>';
    }
    return $result;
}
