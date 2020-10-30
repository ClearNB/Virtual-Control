<?php

/** [Header]
 * Manage & Generate Header HTML Code
 * @author Project GSC
 */

function displayHeader($page) {
    $pagename = str_replace(".php", "", str_replace("./", "", $page));
    switch($pagename) {
        case '403':
        case '404':
        case 'info':
            break;
        case 'analy':
        case 'dash':
        case 'info':
        case 'option':
        case 'test':
        case 'warn':
            break;
        case 'index':
        case 'login':
            break;
    }
}