<?php
    require_once '../add/HeaderSession.php';
    $logout = $_GET['slogout'];
    if($logout == '1'){
        $_SESSION = array();
        session_destroy();
    }
    if($_SESSION['usuario']['nivelAcessoId'] != 1){
        $_SESSION = array();
        session_destroy();
        header("Location: ../errorPagina");
    }
    if (empty($_SESSION['usuario'])) {
        session_start();
        $_SESSION['logout'] = "Logout efetuado com sucesso.";
        header("Location: ../principal");
    }
?>
<html>
    <head>
        <title>Adicionar Usuário</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <style>
            @import url('https://fonts.googleapis.com/css?family=Francois+One');
            a.topo{
                text-decoration: none;
                cursor: default;
                color: black;
                font-weight: 700;
                font-family: arial, sans-serif
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
            .btnfiltro {
                background: #34d9b8;
                background-image: -webkit-linear-gradient(top, #34d9b8, #2bb8a0);
                background-image: -moz-linear-gradient(top, #34d9b8, #2bb8a0);
                background-image: -ms-linear-gradient(top, #34d9b8, #2bb8a0);
                background-image: -o-linear-gradient(top, #34d9b8, #2bb8a0);
                background-image: linear-gradient(to bottom, #34d9b8, #2bb8a0);
                -webkit-border-radius: 10;
                -moz-border-radius: 10;
                border-radius: 10px;
                font-family: Arial;
                color: #ffffff;
                font-size: 24px;
                padding: 10px 20px 10px 20px;
                text-decoration: none;
                border: 0 none;
              }

              .btnfiltro:hover {
                background: #3cfcbc;
                background-image: -webkit-linear-gradient(top, #3cfcbc, #34d9aa);
                background-image: -moz-linear-gradient(top, #3cfcbc, #34d9aa);
                background-image: -ms-linear-gradient(top, #3cfcbc, #34d9aa);
                background-image: -o-linear-gradient(top, #3cfcbc, #34d9aa);
                background-image: linear-gradient(to bottom, #3cfcbc, #34d9aa);
                text-decoration: none;
              }
            .botao {
                border: 0 none;
            }
            .enunciado{
                font-size: 20px;
            }
            .filtro{
                margin-top: 150px;
                width: 50%;
                margin-left: auto;
                margin-right: auto;
                padding: 20px 20px 20px 50px;
                background-color: rgba(245,255,250,0.7);
                box-shadow: 3px 3px 3px rgba(0,0,0,0.7);
                margin-bottom: 100px;
            }
            legend{
                font-family: 'Francois One', sans-serif;
                font-size: 50px;
            }
            label{
                font-family: Arial, sans-serif;
                font-size: 25px;
            }
            span.ano{
                font-family: Arial, sans-serif;
                font-size: 20px;
                font-weight: 600;
                padding: 10px 15px 10px 4px;
            }
            span.area,span.disciplina{
                font-family: Arial, sans-serif;
                font-size: 20px;
                font-weight: 600;
                padding: 10px 15px 10px 4px;
            }
            .navbar-brand{
                font-size: 30px;
            }
            @media screen and (max-width: 480px) {
                .navbar-brand{
                    font-size: 20px;
                }
                .filtro{
                margin-top: auto;
                width: 75%;
                margin-left: auto;
                margin-right: auto;
                padding: auto auto auto auto;
                background-color: rgba(245,255,250,0.7);
                box-shadow: 3px 3px 3px rgba(0,0,0,0.7);
                margin-bottom: 10px;
                }
                legend{
                    font-family: 'Francois One', sans-serif;
                    font-size: 30px;
                }
                label{
                    font-family: Arial, sans-serif;
                    font-size: 25px;
                }
                span.ano{
                    font-family: Arial, sans-serif;
                    font-size: 15px;
                    font-weight: 600;
                    padding: 10px 15px 10px 4px;
                }
                span.area,span.disciplina{
                    font-family: Arial, sans-serif;
                    font-size: 15px;
                    font-weight: 600;
                    padding: auto auto auto auto;
                }


            }
            body{
                background-image: linear-gradient(to bottom, #800080, #7B68EE);
                width: 100%;
                height: 100%;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }
        </style>

        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <script src="../js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
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
            <form class="navbar-form navbar-right" role="form" method="GET" action="">
            <div class="form-group">
                <a href="../ranking" title="Ranking do Mês"><img src="../img/ranking.png" width="65px" style="position: absolute; margin-left: -600px; margin-top: -35px;"/></a>
                <a href="../desempenho" title="Desempenho"><img src="../img/graphics.png" width="65px" style="position: absolute; margin-left: -500px; margin-top: -35px;"/></a>
                <a href="../questoes" title="Resolva as questões"><img src="../img/question.png" width="65px" style="position: absolute; margin-left: -400px; margin-top: -35px;"/></a>
                <a href="../principal" title="Pagina Inicial"><img src="../img/backpack.png" width="65px" style="position: absolute; margin-left: -300px; margin-top: -35px;"/></a>
                <a href="simulado/simulado.html" title="Simulado - Díponivel em dias determinados"><img src="../img/simulado.png" width="65px" style="position: absolute; margin-left: -200px; margin-top: -35px;"/></a>
                <a href="http://www.portal.ufv.br/florestal/" target="_BLANK" title="Pagina Cedaf - Externo"><img src="../img/school.png" width="65px" style="position: absolute; margin-left: -100px; margin-top: -35px;"/></a>
                <a href="../usuario" title="Configurações do Usuário"><img src="../<?php echo $_SESSION['usuario']['foto']; ?>" width="80px" style="position: absolute; margin-left: 120px;"/></a>
            </div>
                <input value="1" name="slogout" style="display: none;"/>
              <button type="submit" class="btnlogout">Logout</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

        <!--COMEÇA A PAGINAÇÃO E AS QUESTOES, -->
        <form class="filtro form-horizontal" method="POST" action="add_usuario.php">
            <fieldset>
                <!-- Form Name -->
                <legend>Cadastro de Usuário</legend>

                <!-- Select Basic -->
                <div class="form-group">
                <label class="col-md-4 control-label" for="acesso">Acesso</label>
                <div class="col-md-4">
                    <select id="acesso" name="acesso" class="form-control">
                    <option value="3">Normal</option>
                    <option value="4">Grátis</option>
                    <option value="2">Colaborador</option>
                    </select>
                </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="nome">Nome</label>
                <div class="col-md-5">
                <input id="nome" name="nome" type="text" placeholder="" class="form-control input-md" required="">

                </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="sobrenome">Sobrenome</label>
                <div class="col-md-5">
                <input id="sobrenome" name="sobrenome" type="text" placeholder="" class="form-control input-md" required="">

                </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="email">E-mail</label>
                <div class="col-md-5">
                <input id="email" name="email" type="text" placeholder="" class="form-control input-md" required="">

                </div>
                </div>
                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="senha">Senha</label>
                <div class="col-md-5">
                <input id="senha" name="senha" type="text" placeholder="" class="form-control input-md" required="">

                </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="matricula">Matrícula</label>
                <div class="col-md-4">
                <input id="matricula" name="matricula" type="number" placeholder="" class="form-control input-md" required="">

                </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="celular">Celular</label>
                <div class="col-md-4">
                <input id="celular" name="celular" type="text" placeholder="" class="form-control input-md" required="">

                </div>
                </div>

                <!-- Select Basic -->
                <div class="form-group">
                <label class="col-md-4 control-label" for="instituicao">Instituição</label>
                <div class="col-md-4">
                    <select id="instituicao" name="instituicao" class="form-control" required="">
                    <option value=""></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    </select>
                </div>
                </div>

                <!-- Select Basic -->
                <div class="form-group">
                <label class="col-md-4 control-label" for="ano">Ano</label>
                <div class="col-md-4">
                    <select id="ano" name="ano" class="form-control">
                    <option value="1">1º Ano</option>
                    <option value="2">2º Ano</option>
                    <option value="3">3° Ano</option>
                    </select>
                </div>
                </div>

                <div class="form-group">
                <label class="col-md-4 control-label" for="plano_pgt">Plano</label>
                <div class="col-md-4">
                    <select id="plano_pgt" name="plano_pgt" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    </select>
                </div>
                </div>

                <!-- Select Basic -->
                <div class="form-group">
                <label class="col-md-4 control-label" for="metodo_pgt">Pagamento</label>
                <div class="col-md-4">
                    <select id="metodo_pgt" name="metodo_pgt" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    </select>
                </div>
                </div>

                <!-- Select Basic -->
                <div class="form-group">
                <label class="col-md-4 control-label" for="iconePerfil">Ícone de Perfil</label>
                <div class="col-md-4">
                    <select id="iconePerfil" name="iconePerfil" class="form-control">
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
                    <option value="HomemAprendendo.png">Inteligência Artificial</option>
                    <option value="Inteligencia Artificial.png">Mulher lendo</option>
                    <option value="Jato.png">Robocop</option>
                    <option value="Mulher.png">Sistema Solar</option>
                    <option value="MulherAprendendo.png">Via Láctea</option>
                    </select>
                </div>
                </div>
                </fieldset>
                <br><br><br><br>
                <input type="submit" class="btnfiltro" value="Cadastrar">
        </form>

        <script src="js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>

    </body>
</html>
