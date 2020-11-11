<!DOCTYPE html>

<!--
<?php
include ('./scripts/session_chk.php');
session_start();
if(!session_chk()) {
    http_response_code(301);
    header('location: 403.php');
    exit();
}
?>
-->

<html>
    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="Virtual Control">
        <link rel="icon" href="images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WARNINGS - A Controlling Network Tool.</title>
        <meta name="description" content="Virtual Control - A Controlling Network Tool.">
        <link rel="stylesheet" href="style/awesome.min.css" type="text/css">
        <link rel="stylesheet" href="style/aquamarine.css" type="text/css">
        <link rel="stylesheet" href="style/dialog.css" type="text/css">
        <link rel="stylesheet" href="style/Roboto.css" type="text/css">

        <script src="js/animate-in.js"></script>
        <script src="js/loader.js"></script>
    </head>

    <body class="text-monospace">
        <!-- Navbar -->
        <div id="nav"></div>
        
        <div class="bg-primary pt-5">
            <div class="container">
                <div id="logo"></div>
            </div>
        </div>
        
        <!-- Server  -->
        <div class="py-3" style="border-left-width: 0px; border-right-width: 0px; border-top-width: 0px; border-bottom-width: 4px; border-style: solid; border-color: #ff5a00;">
            <div class="container">
                
                <!-- Agent Information -->
                <h4 class="bg-dark my-1 rounded-sm py-2" style="" id="AgentName"><i class="fa fa-fw fa-server fa-lg"></i><b>エージェント</b></h4>
                <div class="scroll-view">
                    <ul class="black-view">
                        <li><i class="fa fa-fw fa-server fa-lg"></i>[Agent1] [Desc]</li>
                        <li><i class="fa fa-fw fa-server fa-lg"></i>[Agent2] [Desc]</li>
                        <li><i class="fa fa-fw fa-server fa-lg"></i>[Agent3] [Desc]</li>
                        <li><i class="fa fa-fw fa-server fa-lg"></i>[Agent4] [Desc]</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="py-3" style="">
            <div class="container">
                <div class="row">
                    <div class="col-md-12" style="">
                        <div class="list-group" style="">
                        </div>
                        <h2 class="" style=""><i class="fa fa-fw fa-bar-chart"></i><b>警告情報</b></h2>
                        <h6 style="" class="text-monospace" id="UpdateTime"><i class="fa fa-fw fa-clock-o"></i>データ取得日時：[Update_Time]<br></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-2 bg-primary">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class=""><i class="fa fa-fw fa-level-up"></i>警告レベル</h2>
                    </div>
                </div>
                <div class="row mx-auto">
                    <div class="col-md-2" style="">
                        <h1 class="display-1 text-center text-monospace bg-dark my-1" style=" border-bottom-right-radius: 0.2em; border-top-left-radius: 0.2em;">1</h1>
                    </div>
                    <div class="col-md-10 mx-auto" style="border-style: solid;	border-color: #000; border-left-width: 5px; border-right-width: 0px; border-top-width: 0px; border-bottom-width: 0px;">
                        <p class="mb-0" style="">1 - 現在、このサーバに警告情報はありません<br>2 - 警告情報が発信されています<br>3 - 警告情報をご確認ください<br>4 - 早急に警告の対応をしてください<br>5 - サーバ上の致命的なエラーが発生しています</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-2 bg-dark">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class=""><i class="fa fa-fw fa-clock-o"></i>最新の警告</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card text-center">
                            <div class="card-body bg-primary" style=" border-left-width: 0px; border-right-width: 0px; border-top-width: 0px; border-bottom-width: 2px; border-style: solid; border-color: #000;">
                                <h5 class="card-title"><i class="fa fa-fw fa-exclamation-circle"></i>エージェントと接続がありません</h5>
                                <p class="card-text">SNMPエージェントとのSNMPアクセスが失敗しており、エージェントとの接続が確立しません。</p>
                                <a href="#" class="btn btn-dark"><i class="fa fa-fw fa-info-circle"></i>警告の詳細へ</a>
                            </div>
                            <div class="card-footer bg-secondary">更新: 2日前</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-2 bg-primary">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="list-group">
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h2><i class="fa fa-fw fa-info-circle"></i>警告の詳細</h2>
                    </div>
                </div>
                <div class="row bg-dark my-2 mx-auto">
                    <div class="col-md-12">
                        <ul class="py-2 nav nav-pills flex-grow-1 flex-column bg-dark orange-view">
                            <li class="nav-item" selected> <a href="" class="nav-link" data-toggle="pill" data-target="#tabone"><i class="fa fa-fw fa-exclamation-circle"></i>エージェントと接続ができません</a> </li>
                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabtwo"><i class="fa fa-fw fa-check-square-o"></i>制御情報に違反しているアクセスがあります</a> </li>
                            <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabthree"><i class="fa fa-fw fa-cloud"></i>リンクアップが行われました</a> </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Warnings List -->
                <div class="row">
                    <div class="p-2 mx-auto bg-dark col-md-10 col-11" style="border-radius: 0.5em 0 0.5em 0;">
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tabone" role="tabpanel">
                                <h5><i class="fa fa-fw fa-exclamation-circle"></i>エージェントと接続がありません<br></h5>
                                <h6>発行日: ####/##/##</h6>
                                <ul class="orange-view">
                                    <li><strong>状況</strong><br>解決を行ってください</li>
                                    <li><strong>エージェント情報</strong><br>SNMPv2 Supported Trap. ...</li>
                                    <li><strong>トラップデータ</strong><br>wwwjazoth11029211 ...</li>
                                </ul>
                            </div>
                            
                            <div class="tab-pane fade" id="tabtwo" role="tabpanel">
                                <h5><i class="fa fa-fw fa-check-square-o"></i>制御情報に違反しているアクセスがあります<br></h5>
                                <h6>発行日: ####/##/##</h6>
                                <ul class="orange-view">
                                    <li><strong>状況</strong><br>解決を行ってください</li>
                                    <li><strong>エージェント情報</strong><br>SNMPv2 Supported Trap. ...</li>
                                    <li><strong>トラップデータ</strong><br>wwwjazoth11029211 ...</li>
                                </ul>
                            </div>
                            
                            <!-- Warn-Format -->
                            <div class="tab-pane fade" id="tabthree" role="tabpanel">
                                <h5><i class="fa fa-fw fa-exclamation-circle"></i>エージェントと接続がありません<br></h5>
                                <h6>発行日: ####/##/##</h6>
                                <ul class="orange-view">
                                    <li><h5 class="warnTitle">状況</h5><hr class="orange">解決を行ってください</li>
                                    <li><h5 class="warnTitle">エージェント情報</h5><hr class="orange">SNMPv2 Supported Trap. ...</li>
                                    <li><h5 class="warnTitle">トラップデータ</h5><hr class="orange">wwwjazoth11029211 ...</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Features -->
        <!-- Call to action -->

        <!-- Footer -->
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