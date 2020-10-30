<!DOCTYPE html>

<!-- PHP HEADER MODULE -->

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

        <script src="js/navbar-ontop.js"></script>
        <script src="js/animate-in.js"></script>
        <script src="js/loader.js"></script>
        <script src="js/form_generator.js"></script>
        <script type="text/javascript">
            const c = new form_generator("snmpwk", "./scripts/SNMPWalk.php");
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
            <script src="js/jquery-3.3.1.min.js"></script>
            <script src="js/popper.min.js"></script>
            <script src="js/bootstrap.min.js"></script>
            <script type="text/javascript">
            load(1);
            c.Export();
            load_title("SNMPWALK", "SNMPのエージェントとの接続確認にご活用ください。");
            /* Ajax - SNMPWALKER */
            $(function () {
                $('#snmpwk').submit(function(event) {
                    event.preventDefault();
                    var btn = document.getElementById("walker");
                    btn.disabled = true;
                    btn.value = "アクセス中...";
                    var $form = $(this);
                    
                    $.ajax({
                        type: 'GET',
                        url: $form.attr('action'),
                        data: $form.serializeArray(),
                        dataType: 'json'
                    }).done(function(res) {
                        generate_empty_dialog("snmpwalk-dialog", "<i class=\"fa fa-fw fa-server fa-lx\"></i>SNMPWALK 結果", true);
                        $('#snmpwalk-dialog').html(res["res"]);
                    }).fail(function() {
                        generate_empty_dialog("snmpwalk-dialog", "<i class=\"fa fa-fw fa-server fa-lx\"></i>SNMPWALK 結果", true);
                        $('#snmpwalk-dialog').html("<strong>SNMPの取得に失敗しました。<br>===============================<br>以下の設定項目をご確認ください。<br>・ホストアドレスに間違いがないか<br>・対象機器の設定に不備がないか<br>・MIBの指定方法に間違いがないか</strong>");
                    }).always(function() {
                        btn.value = "SNMPを実行";
                        btn.disabled = false;
                        /* Dialog Controller */
                        (function () {
                            const close = document.getElementById('close');
                            const dialog = document.getElementById('dialog');

                            dialog.showModal();

                            close.addEventListener('click', function () {
                                dialog.close();
                            });

                            dialog.addEventListener('click', function (event) {
                                if (event.target === dialog) {
                                    dialog.close('cancelled');
                                }
                            });
                        }());
                    });
                });
            });
            </script>
    </body>

</html>
