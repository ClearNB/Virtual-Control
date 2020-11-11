<!DOCTYPE html>

<!-- PHP HEADER MODULE -->
<?php
include ('./scripts/session_chk.php');
session_start();
if(!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}

include_once ('./scripts/former.php');
include_once ('./scripts/loader.php');

$loader = new loader();

$form = new form_generator('snmpwk', '');
$form -> Title('SNMPWALK', 'male');
$form -> Input('host', 'ホストアドレス', '接続先を指定します。', 'address-card', true);
$form ->Input('community', 'コミュニティ', 'エージェントが属するコミュニティを設定します。', 'users', true);
$form -> Input('oid', 'OID', 'フィルタリングするOIDを設定します。', 'object-ungroup', false);
$form -> Button('walker', 'ログイン');

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
        <div class="bg-primary pt-5">
            <div class="container">
                <?php echo $loader->load_Logo() ?>
            </div>
        </div>

        <!-- TITLE SECTION -->
        <div class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $loader->SubTitle("TEST", "SNMPWALKのテストを行います。", "fas fa-server") ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT SECTION -->
        <div class="py-2 bg-primary">
            <div class="container">

                <div class="row m-2">
                    <div class="col-md-12">
                        <!-- BUTTON -->
                        <a class="btn btn-block btn-lg py-2 btn-dark active" href="index.php">
                            <i class="fa fa-fw fa-arrow-circle-o-left"></i>戻る
                        </a>
                    </div>
                </div>
                <div id="data_output"></div>
            </div>

            <!-- FOOTER -->
            <?php echo $loader->footer() ?>

            <!-- FOOTER SCRIPTS -->
            <script src="js/now_loading.js"></script>
            <script src="js/jquery.js"></script>
            <script src="jquery/jquery-ui.js"></script>
            <script src="js/popper.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script type="text/javascript">
            load(1);
            load_title("SNMPWALK", "SNMPのエージェントとの接続確認にご活用ください。");
            /* Ajax - SNMPWALKER */
            $(function () {
                $('#snmpwk').submit(function (event) {
                    event.preventDefault();
                    //ボタンによる実行を阻止
                    var btn = document.getElementById("walker");
                    btn.disabled = true;
                    btn.value = "実行中です。";
                    
                    //ローディング
                    dispLoading();
                    generate_empty_dialog("dialog", "snmpwalk-dialog", "<i class=\"fa fa-fw fa-server fa-lx\"></i>SNMPWALK 結果");
                    $('#dialog').dialog({
                        autoOpen: false,
                        resizable: false,
                        width: "50%",
                        closeOnEscape: true,
                        modal: true,
                        show: {
                            effect: "fade",
                            duration: 800
                        },
                        hide: {
                            effect: "fade",
                            duration: 800
                        },
                        buttons: [
                            {
                                text: '閉じる',
                                class: 'btn btn-block btn-lg btn-primary active',
                                click: function() {
                                    $(this).dialog('close');
                                },
                                open:function() {
                                    $(".ui-dialog-titlebar-close").hide();
                                }
                            }
                        ]
                    });
                    
                    var $form = $(this);
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
            });
            </script>
    </body>

</html>
