<!DOCTYPE html>

<?php
include ('./scripts/session_chk.php');
include ('./scripts/loader.php');
if(!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}
$loader = new loader();

$agents = ['agt01', 'agt02', 'agt03', 'agt04', 'agt05']; //エージェントデータはデータベースから取得する
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'INDEX') ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <div id="nav"></div>
        
        <div class="bg-primary pt-5">
            <div class="container">
                <div id="logo"></div>
            </div>
        </div>
        
        <div class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $loader->SubTitle("ANALYTICS", "データを解析しましょう", "fas fa-chart-bar") ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="foot"></div>

        <!-- JavaScript dependencies -->
        <script src="js/jquery.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            load(1);
        </script>
    </body>

</html>