<?php
    require_once '../add/HeaderSession.php';
    if($_SESSION['usuario']['nivelAcessoId'] != 1 && $_SESSION['usuario']['nivelAcessoId'] != 2){
       $_SESSION = array();
       session_destroy();
       header("Location: ../errorPagina");
       exit();
   }
   require '../private_html_protected/config.php';
   require '../private_html_protected/connection.php';
   require '../private_html_protected/database.php';
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
        <br>
        <div class="alert alert-warning" role="alert"><strong>Imagem no Enunciado(SEM AS ASPAS):</strong> &quot;../img_simulado/simuladoM/qN.png&quot;</div>
        <div class="alert alert-warning" role="alert"><strong>Caso com mais de 1 Imagem no Enunciado(r = {1,2,3...Numero da Imagem}):</strong> &quot;../img_simulado/simuladoM/qN_r.png&quot;</div>
        <div class="alert alert-warning" role="alert"><strong>Imagem na Alternativa:</strong> &lt;img src=&quot;../img_simulado/simuladoM/alternativas/qNx.png&quot;/&gt;</div>
        <div class="alert alert-warning" role="alert"><strong>Linguagens, Códigos e suas Tecnologias: 1 a 45<br>Ciências Humanas e suas Tecnologias: 46 a 90<br>Matemática e suas Tecnologias: 91 a 135<br>Ciências da Natureza e suas Tecnologias: 136 a 180</strong></div>
        <form class="form-horizontal" action="add_questaoSimulado.php" method="POST">
            <fieldset>

            <!-- Form Name -->
            <legend>Adicionar Questão</legend>
            <!-- Select Basic -->

            <div class="form-group">
              <label class="col-md-2 control-label" for="anoProva">Ano do Simulado</label>
              <div class="col-md-2">
                <select id="anoProva" name="anoProva" class="form-control" required>
                    <option value=""></option>
                    <?php
                        for($i=17;$i<=19;$i++){
                            if(isset($_SESSION['adm']['anoProva']) && $_SESSION['adm']['anoProva'] == $i){
                                echo "<option value='$i' selected>20$i</option>";
                            }else{
                                echo "<option value='$i'>20$i</option>";
                            }
                        }
                    ?>
                </select>
              </div>
            </div>

            <!-- Select Basic -->
            <div class="form-group">
              <label class="col-md-2 control-label" for="mesProva">Mês do Simulado</label>
              <div class="col-md-2">
                <select id="mesProva" name="mesProva" class="form-control" required>
                    <option value=""></option>
                    <?php
                        $meses = array(
                            1 => "Janeiro",
                            2 => "Fevereiro",
                            3 => "Março",
                            4 => "Abril",
                            5 => "Maio",
                            6 => "Junho",
                            7 => "Julho",
                            8 => "Agosto",
                            9 => "Setembro",
                            10 => "Outubro",
                            11 => "Novembro",
                            12 => "Dezembro"
                        );
                        for($j=1;$j<=12;$j++){
                            if(isset($_SESSION['adm']['mesProva']) && intval($_SESSION['adm']['mesProva']) == $j){
                                if($j>=10){
                                    echo "<option value='".$j."' selected>".$meses[$j]."</option>";
                                }else{
                                    echo "<option value='0".$j."' selected>".$meses[$j]."</option>";
                                }
                            }else{
                                if($j>=10){
                                    echo "<option value='".$j."'>".$meses[$j]."</option>";
                                }else{
                                    echo "<option value='0".$j."'>".$meses[$j]."</option>";
                                }

                            }
                        }
                    ?>
                </select>
              </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
            <label class="col-md-2 control-label" for="numQuestao">Número da Questão: </label>
            <div class="col-md-2">
            <input id="numQuestao" name="numQuestao" type="number" placeholder="Ex: 23" class="form-control input-md" value="" required="">
            <a class="btn btn-warning btn-lg" title="Verifique se já foi adicionado ou não esse número" onclick="verificaNumero()">Verificar Status</a>
            <span id="statusQuestao" style="font-weight: 700; display: none; color: red; opacity: 0.7; font-family: Arial, sans-serif; font-size: 17px; display: inline-block;"></span>
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
                <option value="4">Linguagens, Códigos e suas Tecnologias</option>
                <option value="3">Ciências Humanas e suas Tecnologias</option>
                <option value="1">Matemática e suas Tecnologias</option>
                <option value="2">Ciências da Natureza e suas Tecnologias</option>
                </select>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaA">Alternativa A:</label>
            <div class="col-md-6">
                <textarea rows="4" class="form-control" id="alternativaA" name="alternativaA" required=""></textarea>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaB">Alternativa B:</label>
            <div class="col-md-6">
                <textarea rows="4" class="form-control" id="alternativaB" name="alternativaB" required=""></textarea>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaC">Alternativa C:</label>
            <div class="col-md-6">
                <textarea rows="4" class="form-control" id="alternativaC" name="alternativaC" required=""></textarea>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaD">Alternativa D:</label>
            <div class="col-md-6">
                <textarea rows="4" class="form-control" id="alternativaD" name="alternativaD" required=""></textarea>
            </div>
            </div>

            <!-- Textarea -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="alternativaE">Alternativa E:</label>
            <div class="col-md-6">
                <textarea rows="4" class="form-control" id="alternativaE" name="alternativaE" required=""></textarea>
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

            <!-- Button -->
            <div class="form-group">
            <label class="col-md-2 control-label" for="singlebutton"></label>
            <div class="col-md-4">
                <button id="singlebutton" name="singlebutton" class="btn btn-info">Adicionar</button>
            </div>
            </div>
            <input type="hidden" name="source" value="INSERT"/>
            </fieldset>
        </form>
        <script>
            initSample();
            function verificaNumero(){
                var status = document.getElementById('statusQuestao');
                var anoProva = document.getElementById('anoProva').value;
                var mesProva = document.getElementById('mesProva').value;
                var numQuestao = document.getElementById('numQuestao').value;
                if(numQuestao == "" || mesProva == "" || anoProva == ""){
                    if (anoProva == "") {
                        alert('Selecione o ano da Prova.');
                    } else if (mesProva == "") {
                        alert('Selecione o mês da Prova.');
                    } else {
                        alert('Digite o número da Prova.');
                    }
                } else {
                    var page = 'checar_questaoAdicionada.php';
                    $.ajax({
                        type: 'POST',
                        dataType: 'html',
                        url: page,
                        beforeSend: function(){
                            status.innerHTML = "Buscando...";
                            status.style.display="inline-block";
                        },
                        data: {anoProva: anoProva, mesProva: mesProva, numQuestao: numQuestao},
                        success: function(msg){
                            status.innerHTML = msg;
                            status.style.display="inline-block";
                        }
                    });
                }
            }
        </script>
        <script src="../js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.js"><\/script>')</script>
        <script src="../js/vendor/bootstrap.min.js"></script>
    </body>
</html>
