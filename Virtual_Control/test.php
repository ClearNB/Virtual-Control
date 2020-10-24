<!DOCTYPE html>

<!-- PHP HEADER MODULE -->

<html>
    <head>
        <!-- HEADER SCRIPTS -->
        <meta charset="utf-8">
        <meta name="application-name" content="Virtual Control">
        <link rel="icon" href="images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>FORMAT PAGE - Virtual Control</title>
        <meta name="description" content="Virtual Control - A Controlling Network Tool.">
        <link rel="stylesheet" href="awesome.min.css" type="text/css">
        <link rel="stylesheet" href="aquamarine.css" type="text/css">
        <link rel="stylesheet" href="style/dialog.css" type="text/css">

        <script src="js/navbar-ontop.js"></script>
        <script src="js/animate-in.js"></script>
        <script src="js/loader.js"></script>
    </head>

    <body>
        <!-- HEADER NAVIGATION -->
        <div id="nav"></div>

        <!-- HEADER SECTION -->
        <div class="bg-primary pt-5">
            <div class="container">
                <div id="logo" class="text-center"></div>
            </div>
        </div>

        <!-- TITLE SECTION -->
        <div class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="text-uppercase"><i class="fa fa-fw fa-exclamation-triangle"></i>FORMAT</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-monospace">ここにページの説明を書き入れます。</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT SECTION -->
        <div class="py-2 bg-primary">
            <div class="container">
                <div class="row m-2">
                    <div class="col-md-12">
                        <!-- BUTTON --> <a class="btn btn-block btn-lg py-2 btn-dark active" href="index.php"><i class="fa fa-fw fa-arrow-circle-o-left"></i>戻る</a>
                    </div>
                </div>
                <div class="row m-2">
                    <div class="col-md-12">
                        <!-- DIALOG --> <div id="dialog"></div>
                    </div>
                </div>
                <!-- FORM EXAMPLE -->
                <form action="" method="POST">
                    <div class="form-group"> <label class="formtitle">項目タイトル<br></label>
                        <input type="text" class="form-control bg-dark my-1 form-control-lg shadow-sm text-monospace" placeholder="Enter UserID" required="required" id="userid" name="userid" value="<?php echo htmlspecialchars($userid, ENT_QUOTES, 'UTF-8'); ?>">
                        <small class="form-text text-body" style="">ユーザIDはVCServerによって振り分けられています。</small> </div>
                        <INPUT type="checkbox" class="form-check bg-dark my-1 text-monospace" name="aiueo" value="1" required="required" >あああ
                    <button type="submit" class="btn btn-dark btn-block btn-lg shadow-lg"><i class="fa fa-fw fa-sign-in"></i>送信</button>
                </form>
            </div>
        </div>

        <!-- FOOTER -->
        <div id="foot"></div>

        <!-- FOOTER SCRIPTS -->
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            load(1);
            generate_dialog('d01', 'テスト', 'フォーマット系です。<br>HTMLでセクションを書くことができます。');
        </script>
    </body>

</html>
