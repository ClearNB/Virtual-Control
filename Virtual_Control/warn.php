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

//エージェント選択ページ
$fm_ag = new form_generator('fm_ag');
$fm_ag->Button('fm_ag_bk', 'ホームに戻る', 'button', 'fas fa-folder-open');
$fm_ag->SubTitle('エージェント一覧', 'エージェントを選択してください。', 'fas fa-server');
$fm_ag->Button('fm_ag_open', '', 'button', 'fas fa-folder-open');
$fm_ag->Button('fm_ag_edit', '', 'button', 'fas fa-folder-open');
$fm_ag->Button('fm_ag_close', '', 'button', 'fas fa-folder-open');

//エージェント別警告画面
$fm_wn = new form_generator('fm_wn');
$fm_wn->Button('bt_wn_bk', 'エージェント選択画面に戻る', 'button', 'fas fa-');
$fm_wn->SubTitle('最新の警告', '日時: XXXX/XX/XX', 'fas fa-exclamation-circle');
$fm_wn->openList();
$fm_wn->addList('警告名');
$fm_wn->addList('警告ログ');
$fm_wn->closeList();
$fm_wn->ButtonLg('bt_wd_se', '警告の詳細','button','fas fa-info-circle');
$fm_wn->Title('警告レベル','fas fa-chart-line');
$fm_wn->Title('1');
$fm_wn->openList();
$fm_wn->addList('');
$fm_wn->closeList();

//警告詳細選択
$fm_wd_se = new form_generator('fm_wd_se');
$fm_wd_se->Button('bt_wd_se_wa', '警告ウィザードへ戻る', 'button');
$fm_wd_se->Button('bt_wd_se_bt', '警告詳細選択ボタン', 'button');

//警告詳細
$fm_wd = new form_generator('fm_wd');
$fm_wd->Button('bt_wd_bk', '警告詳細選択画面へ戻る', 'button');
$fm_wd->Caption('');
?>
--> 

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'WARN') ?> <!-- ページタイトル -->
        <script type="text/javascript">
            var fm_ag = '<?php echo $fm_ag->Export() ?>';
            var fm_wn = '<?php echo $fm_wn->Export() ?>';
            var fm_wd_se = '<?php echo $fm_wd_se->Export() ?>';
            var fm_wd = '<?php echo $fm_wd->Export() ?>';
        </script>
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
            $(document).ready(function() {
                animation('data_output', 0, fm_ag);
            });
            
            $(document).on('click','#fm_ag_back',function(){
                animation_to_sites('data_output',400,'./');
            });
            $(document).on('click','#bt_wn_bk',function(){
                animation('data_output',400,fm_ag);
            });
            $(document).on('click','#fm_wn_wd',function(){
                animation('data_output',400,fm_wd_se);
            });
            $(document).on('click','#bk_wa',function(){
                animation_to_sites('data_output',400,'./');
            });
            $(document).on('click','#wd_bt',function(){
                animation('data_output',400,fm_wd);
            });
            $(document).on('click','#fm_wd_bt',function(){
                animation('data_output',400,fm_wd_se);
            });
        </script>
    </body>

</html>