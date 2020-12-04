<?php

include_once '../scripts/former.php';
include_once '../scripts/loader.php';
include_once '../scripts/sqldata.php';
include_once '../scripts/dbconfig.php';
include_once '../scripts/common.php';

$loader = new loader();

?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - ACCOUNT', true) ?>
	<?php echo form_generator::ExportClass([]) ?>
    </head>
    <body>
	<?php echo $loader->navigation(0) ?>
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