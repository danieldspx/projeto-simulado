<?php
    require_once 'add/HeaderSession.php';
    require_once 'private_html_protected/config.php';
    require_once 'private_html_protected/connection.php';
    require_once 'private_html_protected/database.php';
    require_once 'mobile_detect/Mobile_Detect.php';
    date_default_timezone_set('America/Sao_Paulo');
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

    if($_SESSION['usuario']['nivelAcessoId'] == '4' || $_SESSION['usuario']['nivelAcessoId'] == 4){
        header("Location: 403.shtml");
    }
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        header("Location: LoginSimultaneo.php");
    }
    unset($resposta);
    $id_simulado = DBSearch("data_simulado","WHERE status = 1","id_simulado");
    $resposta = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id']." AND id_simulado = '".$id_simulado[0]['id_simulado']."' LIMIT 1","status,usuarios_id,prova");

    $dataSimulado = DBSearch("data_simulado","WHERE status = 1 LIMIT 1","dataInicioP,dataInicioS,dataFimP,dataFimS");

    $dateNow = new DateTime(date('Y')."-".date('m')."-".date('d')." ".date('H').":".date('i').":".date('s')); // Data Atual

    $dataInicio[1] = new DateTime($dataSimulado[0]['dataInicioP']); // Data do inicio do 1 simulado
    $dataFim[1] = new DateTime($dataSimulado[0]['dataFimP']); // Data do termino do 1 simulado

    $dataInicio[2] = new DateTime($dataSimulado[0]['dataInicioS']); // Data do inicio do 2 simulado
    $dataFim[2] = new DateTime($dataSimulado[0]['dataFimS']); // Data do termino do 1 simulado

    if($dateNow >= $dataInicio[1] && $dateNow <= $dataFim[1]){ // Se estiver entre o periodo do 1º Simulado
        if($resposta[0]['status']==0 && isset($resposta[0]) && $resposta[0]['usuarios_id']==$_SESSION['usuario']['id'] && $resposta[0]['prova']==1){
            header("Location: finalizado");
        }
        $prova = 1;
    } else if ($dateNow >= $dataInicio[2] && $dateNow <= $dataFim[2]){ // Se estiver entre o periodo do 2º Simulado
        if($resposta[0]['status']==0 && isset($resposta[0]) && $resposta[0]['usuarios_id']==$_SESSION['usuario']['id'] && $resposta[0]['prova']==2){
            header("Location: finalizado");
        }
        $prova = 2;
    } else {
        if($resposta[0]['status']==0 && isset($resposta[0]) && $resposta[0]['usuarios_id']==$_SESSION['usuario']['id']){
            header("Location: correcao");
        } else {//Fora dos dois periodos
            header("Location: unaSimulado.shtml");
        }
        if(isset($prova)){
            unset($prova);
        }
    }
    //header("Location: correcao"); //REDIRECIONAMENTO DEFINITIVO PARA A PAGINA DE CORREÇÃO
    if(empty($_GET['pagina'])){
        $pagina=1;
    } else {
        $pagina = $_GET['pagina'];
    }
    $detect = new Mobile_Detect;
    $is_mobile=$detect->isMobile();
?>
<html>
    <head>
        <meta name="robots" content="noindex">
        <title>Simulado</title>
        <link rel="stylesheet" href="css/animate.css">
        <link rel="sortcut icon" id="favicon" href="img/favicon/exam.png" type="image/png"/>
        <link href="https://fonts.googleapis.com/css?family=Francois+One" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/simuladoQuestoes.js"></script>
        <script language='Javascript'>
        <?php
                if(isset($prova)){
                    $respostaTime = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id']." AND prova=".$prova." LIMIT 1","hora_inicio,data_inicio");
                } else {
                    $respostaTime = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id']." LIMIT 1","hora_inicio,data_inicio");
                }
                $_SESSION['usuario']['condicao'] = intval(empty($respostaTime[0]["hora_inicio"]));//Já começou a fazer a prova?
                if($_SESSION['usuario']['condicao']==1){ //Não começou a prova
                    echo "var horas = 4;";
                    echo "var minutos = 30;";
                    echo "var segundos = 0;";
                } else {
                    $data_explode = explode("/", $respostaTime[0]["data_inicio"]);
                    $hora_explode = explode(":", $respostaTime[0]["hora_inicio"]);

                    $horaAnterior = $hora_explode[0];
                    $minutoAnterior = $hora_explode[1];
                    $segundoAnterior = $hora_explode[2];
                    $diaAnterior = $data_explode[0];
                    $mesAnterior = $data_explode[1];
                    $anoAnterior = $data_explode[2];

                    $horaAtual = date("H");
                    $minutoAtual = date("i");
                    $segundoAtual = date("s");
                    $diaAtual = date("d");
                    $mesAtual = date("m");
                    $anoAtual = date("Y");
                    $strStart = $mesAnterior."/".$diaAnterior."/".$anoAnterior." ".$horaAnterior.":".$minutoAnterior.":".$segundoAnterior;
                    $strEnd = $mesAtual."/".$diaAtual."/".$anoAtual." ".$horaAtual.":".$minutoAtual.":".$segundoAtual;
                    $dteEnd = new DateTime($strEnd);
                    $dteStart = new DateTime($strStart);
                    $dteDiff = $dteStart->diff($dteEnd);
                    $permission = true;
                    $mudaPagina = false;
                    echo var_dump($strStart);
                    echo var_dump($strEnd);
                    echo var_dump($dteDiff);
                    if(intval($dteDiff->format("%Y"))==0 && intval($dteDiff->format("%M"))==0 && intval($dteDiff->format("%D"))==0){
                        $thora = intval($dteDiff->format("%H"));
                        $tminuto = intval($dteDiff->format("%I"));
                        $tsegundo = intval($dteDiff->format("%S"));
                        $tTotal = $thora*3600 + $tminuto*60 + $tsegundo;
                        if($tTotal>=16200){
                            $permission = false;
                        }
                    } else {
                        $permission = false;
                    }
                    if($permission){
                        $thora = 4 - $thora;
                        $tminuto = 29 - $tminuto;
                        $tsegundo = 59 - $tsegundo;
                        echo "var horas = ".$thora.";";
                        echo "var minutos = ".$tminuto.";";
                        echo "var segundos = ".$tsegundo.";";
                    } else {//FINAL DO SIMULADO
                        echo "setTimeout(finalizaSimulado,0);";
                    }
                }
            ?>
        </script>
        <span id="variaveisJS"></span>
        <script language='Javascript'>
            var condicaoP = <?php echo intval($_SESSION['usuario']['condicao']); ?>;
            function buscarQ(){
                var page = "busca_questaosimulado.php";
                document.getElementById("campoQuestoes").style.display = "inline-block";
                $.ajax
                ({
                    type: 'POST',
                    dataType: 'html',
                    url: page,
                    data: {pagina: <?php echo $pagina;?>},
                           beforeSend: function () {
                               changeVisibility();
                               $("#fieldT").html("<div class=\"sk-cube-grid\"> <div class=\"sk-cube sk-cube1\"></div> <div class=\"sk-cube sk-cube2\"></div> <div class=\"sk-cube sk-cube3\"></div> <div class=\"sk-cube sk-cube4\"></div> <div class=\"sk-cube sk-cube5\"></div> <div class=\"sk-cube sk-cube6\"></div> <div class=\"sk-cube sk-cube7\"></div> <div class=\"sk-cube sk-cube8\"></div> <div class=\"sk-cube sk-cube9\"></div> </div>");
                               document.body.style.backgroundColor = "#080e24";
                               document.body.style.backgroundImage = "url(http://neography.com/experiment/circles/solarsystem/bg.jpg)";
                               document.body.style.backgroundRepeat = "repeat";
                           },
                           success: function (msg)
                           {
                               if(condicaoP == 1){
                                   contagem_tempo();
                                   $("#fieldT").html(msg);
                                   setTime();
                               } else {
                                   contagem_tempo();
                                   $("#fieldT").html(msg);
                                   clockAssync();
                               }
                           }
                          });
                }
                 <?php
                 if($_SESSION['usuario']['condicao']!=1){
                     echo "$(document).ready(buscarQ);";
                 } else{
                     echo "setTimeout(function(){document.getElementById('bodyTag').style.overflowX = 'hidden';},0)";
                 }
                 ?>
        </script>
        <link rel="stylesheet" href="css/simuladoQuestoes.css">
        <style>
            <?php if($_SESSION['usuario']['simuladoUse']!==1){ ?>
            @keyframes light {
                from {
                    fill: #4db858;
                }
                to {
                    fill: #bacbe4;
                }
            }
            @keyframes rotate {
                from {
                    -webkit-transform: rotate(0deg);
                    -moz-transform: rotate(0deg);
                    -ms-transform: rotate(0deg);
                    -o-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                to {
                    -webkit-transform: rotate(30deg);
                    -moz-transform: rotate(30deg);
                    -ms-transform: rotate(30deg);
                    -o-transform: rotate(30deg);
                    transform: rotate(30deg);
                }
            }
            @keyframes head-nod {
                from {
                    transform: rotate(-20deg);
                }
                to {
                    transform: rotate(10deg);
                }
            }
            @keyframes background-scroll {
                from {
                    background-position: 0vw;
                }
                to {
                    background-position: -100vw;
                }
            }
            @keyframes float {
                0% {
                    transform: translate(0px, 0px) rotate(0deg);
                }
                50% {
                    transform: translate(20px, 80px) rotate(0deg);
                }
                100% {
                    transform: translate(50px, -20px) rotate(5deg);
                }
            }
            body {
                background: #03293a;
                padding: 0;
                margin: 0;
                height: 100vh;
                width: 100vw;
            }

            .box {
                width: 100px;
                height: 100px;
                background: white;
                transform-origin: 50% 0%;
                animation: rotate 2s infinite alternate ease-in-out;
            }

            .spaceship {
                width: 20vw;
                margin-left: 50%;
                top: 10vh;
                left: -10vw;
                position: absolute;
                animation: float 2.8s infinite alternate ease-in-out;
                transform-origin: -10% 60%;
                z-index: 20;
            }

            .head {
                transform-origin: 62px 52px 0;
                animation: head-nod 2s infinite alternate ease-in-out;
            }

            .lantern_1 circle:nth-of-type(2) {
                animation-direction: alternate;
                animation-iteration-count: infinite;
                animation-name: light;
                animation-timing-function: ease;
                animation-duration: 0.2s;
            }

            .lantern_2 circle:nth-of-type(2) {
                animation-direction: alternate;
                animation-iteration-count: infinite;
                animation-name: light;
                animation-timing-function: ease;
                animation-duration: 0.4s;
            }

            .lantern_3 circle:nth-of-type(2) {
                animation-direction: alternate;
                animation-iteration-count: infinite;
                animation-name: light;
                animation-timing-function: ease;
                animation-duration: 0.6s;
            }

            .lantern_4 circle:nth-of-type(2) {
                animation-direction: alternate;
                animation-iteration-count: infinite;
                animation-name: light;
                animation-timing-function: ease;
                animation-duration: 0.8s;
            }

            .lantern_5 circle:nth-of-type(2) {
                animation-direction: alternate;
                animation-iteration-count: infinite;
                animation-name: light;
                animation-timing-function: ease;
                animation-duration: 1s;
            }

            .lantern_6 circle:nth-of-type(2) {
                animation-direction: alternate;
                animation-iteration-count: infinite;
                animation-name: light;
                animation-timing-function: ease;
                animation-duration: 1.2s;
            }

            .lantern_7 circle:nth-of-type(2) {
                animation-direction: alternate;
                animation-iteration-count: infinite;
                animation-name: light;
                animation-timing-function: ease;
                animation-duration: 1.4s;
            }

            .leg {
                fill: #65998A;
                transform-origin: top;
                animation-duration: 2s;
            }
            .leg__1 {
                -webkit-transform-origin: 30px 80px;
                -moz-transform-origin: 30px 80px;
                -ms-transform-origin: 30px 80px;
                -o-transform-origin: 30px 80px;
                transform-origin: 30px 80px;
                animation-duration: 2s;
            }
            .leg__1__tip {
                -webkit-transform-origin: 20px 99px 0;
                -moz-transform-origin: 20px 99px 0;
                -ms-transform-origin: 20px 99px 0;
                -o-transform-origin: 20px 99px 0;
                transform-origin: 20px 99px 0;
                animation-duration: 2.5s;
            }
            .leg__2 {
                -webkit-transform-origin: 39px 87px;
                -moz-transform-origin: 39px 87px;
                -ms-transform-origin: 39px 87px;
                -o-transform-origin: 39px 87px;
                transform-origin: 39px 87px;
                animation-delay: .5s;
                animation-duration: 2s;
            }
            .leg__2__tip {
                -webkit-transform-origin: 29px 104px 0;
                -moz-transform-origin: 29px 104px 0;
                -ms-transform-origin: 29px 104px 0;
                -o-transform-origin: 29px 104px 0;
                transform-origin: 29px 104px 0;
                animation-duration: 1.5s;
            }
            .leg__3 {
                -webkit-transform-origin: 65px 89px 0;
                -moz-transform-origin: 65px 89px 0;
                -ms-transform-origin: 65px 89px 0;
                -o-transform-origin: 65px 89px 0;
                transform-origin: 65px 89px 0;
                animation-delay: .4s;
                animation-duration: 2.4s;
            }
            .leg__3__tip {
                -webkit-transform-origin: 71px 102px 0;
                -moz-transform-origin: 71px 102px 0;
                -ms-transform-origin: 71px 102px 0;
                -o-transform-origin: 71px 102px 0;
                transform-origin: 71px 102px 0;
                animation-duration: 1.5s;
            }
            .leg__4 {
                -webkit-transform-origin: 80px 80px;
                -moz-transform-origin: 80px 80px;
                -ms-transform-origin: 80px 80px;
                -o-transform-origin: 80px 80px;
                transform-origin: 80px 80px;
                animation-duraton: 8s;
            }
            .leg__4__tip {
                -webkit-transform-origin: 85px 99px;
                -moz-transform-origin: 85px 99px;
                -ms-transform-origin: 85px 99px;
                -o-transform-origin: 85px 99px;
                transform-origin: 85px 99px;
                animation-duration: 1.5s;
            }
            .leg--top-center {
                transform-origin: 30% 0%;
            }
            .leg--top-right {
                transform-origin: top right;
            }
            .leg--top-left {
                transform-origin: top left;
            }
            .leg--moving {
                animation-direction: alternate;
                animation-iteration-count: infinite;
                animation-name: rotate;
                animation-timing-function: ease-in-out;
            }
            .leg--moving-tip {
                animation-direction: alternate;
                animation-iteration-count: infinite;
                animation-name: rotate;
                animation-timing-function: ease;
            }

            .cloud {
                position: fixed;
                top: 10vh;
                left: 0;
                width: 100vw;
                padding: 0;
                margin: 0;
                z-index: 10;
            }
            .cloud li:nth-of-type(1) {
                background-image: url("http://krystalcampioni.com/talk/clouds.svg");
                width: 100vw;
                position: absolute;
                left: 0;
                top: -10vh;
                animation-direction: normal;
                animation-iteration-count: infinite;
                animation-name: background-scroll;
                animation-timing-function: linear;
                animation-duration: 10s;
                opacity: 0.25;
                height: 100vh;
                background-repeat: repeat-x;
            }
            .cloud li:nth-of-type(2) {
                background-image: url("http://krystalcampioni.com/talk/clouds.svg");
                width: 100vw;
                position: absolute;
                left: 0;
                top: -20vh;
                animation-direction: normal;
                animation-iteration-count: infinite;
                animation-name: background-scroll;
                animation-timing-function: linear;
                animation-duration: 20s;
                opacity: 0.5;
                height: 100vh;
                background-repeat: repeat-x;
            }
            .cloud li:nth-of-type(3) {
                background-image: url("http://krystalcampioni.com/talk/clouds.svg");
                width: 100vw;
                position: absolute;
                left: 0;
                top: -30vh;
                animation-direction: normal;
                animation-iteration-count: infinite;
                animation-name: background-scroll;
                animation-timing-function: linear;
                animation-duration: 30s;
                opacity: 0.75;
                height: 100vh;
                background-repeat: repeat-x;
            }

            .city {
                background-image: url("http://krystalcampioni.com/talk/city.svg");
                background-size: 100%;
                width: 99vw;
                position: fixed;
                left: 0;
                bottom: -30vh;
                animation-direction: normal;
                animation-iteration-count: infinite;
                animation-name: background-scroll;
                animation-timing-function: linear;
                animation-duration: 9.5s;
                height: 100vh;
                background-repeat: repeat-x;
                opacity: .5;
            }
            .city::after {
                content: " ";
                width: 100%;
                height: 32vh;
                position: fixed;
                bottom: 0;
                left: 0;
                background: #07202c;
                z-index: 1;
                opacity: 1;
            }

            .st1 {
                fill: #2E818E;
            }

            .st2 {
                opacity: 0.5;
                fill: #19474C;
            }

            .st3 {
                fill: #FFFFFF;
            }

            .st4 {
                fill: #1C4420;
            }

            .st5 {
                fill: #389B47;
            }

            .st6 {
                opacity: 0.5;
                fill: #2E818E;
            }

            .st7 {
                fill: #4DB858;
            }

            .st8 {
                fill: #ADD799;
            }

            .st9 {
                fill: #D9EBCE;
            }

            <?php }; ?>
        </style>
        <link rel="stylesheet" href="css/animationStudent.css">
        <?php
            if($is_mobile){
                echo "<link href='css/styleMenu.css' rel='stylesheet'>";
                echo "<link href='css/animateMenu.css' rel='stylesheet'>";   
            }
        ?>
        <?php include_once("add/analyticstracking.php") ?>
    </head>
    <body id="bodyTag">
        <div id="navbar-mobile" style="display: none;">
            <?php if($is_mobile){ ?>
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
                    <div id="relogio-menu">
                        <span class="glyphicon glyphicon-time" aria-hidden="true"></span>&nbsp; Tempo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span id="horas">--</span>
                        <span>:</span>
                        <span id="minutos">--</span>
                        <span>:</span>
                        <span id="segundos">--</span>
                    </div>
                </div>
                <div id="menu-active" class="animated first" style="display: none;">
                    <ul class="menu-lista">
                        <?php
                            if($_SESSION['usuario']['condicao']==1){
                                echo "<li class=\"menu-lista-item\" onclick=\"disappear();buscarQ();setTime();mobile_change();\" id=\"btnStart\"><span class=\"glyphicon glyphicon-play\" aria-hidden=\"true\"></span> Começar!</li>";
                            } else {
                                echo "<li id=\"endSimulado\" class=\"menu-lista-item\" onclick=\"verifyQuestions()\"><i class=\"fa fa-graduation-cap\" aria-hidden=\"true\"></i> Finalizar Simulado!</li>";
                            }
                        ?>
                        <li class="menu-lista-item" onclick="logOut()"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Sair</li>
                    </ul>
                </div>
            </div>
            <?php }; ?>
            <form method="GET" action="" name="logOutForm" style="display: none">
                <input value="1" name="slogout" style="display: none;">
            </form>
        </div>
        <nav class="navbar navbar-default" id="navbar" style="border-radius: 0px; z-index: 1000;">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" title="Dados do Usuário" style="font-family: Montserrant,sans-serif;font-weight: 600;color: #2c3e50;"><span class="glyphicon glyphicon-user"></span>&nbsp; Boa prova, <?php echo $_SESSION['usuario']['nome']; ?>.</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php if(!$is_mobile){ ?>
                        <li class="active" style="font-weight: 900;"><a><span class="glyphicon glyphicon-time" aria-hidden="true"></span>&nbsp; Tempo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span id="horas">--</span>
                            <span>:</span>
                            <span id="minutos">--</span>
                            <span>:</span>
                            <span id="segundos">--</span>
                            <span class="sr-only">(current)</span></a></li>
                        <?php }; ?>
                        <li id="simuladoOpcoes">
                            <?php
                                if(!$is_mobile){
                                    if($_SESSION['usuario']['condicao']==1){
                                        echo "<a onclick=\"disappear();buscarQ();setTime();\" id=\"btnStart\"><span class=\"glyphicon glyphicon-play\"></span> Começar!</a>";
                                    } else {
                                        echo "<a id=\"endSimulado\" onclick=\"verifyQuestions()\"><i class=\"fa fa-graduation-cap\" aria-hidden=\"true\"></i> Finalizar Simulado!</a>";
                                    }
                                }
                            ?>
                        </li>
                    </ul>
                    <form class="navbar-form navbar-right" method="GET" action="">
                        <input value="1" name="slogout" style="display: none;">
                        <button type="submit" class="btn btn-primary btn-danger">Logout</button>
                    </form>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container-fluid" id="finalSimulado" style="overflow: auto;">
            <i class="fa fa-times fa-3x" aria-hidden="true" onclick="closeBox()" style="cursor: pointer;position: fixed; top: 10px; right: 30px; color: rgb(231, 76, 60)"></i>
            <div class="student">
                <svg id="student" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 385">
                    <circle class="circle" style="display:none;" id="BG" opacity="0.2" cx="200.011" cy="208" r="157.657"/>
                    <circle class="circle" style="display:none;" id="dashed" opacity="0.15" fill="none" stroke="#FFFFFF" stroke-width="1.9028" stroke-miterlimit="10" stroke-dasharray="6.5548" cx="200.333" cy="208" r="176.666"/>
                    <g id="stud_whole" style="display:none;">
                        <g id="stud_body">
                            <path fill="#FFFFFF" d="M300,323v-9.401C300,268.536,255.131,232,200.241,232h-1.482 C143.869,232,99,268.536,99,313.599V323H300z"/>
                            <path fill="#C99877" d="M251.2,240.3l-46.504,46.501c-2.868,2.869-7.521,2.869-10.391,0 L147.803,240.3C115.768,254.945,94,282.958,94,315.111V326h211v-10.889C305,282.958,283.232,254.945,251.2,240.3z"/>
                            <path opacity="0.2" d="M251.2,240.3l-46.504,46.501c-2.868,2.869-7.521,2.869-10.391,0 L147.803,240.3C115.768,254.945,94,282.958,94,315.111V326l0,0c0-31.997,21.801-60.071,53.8-74.7l46.503,46.498 c2.869,2.872,7.522,2.872,10.392,0.001l46.505-46.499C283.197,265.93,305,294.003,305,326l0,0v-10.889 C305,282.958,283.232,254.945,251.2,240.3z"/>
                        </g>
                        <g id="stud_head">
                            <path fill="#DDA37C" d="M135.934,194.953c-5.481,0-9.926-5.162-9.926-11.53s4.444-11.529,9.926-11.529"/>
                            <path opacity="0.1" d="M261,190.046c0,36.915-27.145,66.838-61.5,66.838l0,0c-34.356,0-61.5-29.923-61.5-66.838v-23.247 c0-36.916,27.144-66.838,61.5-66.838l0,0c34.355,0,61.5,29.922,61.5,66.838V190.046z"/>
                            <path fill="#FBD099" d="M266,185.046c0,36.915-29.589,66.838-66.5,66.838l0,0c-36.912,0-66.5-29.923-66.5-66.838v-23.247 c0-36.916,29.588-66.838,66.5-66.838l0,0c36.911,0,66.5,29.922,66.5,66.838V185.046z"/>
                            <path fill="#F2C38F" d="M200.777,94.961c-1.348,0-2.754,0.052-4.08,0.13C224.17,105.823,243,131.293,243,161.013v24.819 c0,29.719-18.879,55.189-46.352,65.921c1.326,0.079,2.77,0.131,4.117,0.131c36.91,0,66.234-29.923,66.234-66.838v-23.247 C267,124.883,237.688,94.961,200.777,94.961z"/>
                            <path fill="#DDA37C" d="M275.367,183.423c0-5.789-4.367-10.567-8.367-11.391v-10.233c0-36.916-29.312-66.838-66.223-66.838 c-1.467,0-3.011,0.063-4.451,0.157C231.151,97.365,258,126.349,258,161.799v23.247c0,35.449-26.896,64.434-61.723,66.681 c1.441,0.093,2.913,0.157,4.38,0.157c33.56,0,60.81-24.737,65.473-56.975C271.26,194.463,275.367,189.498,275.367,183.423z"/>
                            <path fill="#99492C" d="M219.818,211.524c0,11.122-9.018,20.14-20.143,20.14c-11.124,0-20.143-9.018-20.143-20.14"/>
                            <path fill="#FFFFFF" d="M219.818,211.285c0,1.709-0.213,3.715-0.613,4.715h-39.158c-0.289-1-0.514-3.003-0.514-4.832"/>
                            <g class="eye">
                                <circle fill="#FFFFFF" cx="230.19" cy="178.929" r="9.551"/>
                                <path d="M230.19,173.222c-0.481,0-0.949,0.06-1.398,0.171c0.258,0.405,0.406,0.885,0.406,1.4c0,1.447-1.172,2.619-2.619,2.619 c-0.654,0-1.253-0.241-1.714-0.638c-0.283,0.681-0.44,1.429-0.44,2.214c0,3.184,2.581,5.764,5.766,5.764s5.766-2.58,5.766-5.764 C235.956,175.803,233.375,173.222,230.19,173.222z"/>
                            </g>
                            <g class="eye">
                                <circle fill="#FFFFFF" cx="170.802" cy="178.929" r="9.552"/>
                                <path d="M170.802,173.222c-0.482,0-0.949,0.06-1.398,0.171c0.257,0.405,0.407,0.885,0.407,1.4c0,1.447-1.174,2.619-2.62,2.619 c-0.655,0-1.254-0.241-1.714-0.638c-0.283,0.681-0.44,1.429-0.44,2.214c0,3.184,2.581,5.764,5.766,5.764s5.766-2.58,5.766-5.764 C176.567,175.803,173.986,173.222,170.802,173.222z"/>
                            </g>
                            <g>
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#6B3815" d="M258,136v30.774c7-0.883,12-5.43,12-10.922V126H258z"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#6B3815" d="M147.109,164.33c-7.143,5.105-18.449,1.699-19.795-9.419 c-10.266-58.371,21.152-60.824,21.152-60.824s-2.678-1.742-4.252-2.767c6.418-3.438,13.241-2.234,19.043,1.522 C187.104,107.034,166.324,151.184,147.109,164.33z"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" fill="#4F260B" d="M166.006,96.921l-4.389,10.162 c9.484,31.142-10.533,54.303-10.533,54.303C162.5,151.977,184.316,119.742,166.006,96.921z"/>
                                <g id="hair">
                                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#6B3815" d="M201.883,146.219c1.371,2.254,2.523,4.146,4.095,6.729 c-59.673-18.631-46.111-60.226-41.876-66.788c4.234-6.563,11.658-12.6,20.608-16.324c-0.481,1.902-0.858,3.393-1.235,4.888 c22.131-7.764,49.002-0.148,63.532,10.733c4.941,3.701,9.703,7.613,14.57,11.401c8.224,6.396,17.896,9.472,28.993,10.778 c-5.158,7.781-17.902,14.202-17.902,14.202s7.69,3.34,12.366,5.37C285.034,127.208,254.983,157.615,201.883,146.219z"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#4F260B" d="M256.379,104.706c0,0,16.915,9.361,33.83,3.449 c0,0-4.816,6.628-17.347,14.047C272.862,122.201,260.942,116.496,256.379,104.706z"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" fill="#4F260B" d="M193.809,123.123c0,0,23.333,28.178,56.271,22.617 c0,0-27.564,7.207-48.49,0.195C201.59,145.936,193.254,133.809,193.809,123.123z"/>
                                    <path opacity="0.3" fill-rule="evenodd" clip-rule="evenodd" fill="#4F260B" d="M170.264,132.057 c-4.944,13.301-14.129,26.021-23.009,32.167c0.385-0.181,0.765-0.378,1.132-0.599c8.695-4.995,17.765-15.334,22.678-25.838 c6.094,6.406,16.153,12.604,32.137,17.391c-1.082-1.578-1.949-2.84-2.838-4.135C186.019,145.805,176.47,139.134,170.264,132.057z"/>
                                    <path opacity="0.3" fill-rule="evenodd" clip-rule="evenodd" fill="#4F260B" d="M250.08,145.74c0,0-25.727,6.724-46.34,0.857 c-0.617-0.123-1.234-0.245-1.857-0.379c0.924,1.518,1.75,2.876,2.67,4.388c38.78,6.563,60.551-8.702,65.84-13.063 c-5.628,3.07-12.816,6.19-21.48,8.359C249.303,145.846,249.688,145.807,250.08,145.74z"/>
                                </g>
                            </g>
                            <g id="glasses">
                                <path opacity="0.25" fill="#4F260B" d="M247.237,159h-27.354c-7.833,0-13.884,5.873-13.884,13.706v1.214 c-3.499-1.489-7.455-1.561-11-0.22v-0.994c0-7.833-5.929-13.706-13.763-13.706h-27.354c-7.833,0-13.884,5.873-13.884,13.706v15.78 c0,7.833,6.051,14.514,13.884,14.514h27.354c7.834,0,13.763-6.681,13.763-14.514v-8.007c3.274-2.459,7.891-2.309,11,0.452v7.555 c0,7.833,6.051,14.514,13.884,14.514h27.354c7.834,0,13.763-6.681,13.763-14.514v-15.78C261,164.873,255.071,159,247.237,159z M189,188.486c0,4.532-3.23,8.514-7.763,8.514h-27.354c-4.531,0-7.884-3.981-7.884-8.514v-15.78c0-4.532,3.353-7.706,7.884-7.706 H181h0.237c4.532,0,7.763,3.174,7.763,7.706V188.486z M255,188.486c0,4.532-3.23,8.514-7.763,8.514h-27.354 c-4.531,0-7.884-3.981-7.884-8.514v-15.78c0-4.532,3.353-7.706,7.884-7.706H247h0.237c4.532,0,7.763,3.174,7.763,7.706V188.486z"/>
                                <path fill="none" stroke="#000000" stroke-width="6" stroke-miterlimit="10" d="M208.506,176.158 c-4.578-4.577-11.986-4.577-16.563,0"/>
                                <path d="M247.237,162c4.532,0,7.763,3.174,7.763,7.706v15.78c0,4.532-3.23,8.514-7.763,8.514h-27.354 c-4.531,0-7.884-3.981-7.884-8.514v-15.78c0-4.532,3.353-7.706,7.884-7.706H247 M247.237,156h-27.354 c-7.833,0-13.884,5.873-13.884,13.706v15.78c0,7.833,6.051,14.514,13.884,14.514h27.354c7.834,0,13.763-6.681,13.763-14.514 v-15.78C261,161.873,255.071,156,247.237,156L247.237,156z"/>
                                <path opacity="0.3" fill="#FFFFFF" d="M257,168.327c0-4.599-3.729-8.327-8.329-8.327h-30.342c-4.6,0-8.329,3.728-8.329,8.327 v19.346c0,4.599,3.729,8.327,8.329,8.327h30.342c4.6,0,8.329-3.728,8.329-8.327V168.327z"/>
                                <path class="reflection" opacity=".3" fill="#FFFFFF" d="M226.629,160l-13.033,34.105c1.407,1.09,3.168,1.895,5.086,1.895h11.383l13.703-36H226.629z"/>
                                <path d="M181.237,162c4.532,0,7.763,3.174,7.763,7.706v15.78c0,4.532-3.23,8.514-7.763,8.514h-27.354 c-4.531,0-7.884-3.981-7.884-8.514v-15.78c0-4.532,3.353-7.706,7.884-7.706H181 M181.237,156h-27.354 c-7.833,0-13.884,5.873-13.884,13.706v15.78c0,7.833,6.051,14.514,13.884,14.514h27.354c7.834,0,13.763-6.681,13.763-14.514 v-15.78C195,161.873,189.071,156,181.237,156L181.237,156z"/>
                                <path opacity="0.35" fill="#FFFFFF" d="M191,168.327c0-4.599-3.729-8.327-8.329-8.327h-30.342c-4.6,0-8.329,3.728-8.329,8.327 v19.346c0,4.599,3.729,8.327,8.329,8.327h30.342c4.6,0,8.329-3.728,8.329-8.327V168.327z"/>
                                <path class="reflection" opacity="0.3" fill="#FFFFFF" d="M160.629,160l-13.033,34.105c1.407,1.09,3.168,1.895,5.086,1.895h11.383l13.703-36H160.629z"/>
                            </g>
                        </g>
                    </g>
                    <rect id="clip" x="11" y="344" fill="#192B40" width="372" height="54"/>
                    <g id="book" >
                        <path id="bk_bdy" fill="#31A7DB" d=""/>
                        <path id="bk_edg" fill="none" stroke="#037391" stroke-width="5" stroke-linecap="round" stroke-miterlimit="10" d=""/>
                        <polygon id="btm_pgs" fill="#D3D3D3"/>
                        <polygon id="btm_r" fill="#F2F2F2" points=""/>
                        <polygon id="btm_l" fill="#f0f0f0" points=""/>
                        <polygon id="line" opacity="0" fill="#D3D3D3" points="201,326 198,326 199,299 200,299"/>
                        <path opacity="0" id="pgFld" fill="#f2f2f2" d="M199.235,299c0,0,55.694-1,102-1L354,325 c-85.08,0-153.765,1-153.765,1L199.235,299z"></path>
                        <animate xlink:href="#pgFld" attributeName="d" begin="4s" dur="5s" repeatCount="indefinite" keyTimes="0; .03; .06; .1; .15; 1" values=" M199,299c0,0-55.695-1-102-1l-52.765,27 c85.08,0,153.765,1,153.765,1L199,299z; M199,299c0,0-15.446-45.394-71.818-42.365L99,271 c68.5-11.504,99,54.5,99,54.5L199,299z; M199,299c0,0-1-30-2.352-74.5l-0.371,4.5 c5.578,43.393,1.723,97,1.723,97L199,299z; M199,299c0,0,51.08,6,58.769-47.116l15.36,12.779 C273.129,323,198,326,198,326L199,299z; M199.235,299c0,0,55.694-1,102-1L354,325 c-85.08,0-153.765,1-153.765,1L199.235,299z; M199.235,299c0,0,55.694-1,102-1L354,325 c-85.08,0-153.765,1-153.765,1L199.235,299z "/>
                    </g>
                    <g opacity="0.3">
                        <path style="display:none;" class="stars" fill="#FFFFFF" d="M280.252,56.796c0.255-0.466,0.673-0.466,0.928,0l1.214,2.22 c0.255,0.466,0.846,1.056,1.312,1.312l2.219,1.213c0.466,0.255,0.466,0.672,0,0.927l-2.219,1.214 c-0.466,0.255-1.057,0.845-1.312,1.311l-1.214,2.22c-0.255,0.466-0.673,0.466-0.928,0l-1.213-2.22 c-0.255-0.466-0.846-1.056-1.312-1.311l-2.22-1.214c-0.466-0.255-0.466-0.672,0-0.927l2.22-1.214 c0.466-0.255,1.057-0.845,1.312-1.311L280.252,56.796z"/>
                        <path style="display:none;" class="stars" fill="#FFFFFF" d="M196.203,37.407c0.219-0.401,0.579-0.401,0.798,0l1.046,1.911 c0.219,0.401,0.727,0.909,1.128,1.128l1.909,1.045c0.401,0.219,0.401,0.579,0,0.798l-1.909,1.044 c-0.401,0.219-0.909,0.727-1.128,1.128L197,46.374c-0.219,0.401-0.579,0.401-0.798,0l-1.045-1.911 c-0.219-0.401-0.727-0.909-1.128-1.128l-1.91-1.044c-0.401-0.219-0.401-0.579,0-0.798l1.91-1.045 c0.401-0.219,0.909-0.727,1.128-1.128L196.203,37.407z"/>
                        <path style="display:none;" class="stars" fill="#FFFFFF" d="M44.003,195.166c0.329-0.602,0.868-0.602,1.197,0l1.568,2.867 c0.329,0.602,1.091,1.364,1.693,1.693l2.864,1.567c0.602,0.329,0.602,0.868,0,1.197l-2.864,1.566 c-0.602,0.329-1.364,1.091-1.693,1.693l-1.568,2.865c-0.329,0.603-0.868,0.603-1.197,0l-1.566-2.865 c-0.329-0.603-1.091-1.364-1.693-1.693l-2.865-1.566c-0.602-0.329-0.602-0.868,0-1.197l2.866-1.567 c0.602-0.329,1.363-1.091,1.692-1.693L44.003,195.166z"/>
                        <path style="display:none;" class="stars" fill="#FFFFFF" d="M91.01,119.478c0.151-0.277,0.399-0.277,0.551,0l0.722,1.32 c0.152,0.277,0.502,0.628,0.78,0.78l1.318,0.721c0.277,0.152,0.277,0.4,0,0.552l-1.318,0.721c-0.277,0.152-0.628,0.502-0.78,0.78 l-0.722,1.32c-0.152,0.277-0.4,0.277-0.551,0l-0.722-1.32c-0.151-0.277-0.502-0.628-0.779-0.779l-1.319-0.722 c-0.277-0.151-0.277-0.399,0-0.551l1.319-0.722c0.277-0.152,0.628-0.502,0.779-0.78L91.01,119.478z"/>
                        <path style="display:none;" class="stars" fill="#FFFFFF" d="M344.458,162.397c0.18-0.329,0.475-0.33,0.654,0l0.857,1.567 c0.18,0.33,0.597,0.746,0.926,0.926l1.565,0.856c0.329,0.18,0.329,0.475,0,0.655l-1.565,0.857 c-0.329,0.18-0.746,0.596-0.926,0.926l-0.857,1.567c-0.18,0.33-0.475,0.329-0.654,0l-0.856-1.567 c-0.18-0.329-0.597-0.746-0.926-0.926l-1.566-0.857c-0.329-0.18-0.329-0.475,0-0.654l1.566-0.857 c0.329-0.18,0.746-0.597,0.926-0.926L344.458,162.397z"/>
                    </g>
                </svg>
            </div>
            <div id="textoSimuladoBox">
                <p>Algo deu errado, vai continuar?</p>
            </div>
            <div id="SimuladoBoxButtons">
                <a onclick="closeBox()" style="cursor: pointer" class="cancel_button">Cancelar</a>
                <a onclick="finalizaSimulado()" style="cursor: pointer" class="confirm_button">Confirmar</a>
            </div>
            <br><br>
        </div>
        <span id="campoQuestoes">
            <div style="color:#ecf0f1;">
                <h1 style="font-weight: 900;">Comece o simulado!</h1>
                <p style="font-weight: 800;">Assim que você começar as questões serão buscadas.<br>O gabarito será liberado na segunda-feira,  basta acessar esta página no dia.<br>Depois de começar, você terá 4 horas e 30 minutos ininterruptos para responder todas as 90 questões.</p>
                <?php if($is_mobile){ ?>
                    <p style="font-weight: 800;">Abra o Menu e clique em "Começar".</p>
                <?php }; ?>
            </div>
            <?php if($_SESSION['usuario']['simuladoUse']!==1){ ?>
            <svg class="spaceship" xmlns="http://www.w3.org/2000/svg" viewbox="0 0 109.3 116.8">
                <g class="leg leg__4 leg--moving" id="leg-4">
                    <path class="leg" d="M83.7 99.6l-8.8-13c-.8-1.1-.4-2.7.7-3.4 1.2-.7 2.7-.3 3.3.9L86.4 98c.4.7.1 1.7-.6 2.1-.7.4-1.6.2-2.1-.5z"></path>
                    <path class="leg leg__4__tip leg--moving-tip" d="M82.3 107.1l1.8-8.6c.1-.7.8-1.1 1.5-.9.7.2 1.1.9.8 1.5l-2.7 8.4-2 4.7.6-5.1z"></path>
                </g>
                <g class="leg leg__1 leg--moving" id="leg-1">
                    <path class="leg" d="M23.5 99.6l8.8-13c.8-1.1.4-2.7-.7-3.4-1.2-.7-2.7-.3-3.3.9L20.8 98c-.4.7-.1 1.7.6 2.1.7.4 1.6.2 2.1-.5z"></path>
                    <path class="leg leg__1__tip leg--moving-tip" d="M24.9 107.1l-1.8-8.6c-.1-.7-.8-1.1-1.5-.9-.7.2-1.1.9-.8 1.5l2.7 8.4 2 4.7-.6-5.1z"></path>
                </g>
                <g id="alien">
                    <path class="st1" id="body" d="M63.6 52.4c.3-1.4.1-3.1-.1-4.4-.3-1.8-1.4-1.2-1.9-.7s.2 2 0 3.1c-.1.7-1 1.6-1.5 2.1-7.7 1.2-12.8 10.8-12.8 10.8 5.1.2 14.1 0 20.3 0-.2-5.5.6-10.1-4-10.9z"></path>
                    <g class="head" id="head">
                        <path class="st1" d="M55.9 46.3l-3.1-1.7c-3.3-1.9-2.4-6.9 1.4-7.5l5.6-.9c4.1-.7 8.4.1 12.1 2.2l5.1 2.9c3.3 1.9 2.4 6.9-1.4 7.5l-3.6.6c-5.5.7-11.2-.3-16.1-3.1z"></path>
                        <circle class="st2" cx="54.9" cy="41.2" r="3.1"></circle>
                        <circle class="st2" cx="75.1" cy="44.8" r="3.1"></circle>
                        <circle class="st3" cx="56.3" cy="40.2" r=".9"></circle>
                        <circle class="st3" cx="53.4" cy="42.1" r=".9"></circle>
                        <circle class="st3" cx="76.6" cy="43.9" r=".9"></circle>
                        <circle class="st3" cx="73.7" cy="45.7" r=".9"></circle>
                    </g>
                </g>
                <g id="spaceship">
                    <path class="st4" d="M37.8 92.6c-7-1.6-14.1-7.6-14.9-11h67S57.7 97 37.8 92.6z"></path>
                    <path class="st5" d="M95.8 82.7l-17 1.2c-16.1 1.2-32.2 1.2-48.2 0l-16.8-1.2c-5.5 0-9.9-4.2-9.9-9.4s4.5-9.4 9.9-9.4h9l4.6.4c18.3 1.7 36.7 1.9 55 .6l13.5-1c5.5 0 9.9 4.2 9.9 9.4s-4.5 9.4-10 9.4z"></path>
                    <path class="st6" d="M77.4 14.1C108 9.3 88.3 56.2 89.2 63.9h-67s21.4-44.5 55.2-49.8z"></path>
                    <path class="st7" d="M102.8 79.8l-9.8.6c-25.6 1.6-51.2 1.6-76.8 0l-9.8-.6c-3.6 0-6.5-2.9-6.5-6.5s2.9-6.5 6.5-6.5l9.8.6C41.8 69 67.4 69 93 67.4l9.8-.6c3.6 0 6.5 2.9 6.5 6.5s-2.9 6.5-6.5 6.5zM82.8 12.5s-.9 1.3-3 .3c-1.7-10.1-1.4-8.9-1.4-8.9L70.1 0l10 2.5 2.7 10z"></path>
                    <path class="st4" d="M21.9 61.2c-1.8 1.8-2.8 2.9-2.8 2.9l12.7 1c15.8 1.3 31.6 1.3 47.4 0L92.4 64c0-.3-.1-.6-.1-.9-.3-1.8-1.8-3-3.6-3l-5.9.5c-18 1.6-36.1 1.6-54-.1l-4.2-.4c-1.1.1-2 .5-2.7 1.1zM82.8 11.4c.9.4 1.7 2.1 1.8 3-1 .6-2.5.8-3.4.8-3.6 0-4.8-.8-4.8-.8s4-4.3 6.4-3z"></path>
                    <g class="lantern_1" id="lantern_1">
                        <circle class="st4" cx="7.3" cy="73.5" r="3.6"></circle>
                        <circle class="st8" cx="7.3" cy="73.5" r="2.9"></circle>
                        <circle class="st9" cx="8" cy="72.9" r="1.8"></circle>
                    </g>
                    <g class="lantern_2" id="lantern_2">
                        <circle class="st4" cx="23.1" cy="74.5" r="3.6"></circle>
                        <circle class="st8" cx="23.1" cy="74.5" r="2.9"></circle>
                        <circle class="st9" cx="23.8" cy="73.9" r="1.8"></circle>
                    </g>
                    <g class="lantern_3" id="lantern_3">
                        <circle class="st4" cx="38.9" cy="75.5" r="3.6"></circle>
                        <circle class="st8" cx="38.9" cy="75.5" r="2.9"></circle>
                        <circle class="st9" cx="39.6" cy="74.9" r="1.8"></circle>
                    </g>
                    <g class="lantern_4" id="lantern_4">
                        <circle class="st4" cx="54.7" cy="75.5" r="3.6"></circle>
                        <circle class="st8" cx="54.7" cy="75.5" r="2.9"></circle>
                        <circle class="st9" cx="55.4" cy="74.9" r="1.8"></circle>
                    </g>
                    <g class="lantern_5" id="lantern_5">
                        <circle class="st4" cx="70.6" cy="75.5" r="3.6"></circle>
                        <circle class="st8" cx="70.6" cy="75.5" r="2.9"></circle>
                        <circle class="st9" cx="71.2" cy="74.9" r="1.8"></circle>
                    </g>
                    <g class="lantern_6" id="lantern_6">
                        <circle class="st4" cx="86.4" cy="74.5" r="3.6"></circle>
                        <circle class="st8" cx="86.4" cy="74.5" r="2.9"></circle>
                        <circle class="st9" cx="87" cy="73.9" r="1.8"></circle>
                    </g>
                    <g class="lantern_7" id="lantern_7">
                        <circle class="st4" cx="102.2" cy="73.5" r="3.6"></circle>
                        <circle class="st8" cx="102.2" cy="73.5" r="2.9"></circle>
                        <circle class="st9" cx="102.9" cy="72.9" r="1.8"></circle>
                    </g>
                    <path class="st6" d="M87.4 23.2c-1.2 0-2.2-1-2.2-2.2 0-1.2 1-2.2 2.2-2.2 1.2 0 2.2 1 2.2 2.2 0 1.2-1 2.2-2.2 2.2zM89.1 26.1c.8-.3 1.6.2 1.6 1.1 0 7.5-.6 14.3-3.3 22.2.9-9.8 1.7-14.9 1-22-.1-.6.2-1.1.7-1.3z"></path>
                </g>
                <g class="leg leg__3 leg--moving" id="leg-3">
                    <path class="leg" d="M70.8 104.2l-8.8-13c-.8-1.1-.4-2.7.7-3.4 1.2-.7 2.7-.3 3.3.9l7.5 13.8c.4.7.1 1.7-.6 2.1-.7.5-1.6.3-2.1-.4z"></path>
                    <path class="leg leg__3__tip leg--moving-tip" d="M69.4 111.7l1.8-8.6c.1-.7.8-1.1 1.5-.9.7.2 1.1.9.8 1.5l-2.7 8.4-2 4.7.6-5.1z"></path>
                </g>
                <g class="leg leg__2 leg--moving" id="leg-2">
                    <path class="leg" d="M32.2 104.2l8.8-13c.8-1.1.4-2.7-.7-3.4-1.2-.7-2.7-.3-3.3.9l-7.5 13.8c-.4.7-.1 1.7.6 2.1.7.5 1.6.3 2.1-.4z"></path>
                    <path class="leg leg__2__tip leg--moving-tip" d="M33.6 111.7l-1.8-8.6c-.1-.7-.8-1.1-1.5-.9-.7.2-1.1.9-.8 1.5l2.7 8.4 2 4.7-.6-5.1z"></path>
                </g>
            </svg>
            <div class="background">
                <ul class="cloud">
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
                <div class="city"></div>
            </div>
            <?php }; ?>
        </span>
        <div id="fieldT">
        </div>
        <div id="savingEnd" style="cursor: pointer;position: fixed; right: 2px; top: 80px;">

        </div>
        
        <div class="clock" style="cursor: pointer;position: fixed; left: 2px; bottom: 10px;" <?php if($is_mobile){ echo "style='display:none;'";} ?>><img src="img/hourglassTime.svg" width="50px" onmouseover="document.getElementById('clockBox').style.opacity='1';document.getElementById('clockBox').style.width='100px';generalBox(1);" onmouseout="document.getElementById('clockBox').style.opacity='0';document.getElementById('clockBox').style.width='0px';generalBox(0);"></div>
        <div class="clock" id="clockBox">
            <span id="generalBox" style="opacity: 0;">
                <span id="horasBox">--</span>
                <span>:</span>
                <span id="minutosBox">--</span>
                <span>:</span>
                <span id="segundosBox">--</span>
            </span>
        </div>

        <script src="js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/snap.svg/0.3.0/snap.svg-min.js'></script>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js'></script>
        <script src="js/animationStudent.js"></script>
    </body>
</html>
