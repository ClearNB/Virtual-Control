<?php

/**
 * @var $mysqli MySQLiデータを取得します
 */
static $mysqli;

/**
 * クエリを実行します
 * @param string $query 手続き文字列を定義します
 * @return boolean|array
 */
function query($query) {
    $mysqli = get_db();
    $result = $mysqli->query($query);

    if (!$result) {
	print "クエリが失敗しました" . "Errormessage: <br>" . $mysqli->error . "<br>";
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
	$data = loadfile("data/setting.json");

	$HOST = $data['host'];
	$USERNAME = $data['user'];
	$PASSWORD = $data['password'];
	$DBNAME = $data['database'];
	$PORT = $data['port'];

	/* [追加プログラム]
	 * ・上記4つのデータにいずれかの不備があった場合、初期設定に移行する
	 */

	$mysqli = new mysqli($HOST, $USERNAME, $PASSWORD, $DBNAME, $PORT);
	$mysqli->set_charset("utf8");
	return $mysqli;
    } catch (Exception $ex) {
	print "データベースへのアクセスに失敗:\n$ex";
	return false;
    }
}

function escape($str) {
    $mysqli = get_db();
    return $mysqli->real_escape_string($str);
}

function loadfile($filename) {
    $ip = filter_input(INPUT_SERVER, 'SERVER_ADDR', FILTER_SANITIZE_STRING);
    if (!$ip || $ip == '::1') {
	$ip = '127.0.0.1';
    }
    $json = file_get_contents("http://" . $ip . "/" . $filename);
    $arr = json_decode($json, true);
    return $arr;
}

function random($length = 8) {
    return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

/**
 * 現在のデータベース内で、作成されているテーブルがあるかどうかを調べ、ない場合は作成します。
 * @param array	$tables	テーブルデータの順列配列です。[0]にテーブル名、[1]に定義が格納されています。
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
 * テーブルを作成します。
 * @param string    $table  テーブル名を指定します
 * @param string    $column テーブルの定義を指定します（項目・制約等）
 * @return bool	【判定条件】テーブルの作成が完了したか
 */
function create($table, $column) {
    $query = "CREATE TABLE IF NOT EXISTS $table ($column)";
    $result = query($query);
    return $result;
}

/**
 * すでにあるテーブルにデータを挿入します。
 * @param string $table	    
 * @param array	 $column    
 * @param string $value	    
 * @return bool	【判定条件】テーブルにデータが挿入されたかどうか
 */
function insert($table, $column, $value) {
    $column_text = implode($column, ', ');
    for ($i = 0; $i < sizeof($value); $i++) {
	if (gettype($value[$i]) === 'string') {
	    $value[$i] = "'" . $value[$i] . "'";
	}
    }
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

function update($table, $column, $value, $where = '') {
    $text = $value;
    if (gettype($text) === 'string') {
	$text = "'" . $text . "'";
    }
    $query = "UPDATE $table SET $column = $text $where";
    $result = query($query);
    return $result;
}

function delete($table, $while = '') {
    $query = "DELETE FROM $table $while";
    $result = query($query);
    return $result;
}

function select($one_column, $table, $column, $other = '') {
    $query = "SELECT $column FROM $table $other";
    $result = query($query);
    if ($one_column) {
	if ($result) {
	    $result = $result->fetch_assoc();
	}
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
	$query = "DROP TABLE IF EXISTS " . $var[0];
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
