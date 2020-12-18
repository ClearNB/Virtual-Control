<!DOCTYPE html>

<!-- PHP HEADER MODULE -->
<?php
include_once ('./scripts/general/sqldata.php');
include_once ('./scripts/session/session_chk.php');
include_once ('./scripts/general/loader.php');
include_once ('./scripts/general/former.php');

session_start();
if (!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}

$loader = new loader();

$fm = new form_generator('fm');
$fm->Button('bt_fm_bk', 'ホームに戻る', 'button', 'chevron-circle-left');
$fm->SubTitle('SNMPWALKテスト', '情報の取得をテストします', 'fas fa-server');
$fm->Input('host', 'ホストアドレス', '接続先を指定します。', 'id-card-alt', true);
$fm->Input('community', 'コミュニティ', 'エージェントが属するコミュニティを設定します。', 'users', true);
$fm->Input('oid', 'OID', 'フィルタリングするOIDを設定します。', 'object-ungroup', false);
$fm->Button('fm_bt_sb', 'SNMPWALKを送信', 'submit', 'vials');

$id = $_SESSION['gsc_userid'];
$getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERID = '$id'");
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'TEST') ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <!-- HEADER NAVIGATION -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?>

        <!-- HEADER SECTION -->
        <?php echo $loader->load_logo() ?>

        <!-- TITLE SECTION -->
        <?php echo $loader->Title('SNMP TEST', 'fas fa-server') ?>

        <!-- CONTENT SECTION -->
        <div id="data_output"></div>

        <!-- FOOTER -->
        <?php echo $loader->footer() ?>

        <!-- FOOTER SCRIPTS -->
        <?php echo $loader->footerS(); ?>
        <script type="text/javascript">
            $(document).ready(function() {
                animation('data_output', 0, fm);
            });
        </script>
    </body>

</html>
