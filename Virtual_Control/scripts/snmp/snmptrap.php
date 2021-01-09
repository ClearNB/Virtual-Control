#!/usr/bin/php
<?php

$fp = fopen('php://stdin', 'r');

if (!$fp) {
    http_response_code(403);
    exit();
}

include_once __DIR__ . '/../general/output.php';
include_once __DIR__ . '/../general/sqldata.php';

$set_file = loadSetting();

$dic = preg_replace('/\/$/', '', $set_file['logdirectory']);

$logfile =  $dic . "/trap_" . date("Ymd") . ".log";
$logtime = date("Y-m-d H:i:s");

file_put_contents($logfile, "TIME: " . $logtime . "\n", FILE_APPEND);

$r_type = [
    [1, 'iso', '1'],
    [1, '1.3.6.1.2.1.1.3.0', 'SNMP_SYS_TIME:'],
    [1, '1.3.6.1.6.3.1.1.4.1.0', 'ALERT_OID:'],
    [0, '^(1.3.6.1.2.1.2.2.1.1.)([0-9]){1,}', 'INTERFACE:'],
    [1, '1.3.6.1.6.3.18.1.3.0', 'HOST:'],
    [1, '1.3.6.1.6.3.18.1.4.0', 'COMMUNITY:'],
    [1, '1.3.6.1.6.3.1.1.4.3.0', 'SNMP_ENTERPRISE:'],
    [1, '<UNKNOWN>', ''],
    [1, '"', ''],
    [1, '>', '']
];

while (!feof($fp)) {
    $stdin = fgets($fp, 4096);
    foreach($r_type as $r) {
	if($r[0] == 0) {
	    $stdin = preg_replace("/$r[1]/", $r[2], $stdin);
	} else {
	    $stdin = str_replace($r[1], $r[2], $stdin);
	}
    }
    file_put_contents($logfile, $stdin, FILE_APPEND);
}
file_put_contents($logfile, "+==+\n", FILE_APPEND);
fclose($fp);