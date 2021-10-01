<?php

include_once __DIR__ . '/output.php';
include_once __DIR__ . '/../../data/setting.php';

/**
 * [クエリ実行]
 * クエリを実行します
 * @param string $query 手続き文字列を定義します
 * @return boolean|array
 */
function query($query) {
    $mysqli = get_db();
    $result = $mysqli->query($query);

    if (!$result) {
	print "クエリが失敗しました<br>" . "Errormessage: <br>" . $mysqli->error . "<br>";
	print "原因クエリ: " . $query . "<br>+------------------------+<br>";
	$result = false;
    }

    return $result;
}

/**
 * 
 * @return boolean|\mysqli
 */
function get_db() {
    try {
	$data = getSettinData();
	$mysqli = new mysqli($data['ADDRESS'], $data['USERID'], $data['PASS'], $data['DB'], $data['PORT']);
	$mysqli->set_charset('utf8');
	return $mysqli;
    } catch (Exception $ex) {
	print 'データベースへのアクセスに失敗:\n' . $ex;
	return false;
    }
}

function escape($str) {
    $mysqli = get_db();
    return $mysqli->real_escape_string($str);
}

/**
 * [GET] ランダム文字列生成
 * 0〜9, a〜z, A〜Zの間で8文字分のランダムな文字列を作成します
 * 
 * @param int $length 文字列の長さを指定します
 * @return string 文字数分のランダムな文字列を返します
 */
function random($length = 8) {
    return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

/**
 * [GET] テーブル存在確認
 * 現在のデータベース内で、作成されているテーブルがあるかどうかを調べ、ない場合は作成します
 * 
 * @param array	$tables	テーブルデータの順列配列です（[0]にテーブル名、[1]に定義が格納）
 * @param bool	$isText	テーブルの有無をテキストとして返すかどうか
 * @return bool	【判定条件】テーブルの有無にかかわらず全ての表の確認ができ、かつ無い表を全て作成できていること
 */
function setTableStatus($tables, $isText = false) {
    $r = true;
    $true_table = '【存在する表】</br>';
    $false_table = '【存在しない表】</br>';
    foreach ($tables as $var) {
	$data = create($var[0], $var[1]);
	$r = $r && $data;
    }
    if ($isText) {
	print $true_table . '<hr>' . $false_table;
    }
    return $r;
}

/**
 * [GET] テーブル作成（CREATE）
 * テーブルを作成します
 * 
 * @param string $table  テーブル名を指定します
 * @param string $column テーブルの定義を指定します（項目・制約等）
 * @return bool	【判定条件】テーブルの作成が完了したか
 */
function create($table, $column) {
    $query = "CREATE TABLE IF NOT EXISTS $table ($column)";
    $result = query($query);
    return $result;
}

/**
 * [GET] テーブルデータ挿入（INSERT）
 * 作成済みテーブルにデータを挿入します
 * 
 * @param string $table テーブル名を指定します
 * @param array	 $column 項目名（順序配列）を指定します
 * @param array $value 値（順序配列）を指定します
 * @return bool	【判定条件】テーブルにデータが挿入されたかどうか
 */
function insert($table, $column, $value) {

    //項目にコンマを区切って文字列化
    $column_text = implode($column, ', ');

    //タイプが文字列なら "" で囲む
    for ($i = 0; $i < sizeof($value); $i++) {
	if (gettype($value[$i]) === 'string') {
	    $value[$i] = "'" . $value[$i] . "'";
	}
    }

    //値にコンマを区切って文字列化
    $value_text = implode($value, ", ");

    $query = "INSERT INTO $table ($column_text) VALUES ($value_text)";
    $result = query($query);
    return $result;
}

function insert_onvalue($table, $column, $value, $setnum) {
    //項目にコンマを区切って文字列化
    $column_text = implode($column, ', ');

    for ($i = 0; $i < sizeof($value); $i++) {
	if ($setnum[$i] == 1) {
	    $value[$i] = "'" . $value[$i] . "'";
	}
    }

    //値にコンマを区切って文字列化
    $value_text = implode($value, ", ");

    $query = "INSERT INTO $table ($column_text) VALUES ($value_text)";
    $result = query($query);
    return $result;
}

function insert_select($table, $column, $select) {
    $column_text = implode($column, ', ');
    $query = "INSERT INTO $table ($column_text) $select";
    $result = query($query);
    return $result;
}

/**
 * [GET] テーブルレコードデータ変更（UPDATE）
 * テーブル内の対象レコードのデータを変更します
 * 
 * @param string $table テーブルを指定します
 * @param string $column 対象レコードを指定します
 * @param string $value 対象レコードの変更値を指定します
 * @param int $valuetype 変更値のタイプを確認します（0: 数値, 1: 文字列, 2: SQL文）
 * @param string $where 条件句（WHERE）を指定します
 * @return bool UPDATEが成功したらtrue、失敗したらfalseを返します
 */
function update($table, $column, $value, $valuetype, $where = '') {
    if ($valuetype == 1) {
	$value = "'" . $value . "'";
    }
    $query = "UPDATE $table SET $column = $value $where";
    $result = query($query);
    return $result;
}

/**
 * [SQL] 対象レコード削除
 * 対象レコードデータの削除または全てを削除します
 * @param string $table テーブル名を指定します
 * @param string $while WHILE文以降の入力項目を指定します
 * @return bool 削除できたらtrue、できなかったらfalseを返します
 */
function delete($table, $while = '') {
    $query = "DELETE FROM $table $while";
    $result = query($query);
    return $result;
}

/**
 * [SQL] 対象レコード参照（SELECT）
 * 対象レコードデータ出力を行います
 * 
 * @param bool $one_column 1行分のデータとして切り捨てるかどうかを指定します
 * @param string $table テーブルを指定します（FROM … ）
 * @param string $column データ項目を指定します（SELECT …）
 * @param string $other SELECT文に付加するその他の条件を指定します
 * @return array|bool 正しく取得できた場合は、配列化データ、それ以外はfalseを返します
 */
function select($one_column, $table, $column, $other = '') {
    $result = '';
    $query = 'SELECT ' . $column . ' FROM ' . $table . ' ' . $other;
    $res = query($query);
    if ($one_column && $res) {
	$result = $res->fetch_assoc();
    } else {
	$result = $res;
    }
    return $result;
}

/**
 * $tablesに与えられたテーブルの削除を行います。
 * @param array $tables	テーブル名の入った順序配列です
 * @return bool	全てのテーブルが削除されたかどうかを判定します
 */
function dropAllTable($tables) {
    $res = true;
    foreach ($tables as $var) {
	$query = 'DROP TABLE IF EXISTS ' . $var;
	$result = query($query);
	$res &= $result;
	if (!$res) {
	    break;
	}
    }
    return $res;
}

/**
 * SQLクエリで取得したデータを順列・副連想配列として作成します。
 * @param mixed $data SQLクエリで取得したデータを指定します
 * @return array データレコードは順列、データ内は各テーブル項目の連想配列となっています。
 */
function getArray($data): array {
    $result = [];
    while ($var = $data->fetch_assoc()) {
	array_push($result, $var);
    }
    return $result;
}
