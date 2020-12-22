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

$fm = new form_generator('fm');
$fm->SubTitle('アクセス禁止', 'あなたはこのページを表示・操作できません。', 'fas fa-times-circle');
$fm->Button('bt_fm_bk', 'ホームへ戻る', 'button', 'fas fa-home');
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', '403 (Forbidden)', true) ?>
	<?php echo form_generator::ExportClass() ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar & Logo -->
        <?php echo $loader->navigation($per) ?>

        <?php echo $loader->load_Logo() ?>
        
        <?php echo $loader->Title('403 (Forbidden)', 'fas fa-exclamation') ?> <!-- ページタイトル -->
        
        <div id="data_output"></div>
        
        <!-- Footer -->
        <?php echo $loader->footer() ?>
        
        <?php echo $loader->footerS(true) ?>
        
        <script type="text/javascript">
            $(document).ready(function() {
                animation('data_output', 0, fm);
            });
            
            $(document).on('click', '#bt_fm_bk', function() {
                animation_to_sites('data_output', 400, './');
            });
        </script>
    </body>

</html>