<?php

/**
 * [GET] インデックスからデータを取得
 * 
 * オプションおよびインデックスの配列、現在の配列のインデックスを指定し、データを取得します。<br>
 * エラーが発生する可能性がある場合は、CODEを1で返し、DATAにエラー構文が格納されます。
 * 
 * @param int $option オプション
 * @param array $data_array インデックス配列
 * @param int $data_array_index 現在のインデックス配列のインデックス
 * 
 * @return array ['CODE' => (0 or 1), 'DATA' => (データ), 'DEM' => (ズレ値)]
 */
function getDataFromIndex($option, $data_array, $data_array_index) {
    $res = ['CODE' => 1, 'DATA' => '', 'DEM' => 0];
    if ($option >= 51 && $option <= 59) {
	$dem = intval($option) - 50;
	if (isset($data_array[$data_array_index + $dem - 1])) {
	    $res['CODE'] = 0;
	    $res['DATA'] = implode(' ', array_slice($data_array, $data_array_index, $dem));
	    $res['DEM'] = $dem;
	} else {
	    $res['DATA'] = '「インデックス位置: ' . ($data_array_index + 1) . 'から' . $dem . '文字分を取り出す」が適用できませんでした。';
	}
    } else if ($option == 10 || $option == 20 || $option == 24) {
	if($option == 24) {
	    $arr = getIP($data_array, $data_array_index, 4, ($option == 10));
	} else {
	    $arr = getIP($data_array, $data_array_index, 0, ($option == 10));
	}
	$res['DATA'] = $arr['DATA'];
	if ($arr['CODE'] == 0) {
	    $res['CODE'] = 0;
	    $res['DEM'] = $arr['DEM'];
	}
    } else if ($option == 30) {
	$dem = intval($data_array[$data_array_index - 1]);
	if (isset($data_array[$data_array_index + $data_array[$data_array_index - 1]])) {
	    $res['CODE'] = 0;
	    $res['DEM'] = $dem;
	    $res['DATA'] = implode(' ', array_slice($data_array, $data_array_index, $dem));
	} else {
	    $res['DATA'] = '「インデックス位置: ' . ($data_array_index + 1) . 'から' . $dem . '文字分取り出す」が適用できませんでした。';
	}
    } else if ($option == 40) {
	$dem = 1;
	if (isset($data_array[$data_array_index])) {
	    $res['CODE'] = 0;
	    $res['DEM'] = $dem;
	    $res['DATA'] = getIPType($data_array[$data_array_index]);
	} else {
	    $res['DATA'] = '「インデックス位置: ' . ($data_array_index + 1) . 'から' . $dem . '文字分を取り出し、IPバージョンにコンバートする」が適用できませんでした。';
	}
    }
    return $res;
}

/**
 * IPアドレスのアドレスタイプを返します
 * @param string $data	アドレスタイプの数値データです
 * @return string	4..IPv4, 16..IPv6
 */
function getIPType($data): string {
    $res = '該当なし';
    switch ($data) {
	case 0: $res = '特定不可';
	    break;
	case 1: $res = 'IPv4';
	    break;
	case 2: $res = 'IPv6';
	    break;
    }
    return $res;
}

function getIP($array, $index, $size = 0, $isnet = false): array {
    $res = ['CODE' => 1, 'DATA' => '', 'DEM' => 0];
    if ($size != 0) {
	if (isset($array[$index + $size - 1])) {
	    $res['CODE'] = 0;
	    $res['DATA'] = getIPv4(array_slice($array, $index, $size), $isnet);
	    $res['DEM'] = $size;
	} else {
	    $res['DATA'] = '「インデックス位置: ' . ($index + 1) . 'から' . $size . '文字分を使いIPv4アドレスを作成する」が適用できませんでした。';
	}
    } else {
	$add_i = ($isnet) ? 1 : 0;
	switch ($array[$index]) {
	    case 0:
		$res['CODE'] = 0;
		$res['DATA'] = 'IPアドレス特定不可';
		$res['DEM'] = 1 + $add_i;
		break;
	    case 4:
		$dem = 5 + $add_i;
		if (isset($array[$index + $dem - 1])) {
		    $res['CODE'] = 0;
		    $res['DATA'] = getIPv4(array_slice($array, $index + 1, $dem - 1), $isnet);
		    $res['DEM'] = $dem;
		} else {
		    if ($isnet) {
			$res['DATA'] = '「インデックス位置: ' . ($index + 1) . 'から' . ($dem - 1) . '文字分を使いIPv4アドレスを作成する」が適用できませんでした。';
		    } else {
			$res['DATA'] = '「インデックス位置: ' . ($index + 1) . 'から' . ($dem - 1) . '文字分を使いIPv4アドレス（ポート番号つき）を作成する」が適用できませんでした。';
		    }
		}
		break;
	}
	if ($array[$index] >= 16) {
	    $dem = intval($array[$index]) + $add_i + 1;
	    if (isset($array[$index + $add_i - 1])) {
		$res['CODE'] = 0;
		$res['DATA'] = getIPv6(array_slice($array, $index + 1, $array[$index] + $add_i), $array[$index], $isnet);
		$res['DEM'] = $array[$index] + $add_i + 1;
	    } else {
		if ($isnet) {
		    $res['DATA'] = '「インデックス位置: ' . ($index + 1) . 'から' . $dem . '文字分を使いIPv6アドレスを作成する」が適用できませんでした。';
		} else {
		    $res['DATA'] = '「インデックス位置: ' . ($index + 1) . 'から' . $dem . '文字分を使いIPv6アドレス（ポート番号つき）を作成する」が適用できませんでした。';
		}
	    }
	}
    }
    return $res;
}

/**
 * 4桁のIPv4アドレスを左から読み、アドレス表記にします
 * @param array $data	IPアドレスの数値配列です
 * @param bool $isport 表記にポート番号があるかどうかを指定します
 * @return string	IPv4アドレスデータを返します
 */
function getIPv4($data, $isport = false): string {
    if ($isport) {
	$s_data = implode('.', array_slice($data, 0, 4));
	$p_data = $data[sizeof($data) - 1];
	return $s_data . '/' . $p_data;
    } else {
	return implode('.', $data);
    }
}

/**
 * 16桁のIPv6アドレスを左側から読み、16進数化・0省略化を行います。
 * @param array $data IPv6アドレスが格納されている配列です
 * @param bool $isport サブネットが必要かどうかを指定します（Default: false）
 * @return string IPv6アドレスデータを返します
 */
function getIPv6($data, $isport = false): string {
    $start_first_zero = false;
    $end_first_zero = false;
    $res = [];
    $res_i = 0;
    for ($i = 0; $i < sizeof($data); $i++) {
	$var = $data[$i];
	if ($var == 0) {
	    if ($end_first_zero) {
		array_push($res, '0');
		$res_i += 1;
	    } else if ((!$end_first_zero && $res_i - 1 >= 0 && $res[$res_i - 1] != ':') || ((!$end_first_zero && $res_i == 0))) {
		array_push($res, ':');
		$res_i += 1;
		$start_first_zero = true;
	    }
	} else {
	    if ($start_first_zero) {
		$end_first_zero = true;
	    }
	    array_push($res, dechex(intval($var)));
	    $res_i += 1;
	}
    }

    $dataset = str_replace(':::', '::', implode(':', $res));
    if (!$dataset || $dataset == ':') {
	$dataset = '::';
    }
    if ($isport) {
	$p_data = $data[sizeof($data) - 1];
	return $dataset . '/' . $p_data;
    } else {
	return $dataset;
    }
}
