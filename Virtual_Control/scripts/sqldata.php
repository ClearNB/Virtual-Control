<?php

/* SQLData
 * desc: Generate SQL and execute it.
 * Dependent by common.php
 * [Function List]
 * + getServerStatus()
 * + getAllUserData()
 */
include_once 'common.php';
include_once 'dbconfig.php';

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

function update($table, $column, $value, $while = '') {
    $query = "UPDATE $table SET $column = $value $while";
    $result = query($query);
    return $result;
}

function delete($table, $while = '') {
    $query = "DELETE FROM $table $while";
    $result = query($query);
    return $result;
}

function select($one_column, $table, $column, $other = '') {
    $query = "
            SELECT $column
            FROM   $table
            $other
    ";
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
