<?php

static $mysqli;

function get_db() {
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

    if ($mysqli -> connect_error){
        /* [追加プログラム]
         * ・MySQLでコネクトはできたがデータベースに接続できない場合 → データベースの作成を行う処理を行う
         */
        
        print $mysqli -> connect_error();
        exit;
    } else {
        $mysqli -> set_charset("utf8");
    }

    return $mysqli;
}


function escape($str) {
    $mysqli = get_db();
    return $mysqli -> real_escape_string($str);
}

