<!DOCTYPE html>
<!--
<?php
include_once ('./scripts/general/sqldata.php');
include_once ('./scripts/general/loader.php');
include_once ('./scripts/general/former.php');
include ('./scripts/session/session_chk.php');
session_start();
if (!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}

$loader = new loader();
$userid = $_SESSION['gsc_userid'];
$getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERID = '$userid'");

//エージェント選択ページ
$fm_ag = new form_generator('fm_ag');
$fm_ag->Button('fm_ag_bk', 'ホームに戻る', 'button', 'fas fa-folder-open');
$fm_ag->SubTitle('エージェント一覧', 'エージェントを選択してください。', 'fas fa-server');
$fm_ag->Caption('fm_sl_01');
/*
  $fm_ag->openSelect('sl_ag_ag');
  $fm_ag->addOption(1, 'エージェント1');
  $fm_ag->closeSelect();
 */
$fm_ag->Button('fm_ag_ba', '選択', 'button', '');

//エージェント別警告画面
$fm_wn = new form_generator('fm_wn');
$fm_wn->Button('bt_wn_bk', 'エージェント選択画面に戻る', 'button', 'fas fa-');
$fm_wn->SubTitle('最新の警告', '日時: XXXX/XX/XX', 'fas fa-exclamation-circle');
$fm_wn->openList();
$fm_wn->addList('警告名');
$fm_wn->addList('警告ログ');
$fm_wn->closeList();
$fm_wn->ButtonLg('bt_wd_se', '警告の詳細', 'button', 'fas fa-info-circle');
$fm_wn->Title('警告レベル', 'fas fa-chart-line');
$fm_wn->Title('1', '');
$fm_wn->openList();
$fm_wn->addList('');
$fm_wn->closeList();



//警告詳細
$fm_wd = new form_generator('fm_wd');
$fm_wd->Button('bt_wd_bk', '戻る', 'button');
$fm_wd->Title('WARINGS','');
$fm_wd->SubTitle('ホスト：[エージェントIPアドレス]', '', '');
$fm_wd->Button('fm_wd_de', '警告の詳細へ', 'button');

//警告詳細
$fm_wd_se = new form_generator('fm_wd_se');
$fm_wd_se->Button('bt_wd_se_wa', '警告ウィザードに戻る', 'button');
$fm_wd_se->Button('bt_wd_se_bt', '警告詳細選択ボタン', 'button');
$fm_wd_se->Button('bt_wd_se_aa', '警告詳細選択ボタン', 'button');
?>
--> 

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'WARN') ?> <!-- ページタイトル -->
        <?php echo form_generator::ExportClass([$fm_ag, $fm_wn, $fm_wd_se, $fm_wd]) ?>
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

            $(document).on('click', '#fm_ag_bk', function () {
                animation_to_sites('data_output', 400, './');
            });

            $(document).on('click', '#fm_ag_ba', function () {
                animation('data_output', 400, fm_wd);
            });

            $(document).on('click', '#bt_wd_bk', function () {
                animation('data_output', 400, fm_ag);
            });

            $(document).on('click', '#fm_wd_de', function () {
                animation('data_output', 400, fm_wd_se);
            });

            $(document).on('click', '#bt_wd_se_wa', function () {
                animation_to_sites('data_output', 400, './');
            });

            $(document).on('click', '#fm_wd_bt', function () {
                animation('data_output', 400, fm_wd_se);
            });
        </script>
    </body>

</html>