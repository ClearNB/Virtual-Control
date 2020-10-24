<!DOCTYPE html>

<?php
$agents = ['agt01', 'agt02', 'agt03', 'agt04', 'agt05']; //エージェントデータはデータベースから取得する
?>

<html>

    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="Virtual Control">
        <link rel="icon" href="images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Virtual Control - A Controlling Network Tool.</title>
        <meta name="description" content="Virtual Control - A Controlling Network Tool.">
        <!-- CSS -->
        <link rel="stylesheet" href="awesome.min.css" type="text/css">
        <link rel="stylesheet" href="aquamarine.css" type="text/css">
        <!-- JavaScript -->
        <script src="js/navbar-ontop.js"></script>
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

        <div class="py-3">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="text-monospace text-left text-uppercase"><i class="fa fa-fw fa-server"></i>[Server_NAME]</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="text-monospace">[Description]</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12"><a href="#d01" class="list-group-item list-group-item-action flex-column align-items-start active my-1">
                            <h4 class="text-monospace bg-dark my-1 rounded-sm py-2" id="AgentName"><i class="fa fa-fw fa-server fa-lg"></i><b>[Agent_Info]</b></h4>
                            <div class="d-flex w-100 justify-content-between">
                            </div>
                            <p class="mb-1 text-monospace" id="AgentDescription">[Agent_Description]</p>
                            <div class="row">
                                <div class="col-md-6"><small class="text-monospace" id="AgentStatus">[Agent_Status]</small></div>
                                <div class="col-md-6 "><i class="fa fa-bars fa-2x pull-right my-1 fa-fw"></i></div>
                            </div>
                            <span class="badge badge-pill badge-primary" ></span>
                    </a></div>
                    <!-- Agent Selection -->
                    
                </div>
            </div>
        </div>
        <div class="py-3 border-top border-light" >
            <div class="container">
                <div class="row">
                    <div class="col-md-12" >
                        <div class="list-group" >
                        </div>
                        <h2 class="" ><i class="fa fa-fw fa-bar-chart"></i><b>データアナリティクス</b></h2>
                        <h6  class="" id="UpdateTime"><i class="fa fa-fw fa-clock-o"></i>データ取得日時：[Update_Time]<br></h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-2 bg-primary">
            <div class="container">
                <div class="row">
                    <div class="col-md-12" >
                        <div class="table-responsive" >
                            <h3 class="text-left text-body"><i class="fa fa-fw fa-television"></i>機器情報</h3>
                            <table class="table table-hover">
                                <thead class="thead-dark"></thead>
                                <tbody>
                                    <tr>
                                        <th>機器のホスト名</th>
                                        <td  id="sysName">[sysName]</td>
                                    </tr>
                                    <tr>
                                        <th >機器説明</th>
                                        <td id="sysDescr">[sysDescr]</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">ローケーション情報</th>
                                        <td id="sysLocation">[sysLocation]</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12" >
                        <h3 class="text-left text-body"><i class="fa fa-fw fa-television"></i>インタフェース情報</h3>
                        <!-- インタフェース数 -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-dark"></thead>
                                <tbody>
                                    <tr>
                                        <th>インタフェース数<br></th>
                                        <td id="ifNumber">[ifNumber]</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- インタフェース一覧 -->
                        <h4 class="text-left text-body"><i class="fa fa-fw fa-television"></i>インタフェース一覧</h4>
                        <details>
                            <summary>[ifIndex]</summary>
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <tbody>
                                        <tr>
                                            <th>インタフェース名</th>
                                            <td id="ifDescr">[ifDescr]</td>
                                        </tr>
                                        <tr>
                                            <th>インタフェース説明</th>
                                            <td id="ififAlias">[ififAlias]</td>
                                        </tr>
                                        <tr>
                                            <th>論理的な状態</th>
                                            <td id="ifAdminStatus">[ifAdminStatus]</td>
                                        </tr>
                                        <tr>
                                            <td><b>物理的な状態</b></td>
                                            <td id="ifOperStatus">[ifOperStatus]</td>
                                        </tr>
                                        <tr>
                                            <td><b>MTU</b></td>
                                            <td id="ifMtu">[ifMtu]</td>
                                        </tr>
                                        <tr>
                                            <td><b>インタフェースの帯域</b></td>
                                            <td>[ifSpeed] - [ifHighSpeed]</td>
                                        </tr>
                                        <tr>
                                            <td><b>受信/送信パケットバイト数(64ビット)</b></td>
                                            <td>[ifHCInOctets] / [ifHCOutOctets]</td>
                                        </tr>
                                        <tr>
                                            <td><b>受信/送信ユニキャストパケット数</b></td>
                                            <td>[ifHCInUcastPkts] / [ifHCOutUcastPkts]</td>
                                        </tr>
                                        <tr>
                                            <td><b>受信/送信マルチキャストパケット数</b></td>
                                            <td>[ifHCInMulticastPkts] / [ifHCOutMulticastPkts]</td>
                                        </tr>
                                        <tr>
                                            <td><b>受信/送信ブロードキャストパケット数</b></td>
                                            <td>[ifHCInBroadcastPkts] / [ifHCOutBroadcastPkts]</td>
                                        </tr>
                                        <tr>
                                            <td><b>破棄した受信/送信パケット数</b></td>
                                            <td>[ifInDiscards] / [ifOutDiscards]</td>
                                        </tr>
                                        <tr>
                                            <td><b>受信/送信パケットエラー数</b></td>
                                            <td>[ifInErrors] / [ifOutErrors]</td>
                                        </tr>
                                        <tr>
                                            <td><b>プロトコルが不明な受信パケット数</b></td>
                                            <td>[ifInUnknownProtos]</td>
                                        </tr>
                                    </tbody>
                                </table>
                        </details>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-left text-body"><i class="fa fa-fw fa-globe"></i>IP通信情報</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-left text-body"><i class="fa fa-fw fa-id-card"></i>TCP通信情報</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-left text-body"><i class="fa fa-fw fa-align-justify"></i>UDP通信情報</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-left text-body"><i class="fa fa-fw fa-commenting"></i>ICMP通信情報</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-left text-body"><i class="fa fa-fw fa-address-book"></i>SNMP通信情報</h3>
                    </div>
                </div>
                <hr class="mt-0">
            </div>
        </div>
        <!-- Features -->
        <!-- Call to action -->
        <!-- Footer -->
        <div id="foot"></div>

        <!-- JavaScript dependencies -->
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script type="text/javascript">
            load(1);
        </script>
    </body>

</html>