<?php
include_once ('../scripts/general/sqldata.php');
include_once ('../scripts/session/session_chk.php');
include_once ('../scripts/general/loader.php');
include_once ('../scripts/general/former.php');

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

$fm_pg = new form_generator('fm_pg');
$fm_pg->SubTitle('OPTION - AGENT', 'エージェントを選択してください。', 'book');
$fm_pg->Check(1, 'rd_01', 'agt', '1', 'AGENT1', true);
$fm_pg->Check(1, 'rd_02', 'agt', '2', 'AGENT2',false);
$fm_pg->Check(1, 'rd_03', 'agt', '3', 'AGENT3',false);
$fm_pg->Button('bt_mk_ag', '作成', 'button','plus-square');
$fm_pg->Button('bt_ed_ag', '編集', 'button','edit');
$fm_pg->Button('bt_dl_ag', '削除', 'button','trash-alt');

$fm_ag_cr = new form_generator('fm_ag_cr');
$fm_ag_ed = new form_generator('fm_ag_ed');
$fm_ag_dl = new form_generator('fm_ag_dl');

?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'OPTION - AGENT', true) ?>
	<?php echo form_generator::ExportClass([$fm_pg, $fm_ag_cr, $fm_ag_ed, $fm_ag_dl]) ?>
    </head>
    <body>
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
	<?php echo $loader->load_Logo() ?>
	
	<?php echo $loader->Title('OPTION - AGENT', 'user') ?>
	<div id="data_output"></div>
	
	<?php echo $loader->footer() ?>
	<?php echo $loader->footerS(true) ?>
	
	<script type="text/javascript">
	    $(document).ready(function() {
		animation('data_output', 0, fm_pg);
	    });
            
             $(document).on('click', '#bt_mk_ag', function() { //追加ボタンクリック
		animation('data_output', 400, fm_ag_cr);
	    });
            
            $(document).on('click', '#bt_ed_ag', function() { //編集ボタンクリック
		animation('data_output', 400, fm_ag_ed);
	    });
            
            $(document).on('click', '#bt_dl_ag', function() { //削除ボタンクリック
		animation('data_output', 400, fm_ag_dl);
	    });
	</script>
    </body>
</html>