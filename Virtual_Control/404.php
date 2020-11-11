<!DOCTYPE html>
<?php
include ('./scripts/loader.php');
$loader = new loader();
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'INDEX') ?>
    </head>

    <body class="text-monospace">
        <div id="nav"></div>
        <div class="bg-primary pt-5">
            <div class="container">
                <div id="logo"></div>
            </div>
        </div>
        <div class="py-3" style="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12" style="">
                        <h1 class="text-uppercase"><i class="fa fa-fw fa-exclamation-triangle"></i>404 Not Found</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-monospace">指定されたページはありません。<br>ホームにお戻りください。</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-2 bg-primary">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-block btn-lg py-2 btn-dark active" href="index.php"><i class="fa fa-fw fa-arrow-circle-o-left"></i>戻る</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="foot"></div>

        <script src="js/jquery.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            load(1);
        </script>
    </body>

</html>