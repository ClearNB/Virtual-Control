<?php

include_once '../scripts/former.php';
include_once '../scripts/loader.php';
include_once '../scripts/sqldata.php';
include_once '../scripts/dbconfig.php';
include_once '../scripts/common.php';
include_once '../scripts/session_chk.php';

if(!session_chk()) {
    http_response_code(301);
    header('Location: ../403.php');
    exit();
}
$id = $_SESSION['gsc_userid'];
$getdata = select(true, "GSC_USERS", "USERNAME, PERMISSION", "WHERE USERID = '$id'");
if(!$getdata && $getdata['PERMISSION'] != 0) {
    http_response_code(301);
    header('Location: ../403.php');
    exit();
}

$loader = new loader();

$fm_pg = new form_generator('fm_pg', '');
$fm_pg->SubTitle('OPTION - ACCOUNT', 'ここは、OPTION - ACCOUNT のページです。', 'book');

?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - ACCOUNT', true) ?>
	<?php echo form_generator::ExportClass([$fm_pg]) ?>
    </head>
    <body>
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
	<?php echo $loader->load_Logo() ?>
	
	<?php echo $loader->Title('OPTION - ACCOUNT', 'user') ?>
	<div id="data_output"></div>
	
	<?php echo $loader->footer() ?>
	<?php echo $loader->footerS(true) ?>
	
	<script type="text/javascript">
	    $(document).ready(function() {
		animation('data_output', 0, fm_pg);
	    });
	</script>
    </body>
</html>