<?php //Virtual Control : ERROR (VC-12) by Project GSC
include_once __DIR__ . '/scripts/general/loader.php';
include_once __DIR__ . '/scripts/general/former.php';
include_once __DIR__ . '/scripts/session/session_chk.php';

$loader = new loader();

$per = 2;
if (session_chk() == 0) {
    $getdata = session_get_userdata();
    $per = $getdata['PERMISSION'];
}

$response_caption = [
    200 => ['403 (FORBIDDEN)', 'アクセス禁止', '申し訳ございませんが、あなたはこのページを表示・操作できません。<br>上部ナビゲーションからリンクするか、下の「ホームに戻る」ボタンを押してください。'],
    403 => ['403 (FORBIDDEN)', 'アクセス禁止', '申し訳ございませんが、あなたはこのページを表示・操作できません。<br>上部ナビゲーションからリンクするか、下の「ホームに戻る」ボタンを押してください。'],
    404 => ['404 (NOT FOUND)', 'アクセス到達不可', '申し訳ございませんが、指定されたページは存在しません。<br>上部ナビゲーションからリンクするか、下の「ホームに戻る」ボタンを押してください。'],
    500 => ['500 (INTERNAL SERVER ERROR)', '内部処理エラー', '申し訳ございませんが、指定された処理を行っている途中でエラーが発生しました。<br>上部ナビゲーションからリンクするか、下の「ホームに戻る」ボタンを押してください。']
];

$response_code = http_response_code();

$fm_pg = new form_generator('fm_pg');
$fm_pg->SubTitle($response_caption[$response_code][1], $response_caption[$response_code][2], 'times-circle');
$fm_pg->Button('bt_pg_bk', 'ホームへ戻る', 'button', 'home');

echo $loader->getPage('Virtual Control', $response_caption[$response_code][0], 'exclamation-triangle', $per, 'error.js');