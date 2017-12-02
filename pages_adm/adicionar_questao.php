<?php
    require_once '../add/HeaderSession.php';
    if($_SESSION['usuario']['nivelAcessoId'] != 1 && $_SESSION['usuario']['nivelAcessoId'] != 2){
       $_SESSION = array();
       session_destroy();
       header("Location: ../errorPagina");
       exit();
   }
?>
<!DOCTYPE html>
<!--
Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.md or http://ckeditor.com/license
-->
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../css/reset.css">
        <link href="https://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Adicionar Questões</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="sortcut icon" href="../img/checked.png" type="image/png" />
        <script src="../js/jquery-1.11.1.min.js"></script>
        <script src="../js/index.js"></script>
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <script src="../js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script src="ckeditor.js"></script>
        <script src="js/sample.js"></script>
        <link rel="stylesheet" href="css/samples.css">
        <link rel="stylesheet" href="toolbarconfigurator/lib/codemirror/neo.css">
    </head>
    <body>
        <div class="alert alert-warning" role="alert"><strong>Imagem no Enunciado(utilize somete o conteudo do 'src' sem as aspas duplas) :</strong> &lt;img src=&quot;../img_questoes/qN_ANO.png&quot;/&gt;</div>
        <div class="alert alert-warning" role="alert"><strong>Imagem na Alternativa:</strong> &lt;img src=&quot;../img_questoes/alternativas/qNx_ANO.png&quot;/&gt;</div>
        <form class="form-horizontal" action="add_questao.php" method="POST">
            <fieldset>

            <!-- Form Name -->
            <legend>Adicionar Questão</legend>

            <!-- Text input-->
            <div class="form-group">
            <label class="col-md-2 control-label" for="numQuestao">Número da Questão: </label>
            <div class="col-md-2">
            <input id="numQuestao" name="numQuestao" type="number" placeholder="Ex: 23" class="form-control input-md" required="">

            </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
            <label class="col-md-2 control-label" for="anoProva">Ano da prova:</label>
            <div class="col-md-2">
            <input id="anoProva" name="anoProva" type="number" placeholder="Ex: 2016" class="form-control input-md" required="">

            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="enunciado">Enunciado:</label>
            <div class="col-md-8">
                <textarea class="form-control" id="editor" name="enunciado" required=""></textarea>
            </div>
            </div>

            <!-- Select Basic -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="area">Área:</label>
            <div class="col-md-6">
                <select id="area" name="area" class="form-control" required="">
                <option value=""></option>
                <option value="1">Matemática e suas Tecnologias</option>
                <option value="2">Ciências da Natureza e suas Tecnologias</option>
                <option value="4">Linguagens, Códigos e suas Tecnologias</option>
                <option value="3">Ciências Humanas e suas Tecnologias</option>
                </select>
            </div>
            </div>

            <!-- Select Basic -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="disciplina">Disciplina:</label>
            <div class="col-md-4">
                <select id="disciplina" name="disciplina" class="form-control" required="">
                <option value=""></option>
                <option value="1">Física</option>
                <option value="2">Matemática</option>
                <option value="3">Química</option>
                <option value="4">Biologia</option>
                <option value="5">Português</option>
                <option value="6">História</option>
                <option value="7">Sociologia</option>
                <option value="8">Filosofia</option>
                <option value="9">Inglês</option>
                <option value="10">Espanhol</option>
                <option value="11">Artes</option>
                <option value="12">Geografia</option>
                </select>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaA">Alternativa A:</label>
            <div class="col-md-4">
                <textarea class="form-control" id="alternativaA" name="alternativaA" required=""></textarea>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaB">Alternativa B:</label>
            <div class="col-md-4">
                <textarea class="form-control" id="alternativaB" name="alternativaB" required=""></textarea>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaC">Alternativa C:</label>
            <div class="col-md-4">
                <textarea class="form-control" id="alternativaC" name="alternativaC" required=""></textarea>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaD">Alternativa D:</label>
            <div class="col-md-4">
                <textarea class="form-control" id="alternativaD" name="alternativaD" required=""></textarea>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaE">Alternativa E:</label>
            <div class="col-md-4">
                <textarea class="form-control" id="alternativaE" name="alternativaE" required=""></textarea>
            </div>
            </div>

            <!-- Select Basic -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaCorreta">Alternativa correta:</label>
            <div class="col-md-2">
                <select id="alternativaCorreta" name="alternativaCorreta" class="form-control" required="">
                <option value=""></option>
                <option value="1">A</option>
                <option value="2">B</option>
                <option value="3">C</option>
                <option value="4">D</option>
                <option value="5">E</option>
                </select>
            </div>
            </div>
			<input type="hidden" value="INSERT" name="source"/>
            <!-- Button -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="singlebutton"></label>
            <div class="col-md-4">
                <button id="singlebutton" name="singlebutton" class="btn btn-info">Adicionar</button>
            </div>
            </div>
            </fieldset>
        </form>
        <?php /*
            $enunciado = addslashes($_POST['enunciado']);
            echo htmlspecialchars($enunciado, ENT_QUOTES);*/
        ?>
        <script>
            initSample();
        </script>
        <script src="../js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.js"><\/script>')</script>
        <script src="../js/vendor/bootstrap.min.js"></script>
    </body>
</html>
