<?php

function session_start_once() {
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function session_per_chk() {
    if(session_chk()) {
	$id = $_SESSION['gsc_userid'];
	$sql = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', 'WHERE USERID = ' . $id);
	return $sql && ($sql['PERMISSION'] == 0);
    } else {
	return false;
    }
}

function session_chk() {
    session_start_once();
    return isset($_SESSION['gsc_userid']);
}