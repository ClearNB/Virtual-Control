<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="Virtual Control">
        <link rel="icon" href="../images/favicon.ico">
        <meta name="viewport" content="width = device-width, initial-scale = 1">
        <title>Server Setting - Virtual Control</title>
        <meta name="description" content="Virtual Control - A Controlling Network Tool.">
        <link rel="stylesheet" href="../style/awesome.min.css" type="text/css">
        <link rel="stylesheet" href="../style/aquamarine.css" type="text/css">
        <link rel="stylesheet" href="../style/dialog.css" type="text/css">
        <link rel="stylesheet" href="../jquery-style/jquery-ui.css" type="text/css">
        <link rel="stylesheet" href="../jquery-style/jquery-ui.structure.css" type="text/css">
        <link rel="stylesheet" href="../jquery-style/jquery-ui.theme.css" type="text/css">

        <script src="../js/animate-in.js"></script>
        <script src="../js/loader.js"></script>
        <script src="../js/dialog.js"></script>
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
        <div class="py-3" id="title"></div>

        <!-- CONTENT SECTION -->
        <div class="py-2 bg-primary">
            <div class="container" id="data_output">

                <div class="row m-2">
                    <div class="col-md-12" id="content_output">
                        <!-- Main Content Here -->
                    </div>
                </div>

                <!-- Abastract Content Here (ID Only) -->
                <div id="dialogrt"></div>
                <div id="former"></div>
            </div>

            <!-- FOOTER -->
            <div id="foot"></div>

            <!-- FOOTER SCRIPTS -->
            <script src="../js/now_loading.js"></script>
            <script src="../js/jquery.js"></script>
            <script src="../jquery/jquery-ui.js"></script>
            <script src="../js/popper.min.js"></script>
            <script src="../js/bootstrap.min.js"></script>
            <script type="text/javascript">
                //Javascript, jQuery Code Structure Here
                load_title("MIB設定", "MIBについて設定します。", "server");
                load(1);
            </script>
    </body>
</html>