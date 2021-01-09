<?php
include_once __DIR__ . '/scripts/general/loader.php';
include_once __DIR__ . '/scripts/session/session_chk.php';
include_once __DIR__ . '/scripts/general/sqldata.php';
include_once __DIR__ . '/scripts/general/former.php';

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
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', $response_caption[$response_code][0], false, true) ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
	<?php echo $loader->navigation($per) ?>

	<?php echo $loader->load_Logo() ?>

	<?php echo $loader->Title($response_caption[$response_code][0], 'exclamation-triangle') ?>

        <div id="data_output"></div>

	<?php echo $loader->footer() ?>

	<?php echo $loader->footerS(false, true) ?>

        <script type="text/javascript">
	    $(document).ready(function () {
		animation('data_output', 0, fm_pg);
	    });

	    $(document).on('click', '#bt_pg_bk', function () {
		animation_to_sites('data_output', 400, '/index.php');
	    });
        </script>
    </body>

</html>