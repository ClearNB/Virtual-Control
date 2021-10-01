<?php

include_once  __DIR__ . '/sqldata.php';

/**
 * [DATA CHECK MODULE]
 * データ内容をチェックする共通ファンクションを提供します
 */

/**
 * [GET] 文字列長さチェック
 * 文字列の長さが指定された長さ以下であるかどうかをチェックします<br>
 * 【注意】バイト数を単位に比べます
 * @param string $string 文字列を指定します
 * @param int $validate_length ルールに基づいて指定されたバイト数を指定します
 * @return 文字列の長さが指定された長さ以下であればtrue、そうでなければfalseを返します
 */
function check_length($string, $validate_length) {
    return (strlen($string) <= $validate_length);
}

/**
 * [GET] 対象値チェック
 * データベースの指定されたテーブルの項目内に、特定の値のレコードが存在する個数についてチェックします
 * @param string $table テーブルを指定します
 * @param string $column 項目を指定します
 * @param string $value その項目に対する項目値を指定します
 */
function check_exists($table, $column, $value) {
    $res = 0;
    if(gettype($value) == 'string') {
	$value = '\'' . $value . '\'';
    }
    $sel = select(true, $table, 'COUNT(*) AS COUNTING', 'WHERE ' . $column . ' = ' . $value);
    if($sel) {
	$res = $sel['COUNTING'];
    }
    return $res;
}