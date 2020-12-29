#!/usr/bin/php
<?php

$fp = fopen('php://stdin', 'r');

if (!$fp) {
    http_response_code(301);
    header('Location: ../../403.php');
    exit;
}

include_once __DIR__ . '/../general/sqldata.php';

$set_file = loadfile('data/setting.json');

$dic = preg_replace('/\/$/', '', $set_file['logdirectory']);

$logfile =  $dic . "/trap_" . date("Ymd") . ".log";
$logtime = date("Y-m-d H:i:s");

file_put_contents($logfile, "===== start =====\n", FILE_APPEND);

file_put_contents($logfile, "TIME:" . $logtime . "\n", FILE_APPEND);

while (!feof($fp)) {
    $stdin = fgets($fp, 4096);
    file_put_contents($logfile, $stdin, FILE_APPEND);
}
fclose($fp);

file_put_contents($logfile, "\n", FILE_APPEND);
file_put_contents($logfile, "===== end =====\n", FILE_APPEND);
