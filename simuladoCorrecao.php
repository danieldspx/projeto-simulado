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
    include_once 'private_html_protected/config.php';
    include_once 'private_html_protected/connection.php';
    include_once 'private_html_protected/database.php';
    date_default_timezone_set('America/Sao_Paulo');
    
    $dataSimulado = DBSearch("data_simulado","WHERE status = 1 LIMIT 1","dataInicioP,dataInicioS,dataFimP,dataFimS");

    $dateNow = new DateTime(date('Y')."-".date('m')."-".date('d')." ".date('H').":".date('i').":".date('s')); // Data Atual

    $dataInicio[1] = new DateTime($dataSimulado[0]['dataInicioP']); // Data do inicio do 1 simulado
    $dataFim[1] = new DateTime($dataSimulado[0]['dataFimP']); // Data do termino do 1 simulado

    $dataInicio[2] = new DateTime($dataSimulado[0]['dataInicioS']); // Data do inicio do 2 simulado
    $dataFim[2] = new DateTime($dataSimulado[0]['dataFimS']); // Data do termino do 1 simulado

    if(($dateNow >= $dataInicio[1] && $dateNow <= $dataFim[1]) || ($dateNow >= $dataInicio[2] && $dateNow <= $dataFim[2])){ // Se estiver dentro do periodo de algum simulado
        header("Location: finalizado");
    }
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        header("Location: LoginSimultaneo.php");
    }
    unset($resposta);
?>
<html>
    <head>
        <meta name="robots" content="noindex">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href="css/footer.css" rel="stylesheet" type="text/css">
        
        <title>Ranking</title>
        <meta charset="UTF-8">
        <link rel="sortcut icon" href="img/favicon/trophy.png" type="image/png" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link href="css/correcaoSimulado.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <link href="css/multiple.css" rel="stylesheet">
        <script src="js/multiple.min.js"></script>
        <?php include_once("add/analyticstracking.php") ?>
    </head>
    <body style="background-color: #fafafa">

        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <nav class="navbar navbar-inverse navbar-default" style="border-radius: 0px">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" title="Dados do Usuário" href="usuario" style="font-family: Montserrant,sans-serif;font-weight: 600;color: #ecf0f1;"><span class="glyphicon glyphicon-user"></span>&nbsp; Olá, <?php echo $_SESSION['usuario']['nome']; ?>.</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="questoes"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>&nbsp; Questões</a></li>
                    <li><a href="ranking"><i class="fa fa-trophy" aria-hidden="true"></i>&nbsp; Ranking <span class="sr-only">(current)</span></a></li>
                    <li><a href="desempenho"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp; Desempenho</a></li>
                    <li><a href="filtro"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>&nbsp; Filtro</a></li>
                    <li  class="active"><a href="simulado"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>&nbsp; Simulado</a></li>
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Links Úteis <span class="caret"></span></a>
                        <?php include "linksUteis.add"; ?>
                    </li>
                </ul>
                <form class="navbar-form navbar-right" method="GET" action="">
                    <input value="1" name="slogout" style="display: none;">
                    <button type="submit" class="btn btn-primary btn-danger">Logout</button>
                </form>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
            </nav>

        <?php
                if(isset($_SESSION['Error'])){
                    echo "<h2 class='sessao1'>".$_SESSION['Error']."</h2>";
                    unset($_SESSION['Error']);
                }
                if(isset($_SESSION['Success'])){
                    echo "<h2 class='sessao2'>".$_SESSION['Success']."</h2>";
                    unset($_SESSION['Success']);
                }
                $pesquisa = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"questoes_marcadas,pontos");
                if($dateNow>$dataFim[1] && $dateNow<$dataInicio[2]){
                    $fimQuestoes = 90; //DE ONDE TERMINA O NUMERO DAS QUESTÕES QUE ESTAMOS BUSCANDO
                } else if ($dateNow > $dataFim[2]){
                    $fimQuestoes = 180; //DE ONDE TERMINA O NUMERO DAS QUESTÕES QUE ESTAMOS BUSCANDO
                }
                $fimQuestoes = 90;
                $id_simulado = DBSearch("data_simulado","WHERE status = 1 LIMIT 1","id_simulado");
                $respostaCorreta = DBSearch("questoes_simulado","WHERE numero >= 1 AND numero <= $fimQuestoes AND id_simulado = '".$id_simulado[0]['id_simulado']."' ORDER BY numero ASC;","alternativa_correta,idquestao");
                $string = $pesquisa[0]["questoes_marcadas"];
                for($i=1;$i<=$fimQuestoes;$i++){
                	$separador = "-".$i."_";
                	if(strrpos($string,$separador) !== false){
                		$splited = substr($string,strrpos($string,$separador),strlen($separador)+1);
                		$letra = substr($splited,-1,1);
                		$respostas[$i]=$letra;
                	} else{
                		$respostas[$i]='-';
                	}
                }
            ?>
            <span id="myPoints"><i class="fa fa-trophy" aria-hidden="true"></i> Sua pontuação: <span id="pontuacaoAtual"><?php echo $pesquisa[0]['pontos']; ?></span></span>
            <?php if(empty($pesquisa)){?>
                <div class="alert alert-danger" style="width: 40%; position: relative; left:30%; font-size: 15pt; text-align: center; margin-top: 50px;" role="alert"><strong>Você não realizou o simulado.</strong></div>
            <?php };?>
        <div class="row">
            <div role="main" class="col-md-7 col-md-push-2 col-xs-12">
                <canvas id="AreasDesempenho"></canvas>
            </div>
        </div>
        <div class="item"><p class="textListHeader col-md-3"><sup class="classification">Número</sup></p><p class="textListHeader col-md-3" style='text-align: center;'>&nbsp; Gabarito</p><p class="textListHeader pontuacao col-md-3" style='text-align: center;'>Sua resposta</p><p class="textListHeader pontuacao col-md-3">Status</p></div>
            <?php if(!empty($respostas)){
                $pontuacao = 0;
                for($i=1; $i<=$fimQuestoes; $i++){
                    $j = $i-1;
                    switch($respostaCorreta[$j]['alternativa_correta']){ //Transforma número p/ letra
                            case 1:
                                $respostaCorreta[$j]['alternativa_correta'] = 'A';
                                break;
                            case 2:
                                $respostaCorreta[$j]['alternativa_correta'] = 'B';
                                break;
                            case 3:
                                $respostaCorreta[$j]['alternativa_correta'] = 'C';
                                break;
                            case 4:
                                $respostaCorreta[$j]['alternativa_correta'] = 'D';
                                break;
                            case 5:
                                $respostaCorreta[$j]['alternativa_correta'] = 'E';
                                break;
                    }
                    if($respostas[$i]==$respostaCorreta[$j]['alternativa_correta']){
                        $imagem = "img/checked.svg";
                        $pontuacao++;
                    } else{
                        $imagem = "img/multiply.svg";
                    }
                    echo "<div class=\"item\"><p class=\"textList col-md-3\"><sup class=\"classification\">Nº ".$i."&nbsp;</sup></p><p class=\"textList col-md-3\" style='text-align: center;'>&nbsp; ".$respostaCorreta[$j]['alternativa_correta']."</p><p class=\"textList pontuacao col-md-3\" style='text-align: center;'>".$respostas[$i]."</p><p class=\"textList pontuacao col-md-3\"><img src='".$imagem."' width='40px' heigth='40px'></p></div>";
                }
                $pontuacao *= 5; //5 pontos em cada questão
                echo "<script> setTimeout(function(){ document.getElementById(\"pontuacaoAtual\").innerHTML = \"".$pontuacao."/".($fimQuestoes*5)." ou ".round(($pontuacao*100)/($fimQuestoes*5),1)."%\";},0); </script>";
                $pontosDB = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"pontos");
                if($pontosDB<$pontuacao){
                    $queryUpdatePontos = "UPDATE dados_usuario_simulado SET pontos = ".$pontuacao." WHERE usuarios_id = ".$_SESSION['usuario']['id'].";";
                    DBExecute($queryUpdatePontos);
                }    
            }?>
        <br><br><br><br><br><br><br><br>
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

            var ctxAreas = document.getElementById("AreasDesempenho").getContext("2d");
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
        <script>
        var multiple = new Multiple({
            selector: '.item',
            background: 'linear-gradient(#273463, #8B4256)',
            opacity: true
        });
        </script>
    </body>
</html>
