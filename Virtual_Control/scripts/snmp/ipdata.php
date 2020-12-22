<?php

/**
 * IPアドレスのアドレスタイプを返します
 * @param string $data	アドレスタイプの数値データです
 * @return string	4..IPv4, 16..IPv6
 */
function getIPType($data): string {
    $res = '該当なし';
    switch($data) {
	case 1:
	    $res = 'IPv4';
	    break;
	case 2:
	    $res = 'IPv6';
	    break;
    }
    return $res;
}

/**
 * 4桁のIPv4アドレスを左から読み、アドレス表記にします
 * @param array $data	IPアドレスの数値配列です
 * @return string	IPv4アドレスデータを提供します
 */
function getIPv4($data, $issubnet = false): string {
    if($issubnet) {
	$s_data = implode('.', array_slice($data, 0, 4));
	$p_data = $data[sizeof($data) - 1];
	return $s_data . '/' . $p_data;
    } else {
	return implode('.', $data);
    }
}

/**
 * 16桁のIPv6アドレスを左側から読み、16進数化・0省略化を行います。
 * @param array $data
 */
function getIPv6($data, $issubnet = false): string {
    $start_first_zero = false;
    $end_first_zero = false;
    $res = [];
    $res_i = 0;
    for ($i = 0; $i < 16; $i++) {
	$var = $data[$i];
	if ($var == 0) {
	    if($end_first_zero) {
		array_push($res, '0');
		$res_i += 1;
	    } else if((!$end_first_zero && $res_i - 1 >= 0 && $res[$res_i - 1] != ':') || ((!$end_first_zero && $res_i == 0))) {
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
    if(!$dataset) {
	$dataset = '::';
    }
    if($issubnet) {
	$p_data = $data[sizeof($data) - 1];
	return $dataset . '/' . $p_data;
    } else {
        return $dataset;
    }

}