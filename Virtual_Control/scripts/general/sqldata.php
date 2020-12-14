<?php

static $mysqli;

function query($query) {
    $mysqli = get_db();
    $result = $mysqli -> query($query);

    if (!$result) {
        print "クエリが失敗しました" . "Errormessage: <br>" . $mysqli -> error . "<br>";
	print "原因クエリ: " . $query . "<br>";
        return false;
    }

    return $result;
}

function get_db() {
    try {
        $data = loadfile("data/database.json");

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
    if($ip == '::1') {
        $ip = '127.0.0.1';
    }
    $json = file_get_contents("http://" . $ip . "/" . $filename);
    $arr = json_decode($json, true);
    return $arr;
}

function random($length = 8) {
    return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

//テーブルの状態を把握し、存在しない場合は作成を試みます。
function setTableStatus($tables, $isText = false) {
    $r = true;
    $true_table = '【存在する表】</br>';
    $false_table = '【存在しない表】</br>';
    foreach ($tables as $var) {
        $query = "SELECT * FROM $var[0]";
        $data = query($query);
        //テーブルがある場合・ない場合
        if ($data) {
            $true_table = $true_table . $var[0] . '<br>';
        } else {
            $false_table = $true_table . $var[0] . '<br>';
            $data = createTable($var[0], $var[1]);
        }
        $r = $r && $data;
    }
    if ($isText) {
        print $true_table . '<hr>' . $false_table;
    }
    return $r;
}

function createTable($table, $column) {
    $query = "CREATE TABLE $table ($column)";
    $result = query($query);
    return $result;
}

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

function reset_auto_increment($table, $index = 1) {
    $query = "ALTER TABLE $table AUTO_INCREMENT = $index";
    $result = query($query);
    return $result;
}