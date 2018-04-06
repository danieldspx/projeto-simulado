<?php
    require_once '../add/HeaderSession.php';
    if($_SESSION['usuario']['nivelAcessoId'] != 1){
       $_SESSION = array();
       session_destroy();
       header("Location: ../errorPagina");
       exit();
    }
    $logout = $_GET['slogout'];
    if($logout == '1'){
        $_SESSION = array();
        session_destroy();
    }
    if (empty($_SESSION['usuario'])) {
        session_start();
        $_SESSION['logout'] = "Logout efetuado com sucesso.";
        header("Location: ../principal");
    }

?>
<html>
    <head>
        <meta name="robots" content="noindex">
        <link href="../css/font-awesome.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link href="../css/footer.css" rel="stylesheet" type="text/css">
        <link rel="icon" href="../imagens/questaoicon.ico">
        <title>Cadastros</title>
        <meta charset="UTF-8">
        <link rel="sortcut icon" href="../img/favicon/trophy.png" type="image/png" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="../js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link href="../css/ranking.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <script src="../js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <link href="../css/multiple.css" rel="stylesheet">
        <script src="../js/multiple.min.js"></script>
        <style>
            @media (min-width: 320px){
                .item{
                    width: 100%;
                    left: 0px;
                }
            }
            @media (min-width: 420px){
                .item{
                    width: 80%;
                    left: 10%;
                }
            }
        </style>
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
                <a class="navbar-brand" title="Dados do Usuário" href="../usuario" style="font-family: Montserrant,sans-serif;font-weight: 600;color: #ecf0f1;"><span class="glyphicon glyphicon-user"></span>&nbsp; Olá, <?php echo $_SESSION['usuario']['nome']; ?>.</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="../questoes"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>&nbsp; Questões</a></li>
                    <li><a href="../ranking"><i class="fa fa-trophy" aria-hidden="true"></i>&nbsp; Ranking <span class="sr-only">(current)</span></a></li>
                    <li><a href="../desempenho"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp; Desempenho</a></li>
                    <li><a href="../filtro"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>&nbsp; Filtro</a></li>
                    <li><a href="../simulado"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>&nbsp; Simulado</a></li>
                    <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Links Úteis <span class="caret"></span></a>
                        <?php include "../linksUteis.add"; ?>
                    </li>
                </ul>
                <form class="navbar-form navbar-right" method="GET" action="">
                    <input value="1" name="slogout" style="display: none;">
                    <button type="submit" class="btn btn-primary btn-danger">Logout</button>
                </form>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
            </nav>
            <br><br>

        <!--COMEÇA A PAGINAÇÃO E AS QUESTOES, -->
        <?php
                if(isset($_SESSION['Error'])){
                    echo "<h2 class='sessao1'>".$_SESSION['Error']."</h2>";
                    unset($_SESSION['Error']);
                }
                if(isset($_SESSION['Success'])){
                    echo "<h2 class='sessao2'>".$_SESSION['Success']."</h2>";
                    unset($_SESSION['Success']);
                }
            ?>
            <?php
                require '../private_html_protected/configCadastro.php';
                require '../private_html_protected/connection.php';
                require '../private_html_protected/database.php';
                $cadastros = DBSearch("cadastro_previo","WHERE status = 0"); //Busca os Cadastros Previos
            ?>
            <?php if(empty($cadastros)){?>
                <div class="alert alert-danger" style="width: 40%; position: relative; left:30%; font-size: 15pt; text-align: center; margin-top: 50px;" role="alert"><strong>Não há nenhum registro de Cadastro.</strong></div>
            <?php };?>
            <?php if(isset($cadastros)){
                $i = 0;
                while(isset($cadastros[$i])){
                    $nome = $cadastros[$i]['nome'];
                    $sobrenome = trim($cadastros[$i]['sobrenome']);
                    $email = $cadastros[$i]['email'];
                    $senha = $cadastros[$i]['senha'];
                    $matricula = $cadastros[$i]['matricula'];
                    if(strlen($matricula>4) && substr($matricula,0,1)==0){
                        $matricula=substr($matricula,1);
                    }
                    $celular = $cadastros[$i]['celular'];
                    $instituicao = $cadastros[$i]['instituicao'];
                    $ano = $cadastros[$i]['ano'];
                    $plano = $cadastros[$i]['plano'];
                    if($plano==1){
                        $nivelAcesso = 4;
                    } else {
                        $nivelAcesso = 3;
                    }
                    $pagamento = $cadastros[$i]['pagamento'];
                    $iconePerfil = 'Cerebro.png';
                    echo "<div class=\"item\"><p class=\"textList col-md-9\"><sup class=\"classification\">".$cadastros[$i]['matricula']."&nbsp;</sup>&nbsp;".$cadastros[$i]['nome']." ".$cadastros[$i]['sobrenome']."</p><a class=\"textList pontuacao col-md-3\" data-nome='$nome' data-sobrenome='$sobrenome' data-email='$email' data-senha='$senha' data-matricula='$matricula' data-celular='$celular' data-instituicao='$instituicao' data-ano='$ano' data-plano='$plano' data-acesso='$nivelAcesso' data-pagamento='$pagamento' data-icone='$iconePerfil' onclick='insertIt(this)' title='Liberar' style=\"text-align: right; font-sixe: 25px; cursor: pointer;\"><span class='glyphicon glyphicon-save' aria-hidden='true'></span></a></div>";
                    $i++;
                }
            }?>
        <br><br>
        <script src="../js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.js"><\/script>')</script>
        <script src="../js/vendor/bootstrap.min.js"></script>
        <script>
            var multiple = new Multiple({
                selector: '.item',
                background: 'linear-gradient(#273463, #8B4256)',
                opacity: true
            });
            function updateIt(matricula){
                var page = "update_cadastroLiberado.php";
                $.ajax
                        ({
                            type: 'POST',
                            dataType: 'html',
                            url: page,
                            data: {matricula: matricula}
                        });
            }
            function insertIt(elem){
                var page = "add_usuario.php";
                $.ajax
                        ({
                            type: 'POST',
                            dataType: 'html',
                            url: page,
                            beforeSend: function () {
                                elem.innerHTML="<i class=\"fa fa-refresh fa-spin fa-fw\"></i><span class=\"sr-only\">Loading...</span>";
                            },
                            data: {nome: elem.dataset.nome, sobrenome: elem.dataset.sobrenome, senha: elem.dataset.senha, iconePerfil: elem.dataset.icone, email: elem.dataset.email, acesso: elem.dataset.acesso, matricula: elem.dataset.matricula, celular: elem.dataset.celular, instituicao: elem.dataset.instituicao, ano: elem.dataset.ano, metodo_pgt: elem.dataset.pagamento, plano: elem.dataset.plano},
                            success: function (msg)
                            {
                                elem.innerHTML="<span class='glyphicon glyphicon-saved' aria-hidden='true'></span";
                                elem.setAttribute('onclick','');
                                updateIt(elem.dataset.matricula);
                            }
                        });
            }
        </script>
    </body>
</html>
