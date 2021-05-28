<?php
include_once __DIR__ . '/../scripts/general/session.php';
include_once __DIR__ . '/../scripts/general/sqldata.php';

session_start_once();
$userid = $_SESSION['gsc_userid'];
update("VC_USERS", "LOGINSTATE", 0, "WHERE USERID='$userid'");
unset($_SESSION['vc_userid']);
http_response_code(301);
header("Location: /");
