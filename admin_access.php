<?php
    require_once 'add/HeaderSession.php';
    if($_SESSION['usuario']['nivelAcessoId'] != 1){
       $_SESSION = array();
       session_destroy();
       header("Location: errorPagina");
    }
    $logout = $_GET['slogout'];
    if($logout == '1'){
        $_SESSION = array();
        session_destroy();
    }
    if (empty($_SESSION['usuario'])) {
        session_start();
        $_SESSION['logout'] = "Logout efetuado com sucesso.";
        header("Location: index.php");
    }
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <html>
    <head>
        <meta name="robots" content="noindex">
        
        <title>Administrador</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            @import url('https://fonts.googleapis.com/css?family=Francois+One');
            .btnlogout {
                background: #d93434;
                background-image: -webkit-linear-gradient(top, #d93434, #b82b2b);
                background-image: -moz-linear-gradient(top, #d93434, #b82b2b);
                background-image: -ms-linear-gradient(top, #d93434, #b82b2b);
                background-image: -o-linear-gradient(top, #d93434, #b82b2b);
                background-image: linear-gradient(to bottom, #d93434, #b82b2b);
                -webkit-border-radius: 7;
                -moz-border-radius: 7;
                border-radius: 7px;
                font-family: Arial;
                color: #ffffff;
                font-size: 20px;
                padding: 10px 20px 10px 20px;
                text-decoration: none;
                height: 50px;
                border: 0 none;
              }
            body {
                width: 100%;
                height: 100%;
            }
            table#adm{
                text-align: center;
                width: 1250px;
                height: 150px;
                border: none;
            }
            .btned1 {
                background: #3498db;
                background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
                background-image: -moz-linear-gradient(top, #3498db, #2980b9);
                background-image: -ms-linear-gradient(top, #3498db, #2980b9);
                background-image: -o-linear-gradient(top, #3498db, #2980b9);
                background-image: linear-gradient(to bottom, #3498db, #2980b9);
                -webkit-border-radius: 9;
                -moz-border-radius: 9;
                border-radius: 9px;
                -webkit-box-shadow: 6px 6px 7px #666666;
                -moz-box-shadow: 6px 6px 7px #666666;
                box-shadow: 6px 6px 7px #666666;
                font-family: Arial;
                color: #ffffff;
                font-size: 25px;
                padding: 15px 25px 15px 25px;
                text-decoration: none;
              }

            .btned1:hover {
                background: #41b0f0;
                background-image: -webkit-linear-gradient(top, #41b0f0, #1a85cc);
                background-image: -moz-linear-gradient(top, #41b0f0, #1a85cc);
                background-image: -ms-linear-gradient(top, #41b0f0, #1a85cc);
                background-image: -o-linear-gradient(top, #41b0f0, #1a85cc);
                background-image: linear-gradient(to bottom, #41b0f0, #1a85cc);
                text-decoration: none;
              }
            .btned2 {
                background: #e62b2b;
                background-image: -webkit-linear-gradient(top, #e62b2b, #c71e1e);
                background-image: -moz-linear-gradient(top, #e62b2b, #c71e1e);
                background-image: -ms-linear-gradient(top, #e62b2b, #c71e1e);
                background-image: -o-linear-gradient(top, #e62b2b, #c71e1e);
                background-image: linear-gradient(to bottom, #e62b2b, #c71e1e);
                -webkit-border-radius: 9;
                -moz-border-radius: 9;
                border-radius: 9px;
                -webkit-box-shadow: 6px 6px 7px #666666;
                -moz-box-shadow: 6px 6px 7px #666666;
                box-shadow: 6px 6px 7px #666666;
                font-family: Arial;
                color: #ffffff;
                font-size: 25px;
                padding: 15px 25px 15px 25px;
                text-decoration: none;
              }

            .btned2:hover {
                background: #ff4f4f;
                background-image: -webkit-linear-gradient(top, #ff4f4f, #d91c1c);
                background-image: -moz-linear-gradient(top, #ff4f4f, #d91c1c);
                background-image: -ms-linear-gradient(top, #ff4f4f, #d91c1c);
                background-image: -o-linear-gradient(top, #ff4f4f, #d91c1c);
                background-image: linear-gradient(to bottom, #ff4f4f, #d91c1c);
                text-decoration: none;
            }
            .navbar-brand{
                font-size: 30px;
            }
            @media screen and (max-width: 480px) {
                .navbar-brand{
                    font-size: 20px;
                }
            }
            @media screen{
                td#cabecalho{
                    padding: 0px 30px 0px 0px;
                }
                table#cabecalhoUsuario{
                    position: absolute;
                    margin-left: 85%;
                    margin-top: 20px;
                    box-shadow: none;
                }
                table#cabecalhoLinks{
                    margin-left: 50px;
                    box-shadow: none;
                }
            }

            @media (max-width: 480px){
                td#cabecalho{
                    padding-right: 15px;
                }
                table#cabecalhoUsuario{
                    position: absolute;
                    margin-left: 50%;
                    box-shadow: none;
                }
                table#cabecalhoLinks{
                    margin-left: 0px;
                    box-shadow: none;
                }
            }
        </style>
        <script>
            function dearLordIn(administrador){
                administrador.innerHTML = "Welcome to your home, my Lord.";
            }
            function dearLordOut(administrador){
                administrador.innerHTML = "Administrador";
            }
        </script>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body style="background-color: #fafafa">

        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <nav class="navbar navbar-inverse navbar-fixed-top" style="position: static;" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

            <a class="navbar-brand">Usuário: <?php echo $_SESSION['usuario']['nome']; ?>.</a><div class="nomeusuario"></div>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
             <table id="cabecalhoLinks" class="nav navbar-nav navbar-left">
                <tr>
                    <td id="cabecalho"><a href="ranking" title="Ranking do Mês"><img id="rankingIMG" src="img/ranking.png" width="65px"/></a></td>
                    <td id="cabecalho"><a href="desempenho" title="Desempenho"><img src="img/graphics.png" width="65px"/></a></td>
                    <td id="cabecalho"><a href="principal" title="Pagina Inicial"><img src="img/backpack.png" width="65px"/></a></td>
                    <td id="cabecalho"><a href="questoes" title="Resolva as questões"><img src="img/question.png" width="65px"/></a></td>
                    <td id="cabecalho"><a href="simulado/simulado.html" title="Simulado - Díponivel em dias determinados"><img src="img/simulado.png" width="65px"/></a></td>
                    <td id="cabecalho"><a href="http://www.portal.ufv.br/florestal/" target="_BLANK" title="Pagina Cedaf - Externo"><img src="img/school.png" width="65px"/></a></td>
                </tr>
            </table>
            <table id="cabecalhoUsuario" class="nav navbar-nav navbar-right">
                <tr>
                    <td><a href="/usuario" title="Configurações do Usuário"><img src="<?php echo $_SESSION['usuario']['foto']; ?>" width="80px"/></a></td>
                </tr>
            </table>
            <form class="navbar-form navbar-right" role="form" method="GET" action="">
                <input value="1" name="slogout" style="display: none;"/>
                <button type="submit" class="btnlogout">Logout</button>
            </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

        <!--COMEÇA A PAGINAÇÃO E AS QUESTOES, -->

       <div class="jumbotron">
      <div class="container">
          <h1 id="admin" onmouseover="dearLordIn(this)" onmouseout="dearLordOut(this)">Administrador</h1><img src="img/admin.png" width="100px" height="100px"/>
        <br><br><br><br>
            <a href="pages_adm/adicionar_usuario.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-user"></span> Adicionar Usuário</a>
             <a href="pages_adm/liberarCadastro.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-user"></span> Liberar Usuário</a>
            <a href="pages_adm/adicionar_questao.php" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-floppy-open"></span> Adicionar Questão</a>
            <a href="pages_adm/adicionar_questaoEspecial.html" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-floppy-open"></span> Adicionar Questão - INGLÊS/ESPANHOL</a>
            <a href="pages_adm/adicionar_questaoSimulado.php" class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-floppy-open"></span> Adicionar Questões Simulado</a>
            <a href="https://html-online.com/editor/" target="_BLANK" class="btn btn-lg btn-danger">EDITOR HTML</a>
            <a href="pages_adm/buscar_questaoalterar.html" class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-pencil"></span> Alterar Questões</a>
            <a href="pages_adm/notification.php" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-user"></span> Enviar Mensagem - Broadcast</a>
      </div>
    </div>

      <hr>

        <script src="js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>

    </body>
</html>
