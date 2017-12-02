<?php
    require_once 'add/HeaderSession.php';
    $logout = $_GET['slogout'];
    if($logout == '1'){
        $_SESSION = array();
        session_destroy();
    }
    if (empty($_SESSION['usuario'])) {
        session_start();
        $_SESSION['logout'] = "Logout efetuado com sucesso.";
        header("Location: principal");
    }
    require_once 'private_html_protected/config.php';
    require_once 'private_html_protected/connection.php';
    require_once 'private_html_protected/database.php';

    //Detecta Mobile
    include_once 'mobile_detect/Mobile_Detect.php';
    $detect = new Mobile_Detect;
    $is_mobile=$detect->isMobile();

    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        header("Location: LoginSimultaneo.php");
    }
    $dados = DBSearch("dados_usuario","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"certas_mes, respostas_mes, certas_total, respostas_total, certas_area1, total_area1, certas_area2, total_area2, certas_area3, total_area3, certas_area4, total_area4");
    if(isset($dados[0])){
        if(empty($dados[0]['respostas_mes'])){
            $certas_mes = 0;
            $erradas_mes = 0;
        } else {
            $certas_mes = ($dados[0]['certas_mes']*100)/$dados[0]['respostas_mes'];
            $erradas_mes = 100-$certas_mes;
        }
        if(empty($dados[0]['respostas_total'])){
            $certas_total = 0;
            $erradas_total = 0;
        } else {
            $certas_total = ($dados[0]['certas_total']*100)/$dados[0]['respostas_total'];
            $erradas_total = 100-$certas_total;
        }
        if(empty($dados[0]['total_area1'])){
           $erradas_area1 = 0;
           $certas_area1 = 0;
        } else {
            $certas_area1 = ($dados[0]['certas_area1']*100)/$dados[0]['total_area1'];
            $erradas_area1 = 100-$certas_area1;
        }
        if(empty($dados[0]['total_area2'])){
           $erradas_area2 = 0;
           $certas_area2 = 0;
        } else {
            $certas_area2 = ($dados[0]['certas_area2']*100)/$dados[0]['total_area2'];
            $erradas_area2 = 100-$certas_area2;
        }
        if(empty($dados[0]['total_area3'])){
           $erradas_area3 = 0;
           $certas_area3 = 0;
        } else {
            $certas_area3 = ($dados[0]['certas_area3']*100)/$dados[0]['total_area3'];
            $erradas_area3 = 100-$certas_area3;
        }
        if(empty($dados[0]['total_area4'])){
           $erradas_area4 = 0;
           $certas_area4 = 0;
        } else {
           $certas_area4 = ($dados[0]['certas_area4']*100)/$dados[0]['total_area4'];
           $erradas_area4 = 100-$certas_area4;
        }
        $certas_area1 = number_format($certas_area1,2,'.','');
        $certas_area2 = number_format($certas_area2,2,'.','');
        $certas_area3 = number_format($certas_area3,2,'.','');
        $certas_area4 = number_format($certas_area4,2,'.','');
        $certas_mes = number_format($certas_mes,2,'.','');
        $certas_total = number_format($certas_total,2,'.','');

        $erradas_area1 = number_format($erradas_area1,2,'.','');
        $erradas_area2 = number_format($erradas_area2,2,'.','');
        $erradas_area3 = number_format($erradas_area3,2,'.','');
        $erradas_area4 = number_format($erradas_area4,2,'.','');
        $erradas_mes = number_format($erradas_mes,2,'.','');
        $erradas_total = number_format($erradas_total,2,'.','');
        
    }
?>
<html>
    <head>
        <meta name="robots" content="noindex">
        <link href="css/reset.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/footer.css" rel="stylesheet" type="text/css">
        
        <title>Desempenho</title>
        <meta charset="UTF-8">
        <link rel="sortcut icon" href="img/favicon/line-chart.png" type="image/png" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link href="css/desempenho.css" rel="stylesheet" type="text/css"/>    
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <link href="iconfont/material-icons.css" rel="stylesheet">
        <script type="text/javascript" src="js/websockets.js"></script>
        <?php
            if($is_mobile){
                echo "<link href='css/styleMenu.css' rel='stylesheet'>";
                echo "<link href='css/animateMenu.css' rel='stylesheet'>";   
            }
        ?>
        <?php include_once("add/analyticstracking.php") ?>
    </head>
    <body>
    
        
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <?php if($is_mobile){ ?>
            <div id="navbar-mobile">
                <div class="menu-group">
                    <div class="menu-section animated" id="menu-groupID">
                        <div class="three col" onclick="showMenu()">
                            <div class="hamburger" id="hamburger-6">
                                <span class="line"></span>
                                <span class="line"></span>
                                <span class="line"></span>
                            </div>
                            <div class="divisorV"></div>
                        </div>
                    </div>
                    <div id="menu-active" class="animated first" style="display: none;">
                        <ul class="menu-lista">
                            <li class="menu-lista-item" onclick="window.location.href='questoes';"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span> Questões</li>
                            <li class="menu-lista-item" onclick="window.location.href='ranking';"><i class="fa fa-trophy" aria-hidden="true"></i> Ranking</li>
                            <li class="menu-lista-item li-active" onclick="window.location.href='desempenho';"><i class="fa fa-pie-chart" aria-hidden="true"></i> Desempenho</li>
                            <li class="menu-lista-item" onclick="window.location.href='filtro';"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filtro</li>
                            <li class="menu-lista-item" onclick="window.location.href='simulado';"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Simulado</li>
                            <li class="menu-lista-item" onclick="window.location.href='usuario';"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Meus Dados</li>
                            <?php include_once("add/notif_mob.php"); ?>
                            <li class="menu-lista-item" id="moreLink"><span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span> Links Úteis <span class="glyphicon glyphicon-plus" id="iconPlus"></span></li>
                            <div id="linksUteis" style="display: none;">
                                <?php include "linksUteisMobile.add"; ?>
                            </div>
                            <li class="menu-lista-item" onclick="logOut()"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Sair</li>
                        </ul>
                    </div>
                </div>
                <form method="GET" action="" name="logOutForm" style="display: none">
                    <input value="1" name="slogout" style="display: none;">
                </form>
            </div>
        <?php }else{ ?>
            <nav class="navbar navbar-inverse navbar-default" style="border-radius: 0px" id="navbar-desktop">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="usuario" title="Dados do Usuário" style="font-family: Montserrant,sans-serif;font-weight: 600;color: #ecf0f1;"><span class="glyphicon glyphicon-user"></span>&nbsp; Olá, <?php echo $_SESSION['usuario']['nome']; ?>.</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="questoes"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>&nbsp; Questões</a></li>
                        <li><a href="ranking"><i class="fa fa-trophy" aria-hidden="true"></i>&nbsp; Ranking</a></li>
                        <li class="active"><a href="desempenho"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp; Desempenho <span class="sr-only">(current)</span></a></li>
                        <li><a href="filtro"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>&nbsp; Filtro</a></li>
                        <li><a href="simulado"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>&nbsp; Simulado</a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Links Úteis <span class="caret"></span></a>
                            <?php include "linksUteis.add"; ?>
                        </li>
                        <?php include_once("add/notif_desktp.php"); ?>
                    </ul>
                    <form class="navbar-form navbar-right" method="GET" action="">
                        <input value="1" name="slogout" style="display: none;">
                        <button type="submit" class="btn btn-primary btn-danger">Logout</button>
                    </form>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        <?php }; ?><!--//FIM DO TESTE IS_MOBILE-->
        <div class="container-fluid" style="margin-top: 90px;">
        <!--COMEÇA GRAFICO -->
              <?php if(isset($dados[0])){?>
                    <div class="row">
                        <div role="main" class="col-md-3 col-md-push-3 col-xs-12">
                            <canvas id="QuestoesMes"></canvas>
                        </div>
                        <div role="main" class="col-md-3 col-md-push-3 col-xs-12">
                            <canvas id="QuestoesAno"></canvas>
                        </div>
                    </div>
              <?php }; ?>
              <?php if(!isset($dados[0])){?>
              <div class="alert alert-danger" style="text-align: center; font-size: 25px; width: 50%; position: relative; margin-left: 25%; margin-top: 5%;" role="alert"><strong>Por enquanto não há dados suficintes para gerar os gráficos. Resolva algumas questões e volte.</strong></div>
              <?php }; ?>
        <?php if (!isset($dados[0])) {echo "<div style=\"min-height: 47.82%;\"></div>";} ?>
        <?php if(isset($dados[0])){?>
            <br><br><br>
            <div class="row">
                <div role="main" class="col-md-7 col-md-push-2 col-xs-12">
                    <canvas id="AreasAno"></canvas>
                </div>
            </div>
            <br><br><br><br><br>
        <?php };?>
        </div> <!--Div do class="container-fluid-->
        <!--FOOTER INICIA AQUI-->
        <footer>
            <div class="container">
                <div class="row">
                <div class="col-md-4 col-sm-6 footerleft ">
                    <div class="logofooter"> Créditos de Imagem</div>
                    <div>Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/vectors-market" title="Vectors Market">Vectors Market</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/popcorns-arts" title="Popcorns Arts">Popcorns Arts</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/flat-icons" title="Flat Icons">Flat Icons</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>
                    are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>                
                </div>
                <div class="col-md-2 col-sm-6 paddingtop-bottom">
                    <h6 class="heading7">LINKS ÚTEIS</h6>
                    <ul class="footer-ul">
                        <?php include "footerLinks.add"; ?>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-6 paddingtop-bottom">
                    <div class="fb-page" data-href="https://www.facebook.com/facebook" data-tabs="timeline" data-height="300" data-small-header="false" style="margin-bottom:15px;" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                    <div class="fb-xfbml-parse-ignore">
                        <?php include "footerSites.add"; ?>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </footer>
            <!--footer start from here-->

            <div class="copyright">
            <div class="container">
                <div class="col-md-6">
                <p>© 2017</p>
                </div>
                <!--div class="col-md-6">
                <ul class="bottom_ul">
                    <li><a href="#">webenlance.com</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Faq's</a></li>
                    <li><a href="#">Contact us</a></li>
                    <li><a href="#">Site Map</a></li>
                </ul>
                </div-->
            </div>
            </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.min.js"></script>
        <script>
            var ctxMes = document.getElementById("QuestoesMes").getContext("2d");
            var chartGraphMes = new Chart(ctxMes, {
                type: 'doughnut',
                data:{
                    labels: [
                        "Certo",
                        "Errado"
                    ],
                    datasets: [
                        {
                            data: [<?php echo $certas_mes.",".$erradas_mes; ?>],
                            backgroundColor: [
                                "#2ecc71",
                                "#e74c3c"
                            ],
                            hoverBackgroundColor: [
                                "#27ae60",
                                "#c0392b"
                            ]
                        }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Respostas no mês (%)',
                        fontFamily: "'Arial', 'sans-serfi'",
                        fontSize: 15
                    },
                    animation: {
                        easing: 'easeOutBounce'
                    },
                    legend: {
                        display: true,
                    }
                }
            });
            
            var ctxAno = document.getElementById("QuestoesAno").getContext("2d");
            var chartGraphAno = new Chart(ctxAno, {
                type: 'doughnut',
                data:{
                    labels: [
                        "Certo",
                        "Errado"
                    ],
                    datasets: [
                        {
                            data: [<?php echo $certas_total.",".$erradas_total; ?>],
                            backgroundColor: [
                                "#3498db",
                                "#e67e22"
                            ],
                            hoverBackgroundColor: [
                                "#2980b9",
                                "#d35400"
                            ]
                        }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Respostas no ano (%)',
                        fontFamily: "'Arial', 'sans-serfi'",
                        fontSize: 15
                    },
                    animation: {
                        easing: 'easeOutBounce'
                    }
                }
            });

            var ctxAreas = document.getElementById("AreasAno").getContext("2d");
            var chartGraphAreas = new Chart(ctxAreas, {
                type: 'horizontalBar',
                data:{
                labels: ["Matemática e suas Tecnologias", "Ciências da Natureza e suas Tecnologias", "Ciências Humanas e suas Tecnologias","Linguagens, Códigos e suas Tecnologias"],
                    datasets: [{
                        label: "Certo",
                        backgroundColor: "#f1c40f",
                        data: [<?php echo $certas_area1.",".$certas_area2.",".$certas_area3.",".$certas_area4;?>]
                    }, {
                        label: "Errado",
                        backgroundColor: "#e74c3c",
                        data: [<?php echo $erradas_area1.",".$erradas_area2.",".$erradas_area3.",".$erradas_area4;?>]
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Desempenho anual em cada área (%)',
                        fontFamily: "'Arial', 'sans-serfi'",
                        fontSize: 15
                    },
                    barValueSpacing: 20,
                    scales: {
                        xAxes: [{
                                ticks: {
                                min: 0,
                                }
                            }]
                    },
                    legend: {
                        display: false,
                    },
                    animation: {
                        easing: 'easeOutBounce'
                    }
                }
            });
        </script>
        <script src="js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <?php
            if($is_mobile){
                echo "<script type='text/javascript' src='js/menu.js'></script>";
            }
        ?>
    </body>
</html>
