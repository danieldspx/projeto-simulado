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

    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        unset($resposta);
        header("Location: LoginSimultaneo.php");
    }
    unset($resposta);
    //Detecta Mobile
    require_once 'mobile_detect/Mobile_Detect.php';
    $detect = new Mobile_Detect;
    $is_mobile = $detect->isMobile();
        
    $pesquisa = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"nome,sobrenome,email,matricula,instituicao,ano,celular,SUBSTRING(foto_perfil,5) AS foto_perfil");
    $dados = $pesquisa[0];
    unset($pesquisa);

    $dados['ano'] .= "º Ano";

    $dados['instituicao'] = $dados['instituicao']==1 ? "Central de Ensino e Desenvolvimento Agrário de Florestal": "Escola Estadual Serafim Ribeiro de Rezende";

?>
<html>
    <head>
        <meta name="robots" content="noindex">
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="css/footer.css" rel="stylesheet" type="text/css">
        
        <title>Usuário</title>
        <meta charset="UTF-8">
        <link rel="sortcut icon" href="img/favicon/user-silhouette.png" type="image/png" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link href="css/usuario.css" rel="stylesheet" type="text/css"/>      
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/animate.min.css"/>
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <link href="iconfont/material-icons.css" rel="stylesheet">
        <?php
            if($is_mobile){
                echo "<link href='css/styleMenu.css' rel='stylesheet'>";
                echo "<link href='css/animateMenu.css' rel='stylesheet'>"; 
                echo "<link href='css/mobile_usuario.css' rel='stylesheet'>"; 
            }
        ?>
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
                            <li class="menu-lista-item" onclick="window.location.href='filtro';"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filtro</li>
                            <li class="menu-lista-item" onclick="window.location.href='simulado';"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Simulado</li>
                            <li class="menu-lista-item li-active" onclick="window.location.href='usuario';"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Meus Dados</li>
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
            <nav class="navbar navbar-inverse navbar-default" style="border-radius: 0px; margin: 0px" id="navbar-desktop">
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
                        <li><a href="desempenho"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp; Desempenho</a></li>
                        <li><a href="filtro"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>&nbsp; Filtro</a></li>
                        <li><a href="simulado"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>&nbsp; Simulado</a></li>
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
        <?php }; ?><!--//FIM DO TESTE IS_MOBILE-->
        <div id="main" class="col-md-12">
            <?php if(!$is_mobile){ ?>
                <div class="lside col-md-3">
                    <div class="imgSection">
                       <?php
                            $url_img = "userbg_".rand(1,7).".jpg";
                        ?>
                        <img <?php echo "src='img/$url_img'"; ?> width="100%" style="float: right; filter: brightness(90%)" alt="Imagem de Fundo" aria-hidden="true">
                        <div style="width:100%; text-align:center; position: absolute;">
                            <div id="placeImg"><img <?php echo "src='img/".$dados['foto_perfil']."'"; ?> id="imgUserView" width="100%" height="100%" alt="Imagem de Perfil"></div>
                        </div>
                    </div>
                    <div class="corpoSide">
                        <ul style="list-style: none; position: relative; padding: 0px; margin: 0px;">
                            <li class="corpoSideItem">Ranking <i class="fa fa-trophy" aria-hidden="true"></i></li>
                            <li class="corpoSideItem">Desempenho <i class="fa fa-pie-chart" aria-hidden="true"></i></li>
                        </ul>
                    </div>
                </div>
            <?php }; ?>
            <div class="rside col-md-9">
               <div class="tagsProfile">
                   <ul id="tagList">
                        <li class="tagItem activeTag" onclick="activePerfil()">Perfil</li>
                        <li class="tagItem" onclick="activeSenha()">Senha</li>
                   </ul>
               </div>
                <div class="sectionProfile">
                    <div id="perfilSection">
                        <div class="form">
                            <div class="row">
                                <div class="groupForm col-sm-offset-1 col-md-offset-1 col-xs-12 col-sm-6 col-md-6">
                                    <label for="nNome">Nome</label>
                                    <input type="text" name="nNome" <?php echo "value='".$dados['nome']."'"; ?>  id="nNome">
                                </div>
                                <div class="groupForm col-xs-12 col-sm-4 col-md-4">
                                    <label for="nSobrenome">Sobrenome</label>
                                    <input type="text" name="nSobrenome" <?php echo "value='".$dados['sobrenome']."'"; ?> id="nSobrenome">
                                </div>
                            </div>
                            <div class="row">
                                <div class="groupForm col-sm-offset-1 col-md-offset-1 col-xs-12 col-sm-6 col-md-6">
                                    <label for="nEmail">E-mail</label>
                                    <input type="text" name="nEmail" id="nEmail" <?php echo "value='".$dados['email']."'"; ?> readonly="readonly">
                                </div>
                                <div class="groupForm col-xs-12 col-sm-4 col-md-4">
                                    <label for="nMatricula">Matrícula</label>
                                    <input type="number" name="nMatricula" id="nMatricula" <?php echo "value='".$dados['matricula']."'"; ?> readonly="readonly">
                                </div>
                            </div>
                            <div class="row">
                                <div class="groupForm col-sm-offset-1 col-md-offset-1 col-xs-12 col-sm-6 col-md-6">
                                    <label for="nInstituicao">Instituição</label>
                                    <input type="text" name="nInstituicao" id="nInstituicao" <?php echo "value='".$dados['instituicao']."'"; ?> readonly="readonly">
                                </div>
                                <div class="groupForm col-xs-12 col-sm-4 col-md-4">
                                    <label for="nAno">Ano</label>
                                    <input type="text" name="nAno" id="nAno" <?php echo "value='".$dados['ano']."'"; ?> readonly="readonly">
                                </div>
                            </div>
                            <div class="row">
                               <div class="groupForm col-sm-offset-1 col-md-offset-1 col-xs-12 col-sm-6 col-md-6">
                                    <label for="iconePerfil">Ícone de Perfil</label><br>
                                    <span class="custom-dropdown custom-dropdown--white">
                                        <select name="iconePerfil" id="iconePerfil" onchange="changeIcon(this)" class="custom-dropdown__select custom-dropdown__select--white">
                                            <?php
                                                $img = str_replace(".png","",$dados['foto_perfil']);
                                                if($img=="Foguete"){echo("selected");}
                                                $imagens = array(
                                                    "Aluna" => "Aluna",
                                                    "Aluno" => "Aluno",
                                                    "Astronauta" => "Astronauta",
                                                    "Atomo" => "Átomo",
                                                    "Cerebro" => "Cérebro",
                                                    "Certificado" => "Certificado",
                                                    "Doutor" => "Doutor",
                                                    "Doutora" => "Doutora",
                                                    "Einstein" => "Einstein",
                                                    "Foguete" => "Foguete",
                                                    "Ghost" => "Ghost",
                                                    "Homem" => "Homem",
                                                    "HomemAprendendo" => "Homem lendo",
                                                    "Inteligencia Artificial" => "Inteligência Artificial",
                                                    "Jato" => "Jato",
                                                    "Mulher" => "Mulher",
                                                    "MulherAprendendo" => "Mulher lendo",
                                                    "Robocop" => "Robocop",
                                                    "SistemaSolar" => "Sistema Solar",
                                                    "ViaLactea" => "Via Láctea",
                                                );
                                                foreach($imagens as $key => $value){
                                                    if($img != $key){
                                                        echo "<option value='$key.png'>$value</option>";
                                                    } else {
                                                        echo "<option value='$key.png' selected>$value</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </span>
                                    <div id="viewIcon"><img <?php echo "src='img/$img.png'"; ?> id="imgView" alt="Imagem de Pre-View" width="100%"></div>
                                </div>
                                <div class="groupForm col-xs-12 col-sm-4 col-md-4">
                                    <label for="nCelular">Celular</label>
                                    <input type="text" name="nCelular" <?php echo "value='".$dados['celular']."'"; ?> id="nCelular" onkeydown="MaskDown(this)" onkeyup="MaskUp(this,event,'(##) #####-####')">
                                </div>
                                <div class="row col-xs-12 col-sm-12 col-md-12" style="text-align: center; margin-top: 40px;">
                                    <button class="btnForm" onclick="salvaDados()">Salvar <i class="material-icons" style="margin-left: 5px;">save</i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="senhaSection">
                        <div class="form">
                            <h1>Alteração de Senha</h1>
                            <div class="row col-md-12">
                                <div class="groupForm col-md-6 col-sm-12">
                                    <label for="nSenha">Senha Nova<i class="material-icons iconModify pointer" onclick="changeVisibility(this,'nSenha')">visibility</i></label>
                                    <input type="password" name="nSenha" onkeyup="verificaSenhas()" id="nSenha">
                                </div>
                                <div class="groupForm col-md-6 col-sm-12">
                                    <label for="nSenhaConf">Confirmação<i class="material-icons iconModify pointer" onclick="changeVisibility(this,'nSenhaConf')">visibility</i></label>
                                    <input type="password" name="nSenhaConf" onkeyup="verificaSenhas()" id="nSenhaConf">
                                </div>
                            </div>
                            <div class="row col-md-12">
                                <div class="groupForm col-md-6 col-md-offset-3 col-sm-12">
                                    <label for="senhaAtual">Senha Atual<i class="material-icons iconModify pointer" onclick="changeVisibility(this,'senhaAtual')">visibility</i></label>
                                    <input type="password" name="senhaAtual" id="senhaAtual">
                                </div>
                            </div>
                            <div class="row col-md-12" style="text-align: center;">
                                <button class="btnForm" onclick="changePassword()">Salvar <i class="material-icons" style="margin-left: 5px;">save</i></button>
                            </div>
                        </div>
                    </div>
                </div>
           </div> <!--Fim do rside-->
        </div>
        <span id="dialogContainer"></span>
        <!--FOOTER INICIA AQUI-->
        <footer>
        <div class="container">
            <div class="row">
            <div class="col-md-4 col-sm-6 footerleft ">
                <div class="logofooter"> Créditos de Imagem</div>
                <div>Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/vectors-market" title="Vectors Market">Vectors Market</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/popcorns-arts" title="Popcorns Arts">Popcorns Arts</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/flat-icons" title="Flat Icons">Flat Icons</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/roundicons" title="Roundicons">Roundicons</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/madebyoliver" title="Madebyoliver">Madebyoliver</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/prosymbols" title="Prosymbols">Prosymbols</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>
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
        </div>
        </div>
        <script src="js/usuario.js" type="text/javascript"></script>
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/materialize.min.js" type="text/javascript"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <?php
            if($is_mobile){
                echo "<script type='text/javascript' src='js/menu.js'></script>";
            }
        ?>
    </body>
</html>
