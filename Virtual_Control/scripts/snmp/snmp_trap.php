#!/usr/bin/php
<?php

include_once __DIR__ . '/../general/sqldata.php';
include_once __DIR__ . '/../mib/mib_data.php';
include_once __DIR__ . '/../agent/agent_data.php';

$fp = fopen('php://stdin', 'r');

if (!$fp) {
    http_response_code(403);
    exit();
}

$logtime = date("Y-m-d H:i:s");
$r_type = [
    ['1.3.6.1.2.1.1.3.0', 'SYSTIME'],
    ['1.3.6.1.6.3.1.1.4.1.0', 'TRAPOID'],
    ['(1.3.6.1.2.1.2.2.1.1.)([0-9]){1,}', 'INTERFACEID'],
    ['1.3.6.1.6.3.18.1.3.0', 'HOST'],
    ['1.3.6.1.6.3.18.1.4.0', 'COMMUNITY'],
    ['1.3.6.1.6.3.1.1.4.3.0', 'SYSTEMOID']
];
$res = ['OTHER' => ''];

while (!feof($fp)) {
    $stdin = str_replace(hex2bin('0a'), '', str_replace('>', '', str_replace('"', '', str_replace('<UNKNOWN>', '', str_replace('iso', '1', fgets($fp, 4096))))));
    $r_flag = false;
    if (preg_match('/.*(127.0.0.1).*/', $stdin)) {
	$res['HOST'] = 'localhost';
    }
    if ($stdin != '' && $stdin != '\n' && $stdin != '\r') {
	foreach ($r_type as $r) {
	    $type = $r[0];
	    if (preg_match("/^$type/", $stdin)) {
		print $stdin . ' = ' . bin2hex($stdin);
		$res[$r[1]] = str_replace('\r', '', str_replace('\n', '', preg_replace('/ $/', '', preg_replace("/^$type /", '', $stdin))));
		$r_flag = true;
		break;
	    }
	}
	if (!$r_flag) {
	    $res['OTHER'] .= $stdin . '<br>';
	}
    }
}

$trapid = intval((isset($res['TRAPOID'])) ? searchMIBId($res['TRAPOID'], 1) : 0);
$sysid = intval((isset($res['SYSTEMOID'])) ? searchMIBId($res['SYSTEMOID'], 1) : 0);
$interfaceid = intval((isset($res['INTERFACEID'])) ? $res['INTERFACEID'] : 0);
$host = isset($res['HOST']) ? $res['HOST'] : '';
$com = isset($res['COMMUNITY']) ? $res['COMMUNITY'] : '';
$agentid = intval(searchAgentId($host, $com));

$other = $res['OTHER'];

if($trapid == 0 && isset($res['TRAPOID'])) {
    $other .= 'トラップOID: ' . $res['TRAPOID'] . '<br>';
} else if($trapid == 0) {
    $other .= 'トラップOID: 不明<br>';
}

if($sysid == 0 && isset($res['SYSTEMOID'])) {
    $other .= 'システムOID: ' . $res['SYSTEMOID'] . '<br>';
} else if($sysid == 0) {
    $other .= 'システムOID: 不明<br>';
}

if($agentid == 0 && ($host != '' || $com != '')) {
    $other .= '宛先: ' . $host . ' (' . $com . ')<br>';
} else if($agentid == 0) {
    $other .= '宛先: 不明<br>';
}
$other_r = preg_replace('/^\<br\>/', '', preg_replace('/\<br\>$/', '', $other));

$systime = preg_replace('/.\d{1,2}$/', '', $res['SYSTIME']);
if (preg_match('/\d{1,2}:\d{1,2}:\d{1,2}/', $systime)) {
    $systime = preg_replace('/:/', ' ', $systime, 1);
}
$insert = insert('VC_TRAP', ['AGENTID', 'TRAPMIBID', 'SYSTEMMIBID', 'INTERFACEID', 'INFO', 'SYSTIME', 'TRAPTIME'], [$agentid, $trapid, $sysid, $interfaceid, $other_r, $systime, $logtime]);
