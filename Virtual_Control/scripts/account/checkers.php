<?php

/* [Account Information Checkers]
 * Form Validation Checking Tool.
 * Coded by Minokutonika 2020.
 */

include_once '../sqldata.php';
include_once '../common.php';
include_once '../sqldata.php';

function check_username($data) {
    if(strlen(mb_convert_encoding($data, 'SJIS', 'UTF-8')) > 30) {
        return '・ユーザ名が最大半角文字数30文字をを超えています。';
    } else {
        return null;
    }
}

function check_userid($data) {
    $result = select(true, "MKTK_USERS", "COUNT(*) AS USERCOUNT", "WHERE USERID = '$data'");
    if($result['USERCOUNT'] == 1) {
        return '・ユーザIDが重複しています';
    } else {
        return null;
    }
    
    if(!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{5,20}+\z/', $data)) {
        return '・ユーザID入力ルールに違反しています。';
    } else {
        return null;
    }
}

function check_password($data) {
    if(!preg_match('/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{10,15}+\z/', $data)) {
        return '・パスワードルールに則っていません。';
    } else {
        return null;
    }
}

function check_conf_password($data1, $data2) {
    if($data1 != $data2) {
        return '・確認用パスワードが間違っています。';
    } else {
        return '';
    }
}

function check_permission($data) {
    if(!isset($data)) {
        return '・権限を選択してください。';
    } else {
        return '';
    }
}