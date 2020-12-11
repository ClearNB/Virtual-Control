<?php

function session_start_once() {
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function session_chk() {
    session_start_once();
    return isset($_SESSION['gsc_userid']);
}