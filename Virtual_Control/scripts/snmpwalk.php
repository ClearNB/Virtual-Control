<?php

/* SNMPWalk Launcher for Virtual Control.
 * SNMPWalk is supported for SNMP version 2.0.
 * To launch, need module: PDO_SNMP.
 */

$requestmg = filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH');

$request = isset($requestmg)
        ? strtolower($requestmg) : '';
if($request !== 'xmlhttprequest') {
    http_response_code(403);
    header("Location: ../403.php");
    exit;
}

$method = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
if ($method === 'POST') {
    //Variables
    $host = filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING);
    $com = filter_input(INPUT_POST, 'community', FILTER_SANITIZE_STRING);
    $oid = filter_input(INPUT_POST, 'oid', FILTER_SANITIZE_STRING);

    //Functions
    $code = 0;
    $result = "Host: $host <br> Community: $com <br> OID: $oid<br>===================<br>";
    $snmpdata = snmp2_walk($host, $com, $oid);
    if (!is_array($snmpdata)) {
        $code = 1;
    } else {
        $result = $result . implode('<br>', $snmpdata);
    }
    echo json_encode(['code'=>$code, 'res' => $result]);
}