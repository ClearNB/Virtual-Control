<?php
include_once __DIR__ . '/../general/loader.php';
include_once __DIR__ . '/../session/session_chk.php';
include_once __DIR__ . '/../general/former.php';
include_once __DIR__ . '/warndata.php';

WarnData::load_data();
$data = WarnData::getArray();

$html = '';
foreach($data['VALUE'] as $g => $d) {
    $html .= '<details><summary>' . $g . '</summary>';
    foreach($d as $v) {
	$html .= '<details><summary>' . $v['AGENT'] . ' | ' . $v['MESSAGE'] . '</summary>';
	$html .= '<strong>以下の情報をもとにトラップしたことをお伝えします。</strong>';
	$html .= '<ul>';
	foreach($v as $i => $vl) { if($vl) { $html .= "<li>$i: $vl</li>"; } }
	$html .= '</ul>';
	$html .= '</details>';
    }
    $html .= '</details>';
}

echo $html;