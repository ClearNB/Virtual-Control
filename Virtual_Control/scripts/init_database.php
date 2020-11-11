<?php

include_once '../scripts/sqldata.php';

$gsc_mib_sub_if = [
    "TABLENAME" => 'GSC_MIB_SUB',
    "MIBID" => 2,
    "HEADERS" => [
        'OBJECTID', 'MIBINDEX', 'DESCR', 'JAPTLANS', 'ICON'
    ],
    "VALUES" => [
        ['1.3.6.1.2.1.2.1.0', 2, 'ifNumber', 'インターフェースの総数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.2.2.1.1.* ', 2, 'ifIndex', 'インターフェースのインデックス値', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.2.2.1.2.* ', 2, 'ifDescr', 'インターフェース名', 'fas fa-pencil-alt'],
        ['1.3.6.1.2.1.2.2.1.3.* ', 2, 'ifType', 'インタフェース種別', 'file-text'],
        ['1.3.6.1.2.1.2.2.1.4.* ', 2, 'ifMtu', 'MTU値', 'fas fa-signal'],
        ['1.3.6.1.2.1.2.2.1.5.* ', 2, 'ifSpeed', '帯域', 'fas fa-expand-arrows-alt'],
        ['1.3.6.1.2.1.2.2.1.6.* ', 2, 'ifPhysAddress', 'MACアドレス', 'fas fa-address-card'],
        ['1.3.6.1.2.1.2.2.1.7.* ', 2, 'ifAdminStatus', '管理上の状態', 'fas fa-tasks'],
        ['1.3.6.1.2.1.2.2.1.8.* ', 2, 'ifOperStatus', '運用上の状態', 'fab fa-opera'],
        ['1.3.6.1.2.1.2.2.1.9.* ', 2, 'ifLastChange', '最終更新日時', 'fas-clock'],
        ['1.3.6.1.2.1.2.2.1.10.*', 2, ' ifInOctets', '入力したオクテットのパケット数の下位32ビット', 'fas fa-signal'],
        ['1.3.6.1.2.1.2.2.1.11.*', 2, ' ifInUcastPkts', '入力したユニキャストパケット数の下位32ビット', 'fas fa-signal'],
        ['1.3.6.1.2.1.2.2.1.12.*', 2, ' ifInNUcastPkts', '受信したユニキャスト以外のパケット総数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.2.2.1.13.*', 2, ' ifInDiscards', '入力時の破棄回数の下位32ビット', 'fas fa-indent'],
        ['1.3.6.1.2.1.2.2.1.14.*', 2, ' ifInErrors', '入力エラーの回数の下位32ビット', 'fas fa-times'],
        ['1.3.6.1.2.1.2.2.1.15.*', 2, ' ifInUnknownProtos', 'プロトコルが不明なパケットの入力数の下位32ビット', 'fas fa-signal'],
        ['1.3.6.1.2.1.2.2.1.16.*', 2, ' ifOutOctets', '出力したオクテット数の下位32ビット', 'fas fa-signal'],
        ['1.3.6.1.2.1.2.2.1.17.*', 2, ' ifOutUcastPkts', '出力したユニキャストパケット数の下位32ビット', 'fas fa-signal'],
        ['1.3.6.1.2.1.2.2.1.18.*', 2, ' ifOutNUcastPkts', '送信したユニキャスト以外のパケット総数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.2.2.1.19.*', 2, ' ifOutDiscards', '送信時に破棄したパケット総数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.2.2.1.20.*', 2, ' ifOutErrors', '出力エラーの回数の下位32ビット', 'fas fa-signal'],
        ['1.3.6.1.2.1.2.2.1.21.*', 2, ' ifOutQLen', ' バッファ出力待ちパケット総数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.2.2.1.22.*', 2, ' ifSpecific', '追加情報を保存しているオブジェクトのOID', 'battery-empty'],
        ['1.3.6.1.2.1.31.1.1.1.1.*', 2, 'ifName', 'インタフェース名', 'fas fa-file-signature'],
        ['1.3.6.1.2.1.31.1.1.1.2.*', 2, 'ifInMulticastPkts', '入力したマルチキャスト数の下位32bit', 'fas fa-signal'],
        ['1.3.6.1.2.1.31.1.1.1.3.*', 2, 'ifInBroadcastPkts', '受信したブロードキャストのパケット総数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.4.*', 2, 'ifOutMulticastPkts', '出力したマルチキャストパケット数の下位32bit', 'fas fa-signal'],
        ['1.3.6.1.2.1.31.1.1.1.5.*', 2, 'ifOutBroadcastPkts', ' 送信したブロードキャストのパケット総数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.6.*', 2, 'ifHCInOctets', '入力したオクテット数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.7.*', 2, 'ifHCInUcastPkts', '入力したユニキャストパケット数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.8.*', 2, 'ifHCInMulticastPkts', '入力したマルチキャスト数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.9.*', 2, 'ifHCInBroadcastPkts', '受信したブロードキャストのパケット総数(64 bit)', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.10.*', 2, 'ifHCOutOctets', '出力したオクテット数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.11.*', 2, 'ifHCOutUcastPkts', '出力したユニキャストパケット数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.12.*', 2, 'ifHCOutMulticastPkts', '出力したマルチキャストパケット数', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.13.*', 2, 'ifHCOutBroadcastPkts', '送信したブロードキャストのパケット総数(64 bit)', 'fas fa-stopwatch-20'],
        ['1.3.6.1.2.1.31.1.1.1.14.*', 2, 'ifLinkUpDownTrapEnable', 'リンク状態変化トラップ送信の可否', 'fas fa-link'],
        ['1.3.6.1.2.1.31.1.1.1.15.*', 2, 'ifHighSpeed', '帯域', 'fas fa-expand-arrows-alt'],
        ['1.3.6.1.2.1.31.1.1.1.16.*', 2, 'ifPromiscuousMode', 'プロミスキャスモード', 'fab fa-linode'],
        ['1.3.6.1.2.1.31.1.1.1.17.*', 2, 'ifConnectorPresent', 'コネクターの有無', 'fas fa-plug'],
        ['1.3.6.1.2.1.31.1.1.1.19.*', 2, 'ifCounterDiscontinuityTime', 'カウンタ情報が非連続時の最終更新日時', 'fas-clock'],
        ['1.3.6.1.2.1.31.1.5.*', 2, 'ifCounterDiscontinuityTime', '起動後の最終更新日時', 'fas-clock']
    ]
];

data_input($gsc_mib_sub_if);

function data_input($array) {
    $id = $array['MIBID'];
    $name = $array['TABLENAME'];
    $headers = $array['HEADERS'];
    $values = $array['VALUES'];
    delete('GSC_MIB_' . $name, 'WHRERE MIBINDEX = ' . $id);
    foreach ($values as $v) {
        insert($name, $headers, $v);
    }
}
