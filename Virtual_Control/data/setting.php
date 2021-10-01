<?php

include_once __DIR__ . '/../scripts/general/session.php';

/**
 * [GET] セッティング情報取得
 * 管理者によって設定されたセッティング情報を取得します
 * @return array [ADDRESS, PORT, USERID, PASS, DB] の連想配列を返します
 */
function getSettinData() {
    return ['ADDRESS' => 'localhost', 'PORT' => 3306, 'USERID' => 'vchost', 'PASS' => 'masterkey', 'DB' => 'vctest'];
}