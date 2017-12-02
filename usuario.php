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
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        unset($resposta);
        header("Location: LoginSimultaneo.php");
    }
    unset($resposta);
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"matricula,celular,instituicao,ano");
    if($resposta[0]['instituicao']==1){
        $resposta[0]['instituicao']="Centro de Ensino e Desenvolvimento Agrário de Florestal";
    } else {
        $resposta[0]['instituicao']="Escola Estadual Serafim Ribeiro de Rezende";
    }
    //Detecta Mobile
    include_once 'mobile_detect/Mobile_Detect.php';
    $detect = new Mobile_Detect;
    $is_mobile=$detect->isMobile();
?>
<html>
    <head>
        <meta name="robots" content="noindex">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/footer.css" rel="stylesheet" type="text/css">
        
        <title>Usuário</title>
        <meta charset="UTF-8">
        <link rel="sortcut icon" href="img/favicon/user-silhouette.png" type="image/png" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link href="css/usuariosStyle.css" rel="stylesheet" type="text/css"/>
        <script src="js/usuarioFunctions.js" type="text/javascript"></script>        
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <?php
            if($is_mobile){
                echo "<link href='css/styleMenu.css' rel='stylesheet'>";
                echo "<link href='css/animateMenu.css' rel='stylesheet'>";   
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
        <?php
                if(isset($_SESSION['Error'])){
                    echo "<div id='dialogBox' class='sessao1' style='position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>".$_SESSION['Error']."</p></div>";
                    unset($_SESSION['Error']);
                    echo "<script>setTimeout(closeAlert,7000);</script>";
                }
                if(isset($_SESSION['Success'])){
                    echo "<div id='dialogBox' class='sessao2' style='position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>".$_SESSION['Success']."</p></div>";
                    unset($_SESSION['Success']);
                    echo "<script>setTimeout(closeAlert,7000);</script>";
                }
        ?>
        <form class="userForm" method="POST" action="valida/salvadados.php" style="margin-top: 90px;">
            <fieldset><legend><img src="<?php echo $_SESSION['usuario']['foto']; ?>" width="70px"/>&nbsp;&nbsp;&nbsp;Dados Pessoais</legend>
                <table class="nome inline">
                    <tr>
                        <td>
                            <label>Nome</label><br>
                            <input type="text" name="nNome" value="<?php echo $_SESSION['usuario']['nome'];?>"/>
                        </td>
                    </tr>
                </table>
                <table class="nome inline right">
                    <tr >
                        <td>
                            <label>Sobrenome</label><br>
                            <input type="text" name="nSobrenome" value="<?php echo $_SESSION['usuario']['sobrenome'];?>"/>
                        <td>
                    </tr>
                </table>
                
                <hr>
                <table class="dados inline">
                    <tr>
                        <td>
                            <label>E-mail</label><br>
                            <input type="text" name="nEmail" value="<?php echo $_SESSION['usuario']['email'];?>" readonly="readonly"/>
                        </td>
                    </tr>
                </table>
                <table class="dados inline right">
                    <tr>
                        <td>
                            <label>Ícone de Perfil</label><span class="sub">(Preview no final da página)</span><br>
                            <select name="iconePerfil">
                                <option value="null">  </option>
                                <option value="Aluna.png">Aluna</option>
                                <option value="Aluno.png">Aluno</option>
                                <option value="Astronauta.png">Astronauta</option>
                                <option value="Atomo.png">Átomo</option>
                                <option value="Cerebro.png">Cérebro</option>
                                <option value="Certificado.png">Certificado</option>
                                <option value="Doutor.png">Doutor</option>
                                <option value="Doutora.png">Doutora</option>
                                <option value="Einstein.png">Einstein</option>
                                <option value="Foguete.png">Foguete</option>
                                <option value="Ghost.png">Ghost</option>
                                <option value="Homem.png">Homem</option>
                                <option value="HomemAprendendo.png">Homem lendo</option>
                                <option value="Inteligencia Artificial.png">Inteligência Artificial</option>
                                <option value="Jato.png">Jato</option>
                                <option value="Mulher.png">Mulher</option>
                                <option value="MulherAprendendo.png">Mulher lendo</option>
                                <option value="Robocop.png">Robocop</option>
                                <option value="SistemaSolar.png">Sistema Solar</option>
                                <option value="ViaLactea.png">Via Láctea</option>
                            </select>
                        </td>
                    </tr>
                </table>
                 <table class="dados inline">
                    <tr>
                        <td>
                            <label>Matrícula</label><br>
                            <input type="number" name="nMatricula"  value="<?php echo $resposta[0]['matricula'];?>" readonly="readonly"/>
                        </td>
                    </tr>
                </table>
                <table class="dados inline right">
                    <tr>
                        <td>
                            <label>Celular</label><br>
                            <input id="nCelular" name="nCelular" type="text" value="<?php echo $resposta[0]['celular'];?>" onkeydown="MaskDown(this)" onkeyup="MaskUp(this,event,'(##) #####-####')" class="form-control input-md">
                        </td>
                    </tr>
                </table>
                <hr>
                <table class="dados" style="width: 100%;">
                    <tr>
                        <td>
                            <label>Instituição</label><br>
                            <input type="text" name="nMatricula" id="nMatricula"  value="<?php echo $resposta[0]['instituicao'];?>" readonly="readonly"/>
                        </td>
                    </tr>
                </table>
                <hr>
                <table class="dados inline">
                    <tr>
                        <td>
                            <label>Ano</label><br>
                            <input type="text" name="nInstituicao" id="nInstituicao" value="<?php echo $resposta[0]['ano']."º Ano do Ensino Médio";?>" readonly="readonly" onkeydown="MaskDown(this)" onkeyup="MaskUp(this,event,'(##) #####-####')"/>
                        </td>
                    </tr>
                </table>
                <hr>
                <table class="senha inline">
                    <tr>
                        <td>
                            <label>Alterar Senha</label><br>
                            <input type="password" id="senha" name="nSenha" placeholder="Digite a nova senha" onchange="verificaSenhas()"/>
                        </td>
                    </tr>
                </table>
                <table class="senha inline right">
                    <tr>
                        <td>
                            <label>Confirmação<span id="idSenha" class="sub"></span></label><br>
                            <input type="password" id="senhaConfirm" name="nSenhaConfirm" placeholder="Confirme a nova senha" onchange="verificaSenhas()"/>
                        </td>
                    </tr>
                </table> 
                <hr>
                <table class="senhaAtual">
                    <tr>
                        <td>
                            <label>Senha Atual - Para salvar as configurações<span class="sub"></span></label><br>
                            <input type="password" id="senhaConfirm" name="nSenhaAtual" placeholder="Digite a senha Atual"/>
                            <p style="color: rgba(0,0,0,0.5); font-weight: 700;"><br>Se a senha estiver errada<br>nada será salvo.</p>
                        </td>
                    </tr>
                </table>
                <hr>
                <input style="margin: 0 0 10px 10px;" class="btnsalvar" type="submit" value="Salvar Dados">               
            </fieldset>
        </form>
        <div id="view">preview da foto de Perfil (Passe o mouse sobre a foto)</div>
        <ul id="album-fotos">
		<li id="foto01"><span>Aluna</span></li>
		<li id="foto02"><span>Aluno</span></li>
		<li id="foto03"><span>Astronauta</span></li>
		<li id="foto04"><span>Átomo</span></li>
		<li id="foto05"><span>Cérebro</span></li>
		<li id="foto06"><span>Certificado</span></li>
                <li id="foto07"><span>Doutor</span></li>
                <li id="foto08"><span>Doutora</span></li>
                <li id="foto09"><span>Einstein</span></li>
                <li id="foto010"><span>Foguete</span></li>
                <li id="foto011"><span>Ghost</span></li>
                <li id="foto012"><span>Homem</span></li>
                <li id="foto013"><span>Homem lendo</span></li>
                <li id="foto014"><span>Inteligência Artificial</span></li>
                <li id="foto015"><span>Jato</span></li>
                <li id="foto016"><span>Mulher</span></li>
                <li id="foto017"><span>Mulher lendo</span></li>
                <li id="foto018"><span>Robocop</span></li>
                <li id="foto019"><span>Sistema Solar</span></li>
                <li id="foto020"><span>Via Láctea</span></li>
	</ul>
    <!--FOOTER INICIA AQUI-->
    <br><br><br><br><br><br>
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
