<!DOCTYPE html>
<?php
include_once ('./scripts/loader.php');
$loader = new loader();

include_once ('./scripts/sqldata.php');
include_once ('./scripts/common.php');
include_once ('./scripts/dbconfig.php');
include_once ('./scripts/former.php');

$former = new form_generator('form_failed');
$former->SubTitle('403 (Forbidden)', 'あなたはこのページを表示・操作できません。', 'fas fa-times-cirlce');
$former->Button('', '', 'button', 'fas fa-');

$index = $_SESSION['gsc_userindex'];
$getdata = select(true, 'GSC_USERS', 'USERNAME, PERMISSION', "WHERE USERINDEX = $index");
?>

<html>
    <head>
        <?php echo $loader->loadHeader('Virtual Control', 'INDEX') ?>
    </head>

    <body class="text-monospace">
        <!-- Navbar & Logo -->
        <?php echo $loader->navigation($getdata['PERMISSION']) ?>
        
        <div class="bg-primary pt-5">
            <div class="container">
                <?php echo $loader->load_Logo() ?>
            </div>
        </div>

        <div class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php echo $loader->SubTitle('403 (Forbidden)', 'このページを表示・操作する権限がありません。', 'fas fa-times-circle') ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-primary py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-block btn-lg btn-dark" href="index.php">
                            <i class="fa fa-fw fa-arrow-circle-o-left"></i>戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <?php echo $loader->footer() ?>

        <script src="js/jquery.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </body>

</html>