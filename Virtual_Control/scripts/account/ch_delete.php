<?php

/* UserInformation Deleting Program
 * ユーザ情報の変更を行います。
 * ['res'] =>
 * 0. 正常終了
 * 1. データベースエラー もしくは ヒューマンエラー
 */
$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg) ? strtolower($requestmg) : '';
if ($request !== 'xmlhttprequest') {
    http_response_code(403);
    header('Location: ../../403.php');
    exit;
}

include_once '../sqldata.php';
include_once '../common.php';
include_once '../sqldata.php';
include_once './checkers.php';
include_once '../dbconfig.php';
include_once '../session_chk.php';

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    //1. データの取得
    $function_id = filter_input(INPUT_POST, 'f_num', FILTER_SANITIZE_STRING);
    $index = filter_input(INPUT_POST, 'ch_in', FILTER_SANITIZE_STRING);
    $err_arr = array();

    //2. データの確認
    //入力項目確認
    $function = 0;
    if ($index) {
	$function = 6;
    }
    //相違確認
    if ($function != $function_id) {
	$function = 0;
    }

    //2. データの確認
    switch ($function) {
	case 0: //FAILED
	    array_push($err_arr, '・正しく値が取得できませんでした');
	    array_push($err_arr, '・あなたの行っている行為と入力した項目が一致しません');
	    array_push($err_arr, '・不正行為は行わないようにお願い致します');
    }

    $err_text = implode($err_arr, '<br>');
    $code = 0;
    if ($err_text != "") {
	$code = 1;
    } else {
	switch ($function) {
	    case 6: //DELETE
		$flag = true;
		$sql_s01 = select(false, 'MKTK_USERS_SET', 'SETID', "WHERE USERINDEX = $index");
		if ($sql_s01) {
		    while ($row = $sql_s01->fetch_assoc) {
			$setid = $row['SETID'];
			$sql_s02 = select(false, 'MKTK_TEST', 'TESTID', "WHERE SETID = $setid");
			if ($sql_s02) {
			    while ($row = $sql_s02->fetch_assoc()) {
				$testid = $sql_s02['TESTID'];
				$d01 = delete('MKTK_TEST_QS', "WHERE TESTID = $testid");
				$d02 = delete('MKTK_TEST', "WHERE TESTID = $testid");
				$flag &= ($d01 && $d02);
			    }
			}
			$d01 = delete('MKTK_TEST', "WHERE SETID = $setid");
			$d02 = delete('MKTK_LS_LIST', "WHERE SETID = $setid");
			$d03 = delete('MKTK_USERS_SL', "WHERE SETID = $setid");
			$d04 = delete('MKTK_USERS_SET', "WHERE SETID = $setid");
			$flag &= ($d01 && $d02 && $d03 && d04);
		    }
		}
		$d04 = delete('MKTK_USERS', "WHERE USERINDEX = $index");
		if(!$flag || !$d04) {
		    $code = 1;
		}
		break;
	}
    }
    $r = [
	'res' => $code
    ];
    ob_get_clean();
    echo json_encode($r);
}