<?php
    require_once '../add/HeaderSession.php';
    if($_SESSION['usuario']['nivelAcessoId'] != 1 && $_SESSION['usuario']['nivelAcessoId'] != 2){
       $_SESSION = array();
       session_destroy();
       header("Location: ../errorPagina");
    }
    $logout = $_GET['slogout'];
    if($logout == '1'){
        $_SESSION = array();
        session_destroy();
        header("Location: ../principal");
    }
    include_once '../private_html_protected/config.php';
    include_once '../private_html_protected/connection.php';
    include_once '../private_html_protected/database.php';
    if(isset($_SESSION['usuario'])){
        $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
        if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
            header("Location: ../LoginSimultaneo.php");
        }
    }
    $ano = $_POST['anoProva'];
    $numero = $_POST['numQuestao'];
    $mesProva = $_POST['mesProva'];
    if(empty($ano) || empty($numero)){
        header("Location: buscar_questaoalterar.html");
        exit();
    }
    $busca = DBSearch("questoes_simulado","WHERE id_simulado = '".$mesProva.$ano."' AND numero = $numero;");

?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
    <head>
        <link rel="stylesheet" href="../css/reset.css">
        <link href="https://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Simulado Cedaf</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="sortcut icon" href="../img/checked.png" type="image/png" />
        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/index.js"></script>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <script src="../js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body id="bodyElement">
    <div class="container-fluid">
        <header class="row">
        </header>
        <div class="row" style="margin: 30px 0px;">
            <div role="main" class="col-md-12 col-xs-12">
                <form class="form-horizontal" action="add_questaoSimulado.php" method="POST">
                    <fieldset>

                    <!-- Form Name -->
                    <legend>Alterar Questão</legend>

                    <!-- Text input-->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="id">ID</label>
                    <div class="col-md-1">
                    <input id="id" name="idquestao" type="number" readonly placeholder="" <?php echo "value=\"".$busca[0]['idquestao']."\"";?> class="form-control input-md" required="">

                    </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="numQuestao">Número</label>
                    <div class="col-md-1">
                    <input id="numQuestao" name="numQuestao" type="number" placeholder="" <?php echo "value=\"".$busca[0]['numero']."\"";?> class="form-control input-md" required="">

                    </div>
                    </div>

                    <!-- Text input-->

                    <!-- Textarea -->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="enunciado">Enunciado</label>
                    <div class="col-md-6">
                        <textarea class="form-control" id="enunciado" name="enunciado" rows="10"><?php echo $busca[0]['descricao'];?></textarea>
                    </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="area">Área</label>
                    <div class="col-md-6">
                        <select id="area" name="area" class="form-control">
                        <?php
                            $string = "<option value=\"1\">Matemática e suas Tecnologias</option> <option value=\"2\">Ciências da Natureza e suas Tecnologias</option> <option value=\"4\">Linguagens, Códigos e suas Tecnologias</option> <option value=\"3\">Ciências Humanas e suas Tecnologias</option>";
                            $string = str_replace("value=\"".$busca[0]['areas_idarea']."\"","value=\"".$busca[0]['areas_idarea']."\" selected",$string);
                            echo $string;
                        ?>
                        </select>
                    </div>
                    </div>
                    

                    <!-- Textarea -->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="textarea">A</label>
                    <div class="col-md-4">
                        <textarea class="form-control" id="alternativaA" name="alternativaA"><?php echo $busca[0]['alternativa_a'];?></textarea>
                    </div>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="alternativaB">B</label>
                    <div class="col-md-4">
                        <textarea class="form-control" id="alternativaB" name="alternativaB"><?php echo $busca[0]['alternativa_b'];?></textarea>
                    </div>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="alternativaC">C</label>
                    <div class="col-md-4">
                        <textarea class="form-control" id="alternativaC" name="alternativaC"><?php echo $busca[0]['alternativa_c'];?></textarea>
                    </div>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="alternativaD">D</label>
                    <div class="col-md-4">
                        <textarea class="form-control" id="alternativaD" name="alternativaD"><?php echo $busca[0]['alternativa_d'];?></textarea>
                    </div>
                    </div>

                    <!-- Textarea -->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="alternativaE">E</label>
                    <div class="col-md-4">
                        <textarea class="form-control" id="alternativaE" name="alternativaE"><?php echo $busca[0]['alternativa_e'];?></textarea>
                    </div>
                    </div>

                    <!-- Select Basic -->
                    <div class="form-group">
                    <label class="col-md-4 control-label" for="alternativaCorreta">Alternativa Correta</label>
                    <div class="col-md-1">
                        <select id="alternativaCorreta" name="alternativaCorreta" class="form-control">
                            <?php
                                $string = "<option value=\"1\">A</option> <option value=\"2\">B</option> <option value=\"3\">C</option> <option value=\"4\">D</option> <option value=\"5\">E</option>";
                                $string = str_replace("value=\"".$busca[0]['alternativa_correta']."\"","value=\"".$busca[0]['alternativa_correta']."\" selected",$string);
                                echo $string;
                            ?>
                        </select>
                    </div>
                    </div>
                    <input type="hidden" name="id_simulado" <?php echo "value=\"".$busca[0]['id_simulado']."\"";?> />
                    <input type="hidden" name="source" value="UPDATE"/>
                    <div style="text-align: center;">
                        <br><br>
                        <button class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-floppy-saved"></span> Salvar Alterações</button>
                    </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
    <script src="../js/jquery.js"></script>
    <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.js"><\/script>')</script>
    <script src="../js/vendor/bootstrap.min.js"></script>
    </body>
</html>
