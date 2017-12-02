<?php
    require_once 'add/HeaderSession.php';
    $logout = $_GET['slogout'];
    if($logout == '1' || ($_SESSION['usuario']['nivelAcessoId'] != 1 && $_SESSION['usuario']['nivelAcessoId'] != 2)){
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
        header("Location: LoginSimultaneo.php");
    }
?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <meta name="robots" content="noindex">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Simulador</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
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

              .btnlogout:hover {
                background: #fc3c3c;
                background-image: -webkit-linear-gradient(top, #fc3c3c, #d93434);
                background-image: -moz-linear-gradient(top, #fc3c3c, #d93434);
                background-image: -ms-linear-gradient(top, #fc3c3c, #d93434);
                background-image: -o-linear-gradient(top, #fc3c3c, #d93434);
                background-image: linear-gradient(to bottom, #fc3c3c, #d93434);
                text-decoration: none;
            }
            .nomeusuario{
                position: absolute;
                color: red;
                margin-top: 65px;
                font-weight: 700;
                font-size: 20px;
            }

        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">

        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

            <a class="navbar-brand" style="font-size: 30px;">Seja Bem-Vindo de volta ao lar.</a><div class="nomeusuario"><?php echo $_SESSION['usuario']['nome']; ?></div>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <form class="navbar-form navbar-right" role="form" method="GET" action="admin_access.php">
            <div class="form-group">
                <img src="img/admin_id.png" width="80px" style="position: absolute; margin-left: 120px;"/>
                <a href="questoes" target="_BLANK"><img src="img/Einstein.png" width="80px" style="position: absolute; margin-left: -100px;"/></a>
            </div>
                <input value="1" name="slogout" style="display: none;"/>
              <button type="submit" class="btnlogout">Logout</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
          <h1>Colaborador</h1>
          <img src="img/helpmate.png"/><br/><br/><br/>
          &lt;img src=&quot;img_questoes/qN_ANO.png&quot;/&gt;<br>
          &lt;img src=&quot;img_questoes/alternativas/qNx_ANO.png&quot;/&gt;
          <br><br><br><br>
          <a href="pages_adm/adicionar_questao.php" class="btn btn-lg btn-info" target="_BLANK"><span class="glyphicon glyphicon-floppy-open"></span> Adicionar Questão</a>
          <a href="pages_adm/buscar_questaoalterar.html" class="btn btn-lg btn-info" target="_BLANK"><span class="glyphicon glyphicon-pencil"></span> Alterar Questões</a>
          <a href="pages_adm/adicionar_questaoSimulado.php" class="btn btn-lg btn-warning" target="_BLANK"><span class="glyphicon glyphicon-floppy-open"></span> Adicionar Questões (SIMULADO)</a>
          <a href="pages_adm/buscar_questaoalterarSimulado.html" class="btn btn-lg btn-warning" target="_BLANK"><span class="glyphicon glyphicon-pencil"></span> Alterar Questões (SIMULADO)</a>
          <a href="busca_questaosimuladoColaborador.php" class="btn btn-lg btn-warning" target="_BLANK"> Verificar Questões Adicionadas (SIMULADO)</a>
      </div>
    </div>


      <hr>

      <footer>
        <p>&copy; Simulado Cedaf 2017</p>
      </footer>
    </div> <!-- /container -->        <script src="js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <!--ALOOOscript>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script-->
    </body>
</html>
