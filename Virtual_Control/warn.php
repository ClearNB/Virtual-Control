<?php
include_once __DIR__ . '/scripts/general/loader.php';
include_once __DIR__ . '/scripts/session/session_chk.php';
include_once __DIR__ . '/scripts/general/sqldata.php';
include_once __DIR__ . '/scripts/general/former.php';

session_action_user();
$getdata = session_get_userdata();

$loader = new loader();

//エージェント選択ページ
$fm_ag = new form_generator('fm_ag');
$fm_ag->Button('fm_ag_bk', 'ホームに戻る', 'button', 'home');
$fm_ag->SubTitle('エージェント一覧', 'エージェントを選択してください。', 'server');
$fm_ag->Caption('[AGENT_SELECT]');
$fm_ag->Button('fm_ag_se', '選択して警告を取得する', 'button', 'chevron-circle-right');

//警告ウィザード
$fm_wn = new form_generator('fm_wn');
$fm_wn->Button('bt_wn_bk', 'エージェント選択画面に戻る', 'button', 'chevron-circle-left');
$fm_wn->SubTitle('最新の警告', '取得日時: [DATE]<br>警告数: [COUNTS]', 'fas fa-exclamation-circle');
$fm_wn->CardDark('最新の警告', 'eye', '[警告ジャンル]', '[エージェントホスト]<br>[エージェントIPアドレス]');
$fm_wn->Button('bt_wn_dt', '警告の詳細', 'button', 'fas fa-info-circle');

//警告詳細
$fm_wd_dt = new form_generator('fm_wd_dt');
$fm_wd_dt->Button('bt_se_bk', '警告ウィザードに戻る', 'button');
$fm_wd_dt->Caption('[警告詳細テーブル]');
$fm_wd_dt->Button('bt_se_sl', '警告詳細選択ボタン', 'button');
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'WARN') ?> <!-- ページタイトル -->
        <?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?> <!-- ナビゲーション設定 -->

        <?php echo $loader->load_Logo() ?> <!-- ロゴ表示 -->

        <?php echo $loader->Title('警告画面', 'fas fa-exclamation-triangle') ?><!-- ページタイトル -->

        <div id="data_output"></div> <!-- 内容表示部分 -->

        <?php echo $loader->footer(); ?> <!-- フッター表示 -->

        <?php echo $loader->footerS() ?> <!-- フッター部分スクリプト読込 -->
        <script type="text/javascript">

            //ドキュメントが読み込まれたとき
            $(document).ready(function () {
                animation('data_output', 0, fm_ag);
            });

            $(document).on('click', '#fm_ag_bk, #fm_ag_se', function () {
		switch($(this).attr('id')) {
		    case "fm_ag_bk":
			animation_to_sites('data_output', 400, './');
			break;
		    case "fm_ag_se":
			animation('data_output', 400, fm_wn);
			break;
		}
            });
	    
            $(document).on('click', '#bt_wd_bk', function () {
                animation('data_output', 400, fm_ag);
            });

            $(document).on('click', '#fm_wd_de', function () {
                animation('data_output', 400, fm_wd_se);
            });

            $(document).on('click', '#bt_wd_se_wa', function () {
                animation('data_output', 400, fm_wn);
            });

            $(document).on('click', '#fm_wd_bt', function () {
                animation('data_output', 400, fm_wd_se);
            });
        </script>
    </body>

</html>