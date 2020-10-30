<?php

/* SNMPWalk Launcher for Virtual Control.
 * SNMPWalk is supported for SNMP version 2.0.
 * To launch, need module: PDO_SNMP.
 */
$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);

if ($method === 'GET') {
    //Variables
    $host = filter_input(INPUT_GET, 'host', FILTER_SANITIZE_STRING);
    $com = filter_input(INPUT_GET, 'community', FILTER_SANITIZE_STRING);
    $oid = filter_input(INPUT_GET, 'oid', FILTER_SANITIZE_STRING);

    //Functions
    $result = "Host: $host <br> Community: $com <br> OID: $oid<br>===================<br>";
    $snmpdata = snmp2_walk($host, $com, $oid);
    if (!is_array($snmpdata)) {
        $result = $result . "エラー: SNMPを取得できませんでした。";
    } else {
        $result = $result . implode('<br>', $snmpdata);
    }
    echo json_encode(['res' => $result]);
} else {
    $result = "本システムの直接の実行はできません。";
    echo json_encode(['res' => $result]);
}