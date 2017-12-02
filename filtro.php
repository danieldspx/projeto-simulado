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
    if(isset($_SESSION['questoesFiltro']) || isset($_SESSION['npaginas'])){
        unset($_SESSION['questoesFiltro']);
        unset($_SESSION['nquestoes']);
    }
    include_once 'private_html_protected/config.php';
    include_once 'private_html_protected/connection.php';
    include_once 'private_html_protected/database.php';
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        header("Location: LoginSimultaneo.php");
    }
    
    //Detecta Mobile
    include_once 'mobile_detect/Mobile_Detect.php';
    $detect = new Mobile_Detect;
    $is_mobile=$detect->isMobile();
?>
<html>
    <head>
        <meta name="robots" content="noindex">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/footer.css" rel="stylesheet" type="text/css">
        <title>Filtro</title>
        <meta charset="UTF-8">
        <link rel="sortcut icon" href="img/favicon/funnel.png" type="image/png" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link href="iconfont/material-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <link rel="stylesheet" href="css/filtro.css">
        <?php
            if($is_mobile){
                echo "<link href='css/styleMenu.css' rel='stylesheet'>";
                echo "<link href='css/animateMenu.css' rel='stylesheet'>"; 
            }
        ?>
        <?php include_once("add/analyticstracking.php") ?>
    </head>
    <body style="background-color: #fafafa">
        
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
                            <li class="menu-lista-item" onclick="window.location.href='desempenho';"><i class="fa fa-pie-chart" aria-hidden="true"></i> Desempenho</li>
                            <li class="menu-lista-item li-active" onclick="window.location.href='filtro';"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filtro</li>
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
                    <a class="navbar-brand" title="Dados do Usuário" href="usuario" style="font-family: Montserrant,sans-serif;font-weight: 600;color: #ecf0f1;"><span class="glyphicon glyphicon-user"></span>&nbsp; Olá, <?php echo $_SESSION['usuario']['nome']; ?>.</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="questoes"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>&nbsp; Questões</a></li>
                        <li><a href="ranking"><i class="fa fa-trophy" aria-hidden="true"></i>&nbsp; Ranking</a></li>
                        <li><a href="desempenho"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp; Desempenho</a></li>
                        <li class="active"><a href="filtro"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>&nbsp; Filtro <span class="sr-only">(current)</span></a></li>
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
        
        <!--COMEÇA A PAGINAÇÃO E AS QUESTOES, -->
        
        <form class="filtro" method="POST" action="questoes" style="margin-top: 90px;">
            <fieldset><legend>Opções</legend>
                <label class="labelTitle">Ano: </label><br>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="ano2016" name="ano[]" value="2016" type="checkbox"/>
                            <label for="ano2016" class="label-info"></label>
                        </div>
                        <div class="labelItem">2016</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="ano2015" name="ano[]" value="2015" type="checkbox"/>
                            <label for="ano2015" class="label-info"></label>
                        </div>
                        <div class="labelItem">2015</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="ano2014" name="ano[]" value="2014" type="checkbox"/>
                            <label for="ano2014" class="label-info"></label>
                        </div>
                        <div class="labelItem">2014</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="ano2013" name="ano[]" value="2013" type="checkbox"/>
                            <label for="ano2013" class="label-info"></label>
                        </div>
                        <div class="labelItem">2013</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="anoAll" name="ano[]" value="*" type="checkbox"/>
                            <label for="anoAll" class="label-info"></label>
                        </div>
                        <div class="labelItem">Todos</div>
                    </div>
                <label class="labelTitle">Área(s): </label><br>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="area4" name="area[]" value="4" type="checkbox"/>
                            <label for="area4" class="label-info"></label>
                        </div>
                        <div class="labelItem">Linguagens, Códigos e suas Tecnologias</div>
                    </div><br>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="area1" name="area[]" value="1" type="checkbox"/>
                            <label for="area1" class="label-info"></label>
                        </div>
                        <div class="labelItem">Matemática e suas Tecnologias</div>
                    </div><br>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="area2" name="area[]" value="2" type="checkbox"/>
                            <label for="area2" class="label-info"></label>
                        </div>
                        <div class="labelItem">Ciências da Natureza e suas Tecnologias</div>
                    </div><br>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="area3" name="area[]" value="3" type="checkbox"/>
                            <label for="area3" class="label-info"></label>
                        </div>
                        <div class="labelItem">Ciências Humanas e suas Tecnologias</div>
                    </div><br>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="areaAll" name="area[]" value="*" type="checkbox"/>
                            <label for="areaAll" class="label-info"></label>
                        </div>
                        <div class="labelItem">Todas</div>
                    </div>
                <label class="labelTitle">Disciplina(s): </label><br>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina1" name="disciplina[]" value="1" type="checkbox"/>
                            <label for="disciplina1" class="label-info"></label>
                        </div>
                        <div class="labelItem">Física</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina2" name="disciplina[]" value="2" type="checkbox"/>
                            <label for="disciplina2" class="label-info"></label>
                        </div>
                        <div class="labelItem">Matemática</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina3" name="disciplina[]" value="3" type="checkbox"/>
                            <label for="disciplina3" class="label-info"></label>
                        </div>
                        <div class="labelItem">Química</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina4" name="disciplina[]" value="4" type="checkbox"/>
                            <label for="disciplina4" class="label-info"></label>
                        </div>
                        <div class="labelItem">Biologia</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina5" name="disciplina[]" value="5" type="checkbox"/>
                            <label for="disciplina5" class="label-info"></label>
                        </div>
                        <div class="labelItem">Português</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina6" name="disciplina[]" value="6" type="checkbox"/>
                            <label for="disciplina6" class="label-info"></label>
                        </div>
                        <div class="labelItem">História</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina7" name="disciplina[]" value="7" type="checkbox"/>
                            <label for="disciplina7" class="label-info"></label>
                        </div>
                        <div class="labelItem">Sociologia</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina8" name="disciplina[]" value="8" type="checkbox"/>
                            <label for="disciplina8" class="label-info"></label>
                        </div>
                        <div class="labelItem">Filosofia</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina9" name="disciplina[]" value="9" type="checkbox"/>
                            <label for="disciplina9" class="label-info"></label>
                        </div>
                        <div class="labelItem">Inglês</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina10" name="disciplina[]" value="10" type="checkbox"/>
                            <label for="disciplina10" class="label-info"></label>
                        </div>
                        <div class="labelItem">Espanhol</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina11" name="disciplina[]" value="11" type="checkbox"/>
                            <label for="disciplina11" class="label-info"></label>
                        </div>
                        <div class="labelItem">Artes</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina12" name="disciplina[]" value="12" type="checkbox"/>
                            <label for="disciplina12" class="label-info"></label>
                        </div>
                        <div class="labelItem">Geografia</div>
                    </div>
                    <div class="group-item">
                        <div class="material-switch">
                            <input id="disciplina*" name="disciplina[]" value="*" type="checkbox"/>
                            <label for="disciplina*" class="label-info"></label>
                        </div>
                        <div class="labelItem">Todas</div>
                    </div>
                    
                    <input class="btnfiltro" type="submit" value="Filtrar">                
            </fieldset>
        </form>
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
        <script type="text/javascript" src="js/materialize.min.js"></script>
        <script src="js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>
        <?php
            if($is_mobile){
                echo "<script type='text/javascript' src='js/menu.js'></script>";
            }
        ?>
        <script src="js/vendor/bootstrap.min.js"></script>       
    </body>
</html>
