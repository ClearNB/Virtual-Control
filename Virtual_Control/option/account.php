<?php
include_once '../scripts/general/loader.php';
include_once '../scripts/session/session_chk.php';
include_once '../scripts/general/sqldata.php';
include_once '../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();

$fm_pg = new form_generator('fm_pg', '');
$fm_pg->SubTitle('OPTION - ACCOUNT', 'ここは、OPTION - ACCOUNT のページです。', 'book');

?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - ACCOUNT', true) ?>
	<?php echo form_generator::ExportClass() ?>
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