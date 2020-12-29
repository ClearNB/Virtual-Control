<?php

/**
 * [FUNCTION] includeパッケージ指定
 * 
 * include_onceを行うファイルを指定します。
 * 
 * @package VirtualControl_scripts_general
 * 
 * @param int $page_type includeするファイルについてのタイプです。<br>
 * 【1】..共通インクルード（former, loader, sqldata, table）
 */
function includer($page_type): void {
    $packages = [];
    switch($page_type) {
	case 1:
    }
}