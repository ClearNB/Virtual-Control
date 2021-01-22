<?php
include_once __DIR__ . '/../../scripts/general/loader.php';
include_once __DIR__ . '/../../scripts/session/session_chk.php';
include_once __DIR__ . '/../../scripts/general/sqldata.php';
include_once __DIR__ . '/../../scripts/general/former.php';

session_action_vcserver();
$getdata = session_get_userdata();

$loader = new loader();
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - AGENT') ?>
	<?php echo form_generator::ExportClass() ?>
    </head>
    <body class="text-monospace">
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
	<?php echo $loader->load_Logo() ?>

	<?php echo $loader->Title('OPTION - AGENT', 'server') ?>
        <div id="data_output"></div>

	<?php echo $loader->footer() ?>
	<?php echo $loader->footer_load('option_agent.js') ?>
    </body>
</html>