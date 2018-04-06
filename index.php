<?php
     require './add/HeaderSession.php';
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
        } else {
            header("Location: questoes");
        }
    }
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <link rel="stylesheet" href="css/reset.min.css">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <meta name="theme-color" content="#2c3e50">
        <link href="css/footer.min.css" rel="stylesheet" type="text/css">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Simulado Cedaf</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="sortcut icon" href="img/checked.png" type="image/png" />
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/index.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body id="bodyElement">
         <?php include_once("add/analyticstracking.php"); ?>
    <div class="container-fluid">
        <div class="login" id="loginID">
            <div class="login-center">
                <i class="fa fa-times fa-3x" aria-label="Fechar aba de login" style="position: absolute; right: 15px; top: 5px; cursor: pointer;" onclick="closeLogin()"></i>
                <div id="scannerArea">
                    <div id="lineScanner"></div>
                    <img src="img/fingerprints.svg" style="width: 100%">
                </div>
                <h1>Fazer Log-in</h1>
                <form class="form-horizontal" method="POST" action="valida/valida.php">
                    <!-- email input-->
                    <div class="form-group">
                        <div class="col-md-8 col-md-push-3">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                <input id="email" name="nEmail" type="text" placeholder="Endereço de e-mail ou usuário" class="form-control input-md" required=""> <i class="fa fa-2x fa-info-circle tooltipped" style="display: table-cell; vertical-align: middle; padding-left: 10px; cursor: pointer;" data-placement="bottom" data-toggle="tooltip" aria-hidden="false"></i>
                            </div>
                        </div>
                    </div>
                    <!-- Password input-->
                    <div class="form-group">
                        <div class="col-md-8 col-md-push-3">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1"><i class="fa fa-lock" aria-hidden="true"></i></span>
                                <input id="senha" name="nPassword" type="password" placeholder="Senha" class="form-control input-md">
                            </div>
                        </div>
                    </div>
                    <span id="senhaHelp" class="col-md-8 col-md-push-3"><a id="linkPassword" href="recuperacao">Esqueceu sua senha</a>?</span><br><br><br>
                    <button id="loginB" class="btn btn-block btn-lg btn-primary">Efetuar Log-in&nbsp;&nbsp;<i class="fa fa-sign-in" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>

        <div class="calendar" id="calendarID">
            <div class="calendar-center">
                <i class="fa fa-times fa-3x" aria-label="Fechar aba de login" style="position: absolute; right: 15px; top: 5px; cursor: pointer;" onclick="closeCalendar()"></i>
                <iframe src="https://calendar.google.com/calendar/embed?src=mle5km3vnkfgp7ttdea9pnjr4s%40group.calendar.google.com&ctz=America/Sao_Paulo" style="border: 0; margin-top: 30px;" height="85%" width="100%" frameborder="0" scrolling="no"></iframe>
            </div>
        </div>

        <?php

            if(isset($_SESSION['loginErro'])){
                echo "<div id='dialogBox' class='sessao1' style='z-index: 1001;position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>".$_SESSION['loginErro']."</p></div>";
                unset($_SESSION['loginErro']);
                echo "<script>setTimeout(function(){ closeAlert(); },4000);</script>";
            }

            if(isset($_SESSION['logout'])){
                echo "<div id='dialogBox' class='sessao2' style='z-index: 1001;position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>".$_SESSION['logout']."</p></div>";
                unset($_SESSION['logout']);
                echo "<script>setTimeout(function(){ closeAlert(); },4000);</script>";
            }
        ?>

        <header class="row">
            <div class="rightSide">
                <a class="btn btn-lg btn-top" onclick="openLogin()">Log-in&nbsp;&nbsp;<i class="fa fa-sign-in" aria-hidden="true"></i></a>
                &nbsp;
                <a class="btn btn-lg btn-top" href="planos">Registrar&nbsp;&nbsp;<i class="fa fa-user-plus" aria-hidden="true"></i></a>
            </div>
            <br>
            <img id="StudentFlying" src="img/studentFlying.svg" style="width: 25%;">
            <p id="title">Si<wbr>&shy;mu<wbr>&shy;la<wbr>&shy;do Enem UFV</p>
            <p id="corpo">Mais recursos para garantir sua vaga.</p>
            <br>
            <button id="loginB" class="btn btn-lg btn-primary" onclick="openLogin()">Efetuar o Log-in&nbsp;&nbsp;<i class="fa fa-sign-in" aria-hidden="true"></i></button><br>
            <button id="calendarButton" onclick="openCalendar()"><i class="fa fa-calendar" aria-hidden="true"></i> Agenda de Simulados</button>
            <p id="navegue"><i class="fa fa-long-arrow-down" aria-hidden="true"></i>&nbsp;&nbsp;Navegue para saber mais</p>
        </header>
        <div class="row">
            <div role="main" class="col-md-12 mainB">
                <div class="row bodyPresentation" id="slide1">
                    <h3 class="col-md-12 center tituloBody topReveal"><img data-src="img/street-map.svg" aria-hidden="true" class="lazy-load">&nbsp;Acesse de qualquer lugar</h3>
                    <div class="col-md-4 col-sm-6 col-xs-6 imgReveal leftReveal" style="float:left"><img data-src="img/multidev.svg" aria-hidden="true" class="lazy-load" width="100%"></div>
                    <p class="col-md-7 col-sm-6 col-xs-6 right rightReveal textBody">A qualquer momento e de qualquer lugar, você pode efetuar o log-in e resolver questões. Desse modo, você melhora as suas habilidades, resolvendo as questões de forma mais rápida e adquire conhecimento.</p>
                </div>

                <div class="row bodyPresentation" id="slide2">
                    <h3 class="col-md-12 center tituloBody topReveal"><img  data-src="img/flag.svg" aria-hidden="true" class="lazy-load">&nbsp;Nossos propósitos</h3>
                    <p class="col-md-6 left col-sm-6 col-xs-6 textBody leftReveal">O nosso objetivo é fornecer uma ferramenta de qualidade que ajude o aluno em sua preparação para os vestibulares, com foco no Exame Nacional do Ensino Médio.</p>
                    <div class="col-md-6 col-sm-6 col-xs-6 imgReveal rightReveal" style="float: right;"><img data-src="img/studentIndex.svg" aria-hidden="true" class="lazy-load" width="100%"></div>
                </div>

                <div class="row bodyPresentation" id="slide3">
                    <h3 class="col-md-12 center tituloBody topReveal"><img data-src="img/stopwatch.svg" aria-hidden="true" class="lazy-load">&nbsp;Participe de simulados</h3>
                    <p class="col-md-6 col-sm-6 col-xs-6 left textBody leftReveal">No decorrer dos meses teremos simulados, onde cada etapa da prova ocorrerá em um final de semana. Assim, buscamos simular a prova do ENEM.</p>
                    <div class="col-md-6 col-sm-6 col-xs-6 imgReveal rightReveal" style="float:right"><img data-src="img/QUIZ.svg" aria-hidden="true" class="lazy-load" width="100%"></div>
                </div>

                <div class="row bodyPresentation" id="slide4">
                    <h3 class="col-md-12 center tituloBody topReveal"><img data-src="img/trophy-star.svg" aria-hidden="true" class="lazy-load">&nbsp;Ganhe prêmios</h3>
                    <div class="col-md-5 col-sm-6 col-xs-6 imgReveal leftReveal" style="float:left"><img data-src="img/trophy.svg" aria-hidden="true" class="lazy-load" width="100%"></div>
                    <p class="col-md-7 col-sm-6 col-xs-6 right textBody rightReveal">Ao final do mês os alunos com as melhores pontuações no ranking serão premiados.</p>
                </div>

                <div class="row bodyPresentation" id="slide5">
                    <h3 class="col-md-12 center tituloBody topReveal"><img data-src="img/placeholder.svg" aria-hidden="true" class="lazy-load">&nbsp;Por que criamos este site?</h3>
                    <p class="col-md-6 col-sm-6 col-xs-6 left textBody leftReveal">Este site foi criado com o intuito de ser um complemento na preparação dos alunos para o Exame Nacional do Ensino Médio (ENEM). O site foi desenvolvido para cumprir com a promessa feita pelo <a href="http://www.gremioestudantil.caf.ufv.br/" style="color:#f1c40f;" target="_blank" rel="noopener" title="Nosso site">Grêmio Estudantil Diogo Alves de Melo</a> na chapa de 2017. Esperamos que você aproveite.</p>
                    <div class="col-md-6 col-sm-6 col-xs-6 imgReveal rightReveal" style="float:right"><img data-src="img/meeting.svg" aria-hidden="true" class="lazy-load" width="100%"></div>
                </div>

                <div class="row bodyPresentation" id="slide6">
                    <h3 class="col-md-12 center tituloBody topReveal"><img data-src="img/text-lines.svg" aria-hidden="true" class="lazy-load">&nbsp;Infor<wbr>&shy;ma<wbr>&shy;ções gerais</h3>
                    <div class="col-md-6 col-sm-6 col-xs-6 imgReveal leftReveal" style="float:left"><img data-src="img/informacoes.svg" aria-hidden="true" class="lazy-load" width="100%"></div>
                    <p class="col-md-6 col-sm-6 col-xs-6 right textBody rightReveal">Para se registrar acesse o formulário para cadastro aqui, lembre-se que apenas alunos da Cedaf poderão participar. Deste modo, haverá a verificação se o aluno está matriculado na instituição. Caso você ainda tenha dúvidas acesse o nosso <a title="FAQ" href="ajuda">FAQ</a>.</p>
                </div>

                <div class="row bodyPresentation"  id="slide7">
                    <h3 class="col-md-12 center tituloBody topReveal"><img data-src="img/video-player.svg" aria-hidden="true" class="lazy-load">&nbsp;Nosso vídeo</h3>
                    <span class="col-md-7 col-sm-8 col-xs-12 leftReveal"><div class="embed-responsive embed-responsive-16by9"><iframe width="560" height="315" class="embed-responsive-item lazy-load" data-src="https://www.youtube.com/embed/tl7tpKccPJA" aria-hidden="true" class="lazy-load" allowfullscreen></iframe>
                    </div></span>
                    <div class="col-md-4 col-sm-4 col-xs-4 imgReveal rightReveal" style="float:right"><img data-src="img/video-playerYT.svg" aria-hidden="true" class="lazy-load" width="100%"></div>
                </div>

            </div>

        </div>
        
        <div class="row">
            <div class="thanks">Made with&nbsp;
                <svg class="heart" viewBox="0 0 32 29.6">
                    <path d="M23.6,0c-3.4,0-6.3,2.7-7.6,5.6C14.7,2.7,11.8,0,8.4,0C3.8,0,0,3.8,0,8.4c0,9.4,9.5,11.9,16,21.2c6.1-9.3,16-12.1,16-21.2C32,3.8,28.2,0,23.6,0z"/>
                </svg>&nbsp;by<br>Daniel dos Santos Pereira
            </div>
        </div>
        <div class="modal fade" id="colaboradores">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Colaboradores</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        FAPEMIG<br>
                        Grêmio EStudantil Diogo Alves de Melo - GEDAM<br>
                        Danilo dos Santos<br>
                        David Rattes<br>
                        Frantiesco Menezes<br>
                        Helder Eduardo<br>
                        Henrique Araújo<br>
                        Hiago Figueiredo<br>
                        Igor Lemos<br>
                        Janine Barbosa<br>
                        Maria Eduarda Fiedler<br>
                        Tatiane Brandão<br>
                        Thiago Oliveira<br>
                        Tiago Mendonça<br>
                        Antônio Barros - Professor<br>
                        Marcus Henrique - Professor<br>
                        Ronan Dutra - Professor<br>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <footer class="row">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-sm-6 footerleft" style="color: azure;">
                        <div class="logofooter"> Créditos de Imagem</div>
                        <div>Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://br.freepik.com/" title="Freepik">www.freepik.com</a><br>Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/vectors-market" title="Vectors Market">Vectors Market</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/popcorns-arts" title="Popcorns Arts">Popcorns Arts</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/flat-icons" title="Flat Icons">Flat Icons</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/pixel-buddha" title="Pixel Buddha">Pixel Buddha</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/alfredo-hernandez" title="Alfredo Hernandez">Alfredo Hernandez</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank" rel="noopener">CC 3.0 BY</a></div>
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
                                <blockquote cite="https://www.facebook.com/gedam.cedaf"><a data-toggle="modal" data-target="#colaboradores" style="cursor: pointer">Colaboradores</a></blockquote>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="js/jquery.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/scrollreveal.min.js"></script>
    <script src="js/index.min.js"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip({title: "Ex: Se o seu e-mail é 'gremio@ufv.br', o usuário é 'gremio'", animation: true});
            adaptar();
        });
        window.sr = ScrollReveal({ reset: true, delay: 0, duration: 700, distance: '20%', mobile: false});
        sr.reveal('.leftReveal',{origin: 'left'});
        sr.reveal('.rightReveal',{origin: 'right'});
        sr.reveal('.topReveal',{origin: 'top'});

        const io = new IntersectionObserver(entries => {
            for(const entry of entries){
                //console.log(entry.target.id+' is in view: '+entry.isIntersecting);
                if(entry.isIntersecting){
                    applyIMG(entry);
                    stopObserve(entry);
                }
            }
        },{
            rootMargin: "200px" //MARGEM DA SENTINELA
        });

        document.querySelectorAll('.lazy-load').forEach(elem => io.observe(elem));//Observa cada elemento com .lazy-load

        function stopObserve(elem){
            io.unobserve(elem.target); //Para de rastrear o elemento
        }

        function applyIMG(elem){ //Inicia o carregamento da Imagem
            elem.target.setAttribute("src",elem.target.dataset.src);
        }
    </script>
    </body>
</html>

