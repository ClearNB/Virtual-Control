<?php

/**
 * 
 * エージェント情報に対する変更要求を受け取った際の処理をここで行います
 */
include_once __DIR__ . '/../session/session_chk.php';
include_once __DIR__ . '/mibfunction.php';
include_once __DIR__ . '/mibpage.php';

session_action_scripts();



ob_get_clean();
echo json_encode($r);
