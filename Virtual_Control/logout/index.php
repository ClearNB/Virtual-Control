<?php
include_once __DIR__ . '/../scripts/session/session_chk.php';
include_once __DIR__ . '/../scripts/general/sqldata.php';

session_start_once();
$userid = $_SESSION['gsc_userid'];
update("GSC_USERS", "LOGINSTATE", 0, "WHERE USERID='$userid'");
unset($_SESSION['gsc_userid']);
http_response_code(301);
header("Location: /");
