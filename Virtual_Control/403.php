<!DOCTYPE html>
<?php
include_once ('./scripts/loader.php');
$loader = new loader();

include_once ('./scripts/sqldata.php');
include_once ('./scripts/common.php');
include_once ('./scripts/dbconfig.php');
include_once ('./scripts/former.php');

$fm = new form_generator('form_failed', '', 2);
$fm->SubTitle('アクセス禁止', 'あなたはこのページを表示・操作できません。', 'fas fa-times-circle');
$fm->Button('bttn_fm_back', 'ホームへ戻る', 'button', 'fas fa-home');

include_once ('./scripts/session_chk.php');
session_start();
if(isset($_SESSION['gsc_userindex'])) {
    $getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERINDEX = $index");
} else {
    $getdata = ["PERMISSION"=>2];
}
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'INDEX') ?>
        <script type="text/javascript">
            var fm = '<?php echo $fm->Export() ?>';
        </script>
    </head>

    <body class="text-monospace">
        <!-- Navbar & Logo -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?>

        <?php echo $loader->load_Logo() ?>
        
        <?php echo $loader->Title('403 (Forbidden)', 'fas fa-exclamation') ?> <!-- ページタイトル -->
        
        <div id="data_output"></div>
        
        <!-- Footer -->
        <?php echo $loader->footer() ?>
        
        <?php echo $loader->footerS() ?>
        
        <script type="text/javascript">
            $(document).ready(function() {
                animation('data_output', 0, fm);
            });
        </script>
    </body>

</html>