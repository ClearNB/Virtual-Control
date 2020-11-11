<?php

function session_start_once() {
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function session_chk() {
    session_start_once();
    return isset($_SESSION['gsc_userindex']);
}

function fails_check() {
    session_start_once();
    return (isset($_SESSION['gsc_login_fails']) && $_SESSION['gsc_login_fails'] > 3);
}

function failed_check() {
    session_start_once();
    return isset($_SESSION['gsc_failed']);
}

function update_fails() {
    session_start_once();
    if (!isset($_SESSION['gsc_login_fails'])) {
        $_SESSION['gsc_login_fails'] = 0;
    } else {
        $_SESSION['gsc_login_fails'] += 1;
    }
}

function failed() {
    unset($_SESSION['gsc_login_fails']);
    session_write_close();
    ini_set('session.gc_divisor', 1);
    ini_set('session.gc_maxlifetime', 600);
    session_start();
    $_SESSION['mktk_failed'] = 'failed';
}