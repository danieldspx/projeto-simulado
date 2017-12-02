<?php
    require_once 'add/HeaderSession.php';
    $logout = $_GET['slogout'];
    if($logout == '1'){
        $_SESSION = array();
        session_destroy();
        header("Location: principal");
    }
    if(isset($_SESSION['usuario'])){
        include_once 'private_html_protected/config.php';
        include_once 'private_html_protected/connection.php';
        include_once 'private_html_protected/database.php';
        $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
        if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
            header("Location: LoginSimultaneo.php");
        }
    }
    
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <link rel="stylesheet" href="css/reset.css">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <link href="css/footer.css" rel="stylesheet" type="text/css">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Simulado Cedaf</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="sortcut icon" href="img/checked.png" type="image/png" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/index.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/index.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body id="bodyElement">
    <div class="container-fluid">
        <header class="row">
        </header>
        <div class="row">
            <div role="main" class="col-md-5 col-xs-12">
                <canvas id="QuestoesMes"></canvas>
            </div>
            <div role="main" class="col-md-5 col-xs-12">
                <canvas id="QuestoesAno"></canvas>
            </div>
            <div role="main" class="col-md-7 col-xs-12">
                <canvas id="AreasAno"></canvas>
            </div>
        </div>
        <footer class="row" style="margin-bottom: 0px;">
            <div class="container">
            <div class="row">
            <div class="col-md-4 col-sm-6 footerleft ">
                <div class="logofooter"> Créditos de Imagem</div>
                <div>Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/vectors-market" title="Vectors Market">Vectors Market</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/popcorns-arts" title="Popcorns Arts">Popcorns Arts</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/flat-icons" title="Flat Icons">Flat Icons</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/pixel-buddha" title="Pixel Buddha">Pixel Buddha</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/alfredo-hernandez" title="Alfredo Hernandez">Alfredo Hernandez</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>
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
                        data: [70, 30],
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
                        data: [70, 30],
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
                    data: [5, 4, 5, 6]
                }, {
                    label: "Errado",
                    backgroundColor: "#e74c3c",
                    data: [4, 5, 7, 8]
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
    </body>
</html>
