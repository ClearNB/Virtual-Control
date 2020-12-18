<?php
include_once ('../scripts/general/sqldata.php');
include_once ('../scripts/session/session_chk.php');
include_once ('../scripts/general/loader.php');
include_once ('../scripts/general/former.php');

if (!session_chk()) {
    http_response_code(301);
    header('Location: ../403.php');
    exit();
}
$id = $_SESSION['gsc_userid'];
$getdata = select(true, "GSC_USERS", "USERNAME, PERMISSION", "WHERE USERID = '$id'");
if (!$getdata && $getdata['PERMISSION'] != 0) {
    http_response_code(301);
    header('Location: ../403.php');
    exit();
}
$loader = new loader();

$fm_pg = new form_generator('fm_pg');
$fm_pg->Button('bt_ag_bk', '設定一覧へ', 'button', 'list');
$fm_pg->SubTitle('OPTION - AGENT', 'エージェントを選択してください。', 'book');
$fm_pg->Check(1, 'rd_01', 'agt', '1', 'AGENT1', true);
$fm_pg->Check(1, 'rd_02', 'agt', '2', 'AGENT2',false);
$fm_pg->Check(1, 'rd_03', 'agt', '3', 'AGENT3',false);
$fm_pg->Button('bt_ag_cr', '作成', 'button','plus-square');
$fm_pg->Button('bt_ag_ed', '編集', 'button','edit');
$fm_pg->Button('bt_ag_dl', '削除', 'button','trash-alt');

$fm_ag_cr = new form_generator('fm_ag_cr'); //エージェントIPアドレス
$fm_ag_ed = new form_generator('fm_ag_ed');
$fm_ag_dl = new form_generator('fm_ag_dl');

//追加ページ
$fm_ag_cr->Input('in_ag_ad', 'エージェントIPアドレス',
        'IPアドレスのほか、ホスト名、ドメイン名の入力ができます。',
        'server', true);

$fm_ag_cr->Input('in_ag_co', 'コミュニティ名',
        'SNMPv2cでのエージェントに対応したコミュニティ名を入力します。',
        'american-sign-language-interpreting', true);

$fm_ag_cr->Button('bt_cr_nx', '次へ', 'button', 'arrow-right');
$fm_ag_cr->Button('bt_cr_bk', '戻る', 'button', 'long-arrow-alt-left');

$fm_ag_sl = new form_generator('fm_ag_sl'); //MIBの設定1
$fm_ag_sl->SubTitle('MIBの設定', '', '');
$fm_ag_sl->Check(0, 'rd_04', 'agt', '4', 'MIBサブツリー1', true);
$fm_ag_sl->Check(0, 'rd_05', 'agt', '5', 'MIBサブツリー2', false);
$fm_ag_sl->Check(0, 'rd_06', 'agt', '6', 'MIBサブツリー3', false);

$fm_ag_sl->Button('bt_sl_nx', '次へ', 'button', 'arrow-right');
$fm_ag_sl->Button('bt_sl_bk', '戻る', 'button', 'long-arrow-alt-left');
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'OPTION - AGENT', true) ?>
        <?php echo form_generator::ExportClass([$fm_pg, $fm_ag_cr, $fm_ag_ed, $fm_ag_dl, $fm_ag_sl]) ?>
    </head>
    <body>
	<?php echo $loader->navigation($getdata['PERMISSION']) ?>
        <?php echo $loader->load_Logo() ?>

        <?php echo $loader->Title('OPTION - AGENT', 'user') ?>
        <div id="data_output"></div>

	<?php echo $loader->footer() ?>
        <?php echo $loader->footerS(true) ?>

        <script type="text/javascript">
            $(document).ready(function () {
                animation('data_output', 0, fm_pg);
            });

            $(document).on('click', '#bt_ag_cr', function () { //追加ボタンクリック
                animation('data_output', 400, fm_ag_cr);
            });

            $(document).on('click', '#bt_ag_ed', function () { //編集ボタンクリック
                animation('data_output', 400, fm_ag_ed);
            });

            $(document).on('click', '#bt_ag_dl', function () { //削除ボタンクリック
                animation('data_output', 400, fm_ag_dl);
            });

            $(document).on('click', '#bt_cr_nx', function () { //次へボタンクリック
                animation('data_output', 400, fm_ag_sl);
            });
            
            $(document).on('click', '#bt_cr_bk', function () { //戻る7ボタンクリック
                animation('data_output', 400, fm_pg);
            });
            
            $(document).on('click', '#bt_sl_nx', function () { //次へボタンクリック
                animation('data_output', 400, fm_ag_sl);
            });
            
            $(document).on('click', '#bt_sl_bk', function () { //戻る7ボタンクリック
                animation('data_output', 400, fm_ag_cr);
            });
        </script>
	
    </body>
</html>