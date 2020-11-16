<!DOCTYPE html>

<!-- PHP HEADER MODULE -->
<?php
include ('./scripts/session_chk.php');
session_start();
if (!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}

include_once ('./scripts/former.php');
include_once ('./scripts/loader.php');

$loader = new loader();

$form = new form_generator('snmpwk');
$form->Title('SNMPWALK', 'male');
$form->Input('host', 'ホストアドレス', '接続先を指定します。', 'address-card', true);
$form->Input('community', 'コミュニティ', 'エージェントが属するコミュニティを設定します。', 'users', true);
$form->Input('oid', 'OID', 'フィルタリングするOIDを設定します。', 'object-ungroup', false);
$form->Button('walker', 'ログイン');

$index = $_SESSION['gsc_userindex'];
$getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERINDEX = $index");
?>

<html>
    <head>
	<?php echo $loader->loadHeader('Virtual Control', 'TEST') ?>
    </head>

    <body class="text-monospace">
        <!-- HEADER NAVIGATION -->
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>

        <!-- HEADER SECTION -->
	<?php echo $loader->load_logo() ?>

        <!-- TITLE SECTION -->
	<?php echo $loader->Title('SNMPWALK TEST', 'file-text') ?>

        <!-- CONTENT SECTION -->
        <div class="py-2 bg-primary">
            <div class="container">
                <div id="data_output"></div>
            </div>
	</div>

	<!-- FOOTER -->
	<?php echo $loader->footer() ?>

	<!-- FOOTER SCRIPTS -->
	<?php echo $loader->footerS(); ?>
	<script type="text/javascript">
	    
	    /* Ajax - SNMPWALKER */
	    $('#snmpwk').submit(function (event) {
		event.preventDefault();
		//ボタンによる実行を阻止
		var btn = document.getElementById("walker");

		var $form = $(this);
		
		ajax_dynamic_post('')
		$.ajax({
		    type: 'GET',
		    url: $form.attr('action'),
		    data: $form.serializeArray(),
		    dataType: 'json'
		}).done(function (res) {
		    removeLoading();
		    $('#snmpwalk-dialog').html(res["res"]);
		}).fail(function () {
		    removeLoading();
		    $('#snmpwalk-dialog').html("<strong>SNMPの取得に失敗しました。<br>===============================<br>以下の設定項目をご確認ください。<br>・ホストアドレスに間違いがないか<br>・対象機器の設定に不備がないか<br>・MIBの指定方法に間違いがないか</strong>");
		}).always(function () {
		    btn.value = "SNMPを実行";
		    btn.disabled = false;
		    /* Dialog Controller */
		    $('#dialog').parent().css({position: "fixed"}).end().dialog('open');
		});
	    });
	</script>
    </body>

</html>
