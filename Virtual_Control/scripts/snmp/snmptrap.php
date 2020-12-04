#!/usr/bin/php
<?php

$logfile = "trap_" . date("Ymd") . ".log";
$logtime = date("Y-m-d H:i:s ");

file_put_contents($logfile, $logtime . "===== start =====\n", FILE_APPEND);

$fp = fopen('php://stdin', 'r');

if (!$fp) {
    file_put_contents($logfile, $logtime . "ERROR : fopen error\n", FILE_APPEND);
    exit;
}

while (!feof($fp)) {
    $stdin = fgets($fp, 4096);
    file_put_contents($logfile, $logtime . "DEBUG : " . $stdin, FILE_APPEND);
}
fclose($fp);

file_put_contents($logfile, $logtime . "\n", FILE_APPEND);
file_put_contents($logfile, $logtime . "===== end =====\n", FILE_APPEND);
