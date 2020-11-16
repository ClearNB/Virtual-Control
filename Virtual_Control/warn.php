<!DOCTYPE html>

<!--
<?php
include ('./scripts/session_chk.php');
session_start();
if(!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}

include_once ('./scripts/sqldata.php');
include_once ('./scripts/common.php');
include_once ('./scripts/dbconfig.php');
include_once ('./scripts/loader.php');
include_once ('./scripts/former.php');
$loader = new loader();
$index = $_SESSION['gsc_userindex'];
$getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERINDEX = $index");

//テストページ
$fm_ts = new form_generator('fm_ts', '', 2);
$fm_ts->SubTitle('テストページ', 'これはテストページです。', 'fas fa-vials');
$fm_ts->openRow();
$fm_ts->Buttonx3('fm_ts_open', '', 'button', 'fas fa-folder-open');
$fm_ts->Buttonx3('fm_ts_edit', '', 'button', 'fas fa-folder-open');
$fm_ts->Buttonx3('fm_ts_close', '', 'button', 'fas fa-folder-open');
$fm_ts->closeDiv();
?>
-->

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'WARN') ?> <!-- ページタイトル -->
        <script type="text/javascript">
            var fm_ts = '<?php echo $fm_ts->Export() ?>';
        </script>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?> <!-- ナビゲーション設定 -->
        
        <?php echo $loader->load_Logo() ?> <!-- ロゴ表示 -->
        
        <?php echo $loader->Title('警告画面', 'exclamation') ?> <!-- ページタイトル -->
        
        <div id="data_output"></div> <!-- 内容表示部分 -->
        
        <?php echo $loader->footer(); ?> <!-- フッター表示 -->

        <?php echo $loader->footerS() ?> <!-- フッター部分スクリプト読込 -->
        <script type="text/javascript">
            //animation('data_output', [前のページを閉じる時間], 遷移対象オブジェクト)
            
            /* [ボタン押下時のイベント]
             * $(document).on('click', '#[ボタンのID]', function() {
             *     ...
             * });
             */
            
            //ドキュメントが読み込まれたとき
            $(document).ready(function() {
                animation('data_output', 0, fm_ts);
            });
        </script>
    </body>

</html>