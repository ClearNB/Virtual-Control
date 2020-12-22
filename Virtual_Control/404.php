<?php
include_once './scripts/general/loader.php';
include_once './scripts/session/session_chk.php';
include_once './scripts/general/sqldata.php';
include_once './scripts/general/former.php';

$loader = new loader();

$per = 2;

if(session_chk() == 0) {
    $getdata = session_get_userdata();
    $per = $getdata['PERMISSION'];
}

$fm_pg = new form_generator('fm_pg', '');
$fm_pg->SubTitle('指定されたページはありません。', 'ホームにお戻りください。', 'exclamation-triangle');
$fm_pg->Button('bt_pg_bk', 'ホームに戻る', 'button', 'home');
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'INDEX', true) ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <?php echo $loader->navigation($per) ?>
	<?php echo $loader->load_Logo() ?>
	
	<?php echo $loader->Title('404 (NOT FOUND)', 'exclamation-triangle') ?>
        
        <div id="data_output"></div>

        <?php echo $loader->footer() ?>
        <?php echo $loader->footerS(true) ?>
	
	<script type="text/javascript">
	    $(document).ready(function() {
		animation('data_output', 0, fm_pg);
	    });
	    
	    $(document).on('click', '#bt_pg_bk', function() {
		animation_to_sites('data_output', 400, './');
	    });
	</script>
    </body>

</html>