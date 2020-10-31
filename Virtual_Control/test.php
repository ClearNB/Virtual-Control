<!DOCTYPE html>

<!-- PHP HEADER MODULE -->
<?php
session_start();
if (!isset($_SESSION['username']) && !isset($_SESSION['permission'])) {
    http_response_code(403);
    header("Location: 403.php");
    exit;
}
?>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="Virtual Control">
        <link rel="icon" href="images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TEST - A Controlling Network Tool.</title>
        <meta name="description" content="Virtual Control - A Controlling Network Tool.">
        <link rel="stylesheet" href="style/awesome.min.css" type="text/css">
        <link rel="stylesheet" href="style/aquamarine.css" type="text/css">
        <link rel="stylesheet" href="style/dialog.css" type="text/css">
        <link rel="stylesheet" href="jquery-style/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="jquery-style/jquery-ui.structure.css" type="text/css">
        <link rel="stylesheet" href="jquery-style/jquery-ui.theme.css" type="text/css">
        
        <script src="js/navbar-ontop.js"></script>
        <script src="js/animate-in.js"></script>
        <script src="js/loader.js"></script>
        <script src="js/form_generator.js"></script>
        <script type="text/javascript">
            const c = new form_generator("snmpwk", "./scripts/snmpwalk.php");
            c.Title("SNMPWALK", "male");
            c.Input("host", "ホストアドレス", "接続先を指定します。", "address-card", true);
            c.Input("community", "コミュニティ", "エージェントが属するコミュニティを設定します。", "users", true);
            c.Input("oid", "OID", "フィルタリングするOIDを設定します。", "object-ungroup", false);
            c.Button("walker", "SNMPWALKを実行");
        </script>
    </head>

    <body class="text-monospace">
        <!-- HEADER NAVIGATION -->
        <div id="nav"></div>

        <!-- HEADER SECTION -->
        <div class="bg-primary pt-5">
            <div class="container">
                <div id="logo" class="text-center"></div>
            </div>
        </div>

        <!-- TITLE SECTION -->
        <div class="py-3" id='title'></div>

        <!-- CONTENT SECTION -->
        <div class="py-2 bg-primary">
            <div class="container">

                <div class="row m-2">
                    <div class="col-md-12">
                        <!-- BUTTON -->
                        <a class="btn btn-block btn-lg py-2 btn-dark active" href="index.php"><i class="fa fa-fw fa-arrow-circle-o-left"></i>戻る</a>
                    </div>
                </div>

                <div id="dialogrt"></div>

                <div id="former"></div>
            </div>

            <!-- FOOTER -->
            <div id="foot"></div>

            <!-- FOOTER SCRIPTS -->
            <script src="js/now_loading.js"></script>
            <script src="js/jquery.js"></script>
            <script src="jquery/jquery-ui.js"></script>
            <script src="js/popper.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script type="text/javascript">
            load(1);
            c.Export();
            load_title("SNMPWALK", "SNMPのエージェントとの接続確認にご活用ください。");
            /* Ajax - SNMPWALKER */
            $(function () {
                $('#snmpwk').submit(function (event) {
                    //ローディング
                    dispLoading();
                    
                    event.preventDefault();
                    //ボタンによる実行を阻止
                    var btn = document.getElementById("walker");
                    btn.disabled = true;
                    btn.value = "実行中です。";
                    
                    var $form = $(this);
                    $.ajax({
                        type: 'GET',
                        url: $form.attr('action'),
                        data: $form.serializeArray(),
                        dataType: 'json'
                    }).done(function (res) {
                        generate_empty_dialog("snmpwalk-dialog", "<i class=\"fa fa-fw fa-server fa-lx\"></i>SNMPWALK 結果", true);
                        $('#snmpwalk-dialog').html(res["res"]);
                    }).fail(function () {
                        generate_empty_dialog("snmpwalk-dialog", "<i class=\"fa fa-fw fa-server fa-lx\"></i>SNMPWALK 結果", true);
                        $('#snmpwalk-dialog').html("<strong>SNMPの取得に失敗しました。<br>===============================<br>以下の設定項目をご確認ください。<br>・ホストアドレスに間違いがないか<br>・対象機器の設定に不備がないか<br>・MIBの指定方法に間違いがないか</strong>");
                    }).always(function () {
                        removeLoading();
                        btn.value = "SNMPを実行";
                        btn.disabled = false;
                        /* Dialog Controller */
                        $('#dialog').dialog({
                            autoOpen: false,
                            resizable: false,
                            width: "80%",
                            closeOnEscape: true,
                            modal: true,
                            show: {
                                effect: "blind",
                                duration: 1000
                            },
                            hide: {
                                effect: "blind",
                                duration: 1000
                            },
                            buttons: [
                                {
                                    text: '閉じる',
                                    class: 'btn btn-block btn-lg btn-primary active',
                                    click: function() {
                                        $(this).dialog('close');
                                    }
                                }
                            ]
                        });
                        
                        $('#dialog').parent().css({position: "fixed"}).end().dialog('open');
                    });
                });
            });
            </script>
    </body>

</html>
