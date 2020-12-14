<!DOCTYPE html>

<?php
include_once ('./scripts/general/sqldata.php');
include_once ('./scripts/loader.php');
include_once ('./scripts/former.php');
if(!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}
$loader = new loader();

$agents = ['agt01', 'agt02', 'agt03', 'agt04', 'agt05']; //エージェントデータはデータベースから取得する

$userid = $_SESSION['gsc_userid'];
$getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERID = '$userid'");
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'ANALY') ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?>
        
        <?php echo $loader->load_Logo() ?>
        
        <?PHP echo $loader->Title('ANALY', '') ?>
        
        <?php echo $loader->footer() ?>

        <!-- JavaScript dependencies -->
        <?php echo $loader->footerS() ?>
	
	<script type="text/javascript">
	
	</script>
    </body>
</html>