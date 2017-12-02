<?php
    require_once 'add/HeaderSession.php';
    require_once 'private_html_protected/config.php';
    require_once 'private_html_protected/connection.php';
    require_once 'private_html_protected/database.php';
    $logout = $_REQUEST['slogout'];
    if($logout == '1'){
        $_SESSION = array();
        session_destroy();
    }
    if (empty($_SESSION['usuario'])) {
        session_start();
        $_SESSION['logout'] = "Logout efetuado com sucesso.";
        header("Location: index.php");
    }
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        header("Location: LoginSimultaneo.php");
    }
    $_SESSION['usuario']['simuladoUse']=1;
?>
<html>
    <head>
        <link rel="icon" type="image/png" href="images/questaoicon.png" />
        <title>Questões</title>
        <link rel="sortcut icon" href="img/favicon/questaoicon.png" type="image/png" />
        <meta charset="UTF-8">
        <link href="css/questoes_simulado.css" rel="stylesheet" type="text/css"/>
        <script src="js/questoes_simulado.js" type="text/javascript"></script>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script>window.MathJax = { MathML: { extensions: ["mml3.js", "content-mathml.js"]}};</script>
        <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=TeX-MML-AM_CHTML"></script>
        <script type="text/javascript" src="http://latex.codecogs.com/latexit.js"></script>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <link rel="stylesheet" href="css/busca_questaosimulado.css">
        <script>
            function essencial(){
                    var div = document.getElementById("subir");
                    if(document.body.scrollTop>=600){
                        div.style.visibility="visible";
                        div.style.opacity="1";
                    } else {
                        div.style.visibility="hidden";
                        div.style.opacity="0";
                    }
            }
            window.onscroll = JsScrollReveal;
            function JsScrollReveal(){
                var posicao = document.body.scrollTop;
                var div = document.getElementById("subir");
                if(posicao>=600){
                    div.style.visibility="visible";
                    div.style.opacity="1";

                } else if (posicao == 0){
                    div.style.visibility="hidden";
                } else {
                    div.style.opacity="0";
                }
            }
        </script>
    </head>
    <body style="background-color: #fafafa" onload="essencial()">
        <!--COMEÇA A PAGINAÇÃO E AS QUESTOES-->
        <table class="paginacao" style="margin-top: 50px; display: inline-block">
            <tr>
                <td>
                    <button id="previousPage" name="previousPage" class="btn btn-success" onclick="muda_pagina(1)" style="font-weight: 900; height: 35px;"><span class="glyphicon glyphicon-chevron-left"></span></button>
                </td>
                <td>
                        <input name="paginaAtual" type="text" placeholder="" class="form-control input-md" style="color:black; font-weight: 900; text-align: center ;font-family: arial,sans-serif; font-size: 15px; width: 50px; height: 35px;" <?php /*valor inicial da paginação*/ $pagina_atual = $_REQUEST['pagina'];if(!isset($pagina_atual)){$pagina_atual = 1;}echo("value=\"$pagina_atual\"")?> type="number"  id="numero_pagina" onchange="muda_pagina(3)">
                </td>
                <td>
                        <button id="nextPage" name="nextPage" class="btn btn-success" onclick="muda_pagina(2)" style="font-weight: 900;height: 35px;" <?php if($_REQUEST['pagina']>=9){echo "disabled";} ?> ><span class="glyphicon glyphicon-chevron-right"></span></button>

                </td>
            </tr>
        </table>
        <div class="saveIt" style="margin-top: 35px;">
            <span id="Saved" style="font-weight: 700; font-size: 17px; font-family: Arial, sans-serif; background-color: rgba(245,245,245,0.8); border-radius: 15px; padding: 7px; display: none;"></span>
            <span class="btn btn-primary btn-warning"><img src="img/save.svg" onclick="settar()" title="Salvar" style="cursor: pointer;" alt="" width="30px"/></span>

        </div>
        <?php
            require_once 'private_html_protected/config.php';
            require_once 'private_html_protected/connection.php'; //Chamando Arquivos Necessarios para Conexão
            require_once 'private_html_protected/database.php';
        
            // -- Divisor de pesquisas --
            $pagina_atual = $_REQUEST['pagina'];
            if(!isset($pagina_atual)){
                $pagina_atual = 1;
            }
            $qpp = 10; //Questoes por pagina
            $inicio = ($pagina_atual-1) * $qpp; //Subtrai 1 para o calculo do inicio da pagina estar correto
        
            date_default_timezone_set('America/Sao_Paulo');

            $dataSimulado = DBSearch("data_simulado","WHERE status = 1 LIMIT 1","dataInicioP,dataInicioS,dataFimP,dataFimS,id_simulado");
            $id_simulado = $dataSimulado[0]['id_simulado'];
            $dateNow = new DateTime(date('Y')."-".date('m')."-".date('d')." ".date('H').":".date('i').":".date('s')); // Data Atual

            $dataInicio[1] = new DateTime($dataSimulado[0]['dataInicioP']); // Data do inicio do 1 simulado
            $dataFim[1] = new DateTime($dataSimulado[0]['dataFimP']); // Data do termino do 1 simulado

            $dataInicio[2] = new DateTime($dataSimulado[0]['dataInicioS']); // Data do inicio do 2 simulado
            $dataFim[2] = new DateTime($dataSimulado[0]['dataFimS']); // Data do termino do 1 simulado

            if($dateNow >= $dataInicio[1] && $dateNow <= $dataFim[1]){ // Se estiver entre o periodo do 1º Simulado
                $questoes = DBSearch("questoes_simulado","JOIN areas ON areas.idarea = areas_idarea WHERE (areas_idarea = 3 or areas_idarea = 4) AND id_simulado = '$id_simulado' ORDER BY numero ASC LIMIT $inicio, $qpp","idquestao, numero, areas.desc_area AS area, descricao, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, alternativa_correta");
                $prova = 1;
            } else if ($dateNow >= $dataInicio[2] && $dateNow <= $dataFim[2]){ // Se estiver entre o periodo do 2º Simulado
                $questoes = DBSearch("questoes_simulado","JOIN areas ON areas.idarea = areas_idarea WHERE (areas_idarea = 1 or areas_idarea = 2) AND id_simulado = '$id_simulado' ORDER BY numero ASC LIMIT $inicio, $qpp","idquestao, numero, areas.desc_area AS area, descricao, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, alternativa_correta");
                $prova = 2;
            } else { //Fora dos dois periodos
                exit();
            }
            $hash_a=md5("A");
            $hash_b=md5("B");
            $hash_c=md5("C");
            $hash_d=md5("D");
            $hash_e=md5("E");
            $numero_pagina = $_REQUEST['pagina'];

            if(!isset($numero_pagina)){
                $numero_pagina = 1;
            }
                $pesquisa = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"questoes_marcadas");
                $_SESSION['usuario']['simulado']['qmarcadas'] = $pesquisa[0]['questoes_marcadas'];

                for($i=0;$i<$qpp;$i++){
                    if(empty($questoes[$i])){//Se ja tiver acabado as questões
                        break;
                    }
                    $id = $questoes[$i]['idquestao'];
                    $numero = $questoes[$i]['numero'];;
                    echo "<div class=\"fadecss-transition\">";
                        echo "<div class=\"painel-questao\">";
                            echo "<div class=\"cabecalho\">";
                                echo "<div class=\"numero-questao\">".$numero."</div>";
                                echo "<div class=\"descricao\">";
                                    echo "<a id=\"area\" class=\"hidden-xs topo\" >".$questoes[$i]['area']."</a>";
                                echo "</div>";
                            echo "</div> ";
                            echo "<div class=\"corpo\" style=\"font-size: 1em;\">";
                                echo "<div style=\"font-family: 'Roboto', sans-serif; \" class=\"enunciado\">".$questoes[$i]['descricao']."</div>";
                                echo "<div class=\"alternativas\">";
                                $transform_letra = $questoes[$i]['alternativa_correta'];//Pega o numero da alternativa
                                switch ($transform_letra){ //Transforma em letra
                                    case "1":
                                        $transform_letra = "A";
                                        break;
                                    case "2":
                                        $transform_letra = "B";
                                        break;
                                    case "3":
                                        $transform_letra = "C";
                                        break;
                                    case "4":
                                        $transform_letra = "D";
                                        break;
                                    case "5":
                                        $transform_letra = "E";
                                        break;
                                }
                                    $hash = md5($transform_letra);//Alternativa correta encriptada
                                    echo "<div class=\"alternativabutton\" id=\"A".$numero."\" onclick=\"check('A".$numero."','".$hash_a.$hash."')\">";
                                        echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">A</span> - ".$questoes[$i]['alternativa_a']."<!--v-html--></div>";
                                    echo "</div><br>";
                                    echo "<div class=\"alternativabutton\" id=\"B".$numero."\" onclick=\"check('B".$numero."','".$hash_b.$hash."')\">";
                                        echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">B</span> - ".$questoes[$i]['alternativa_b']."<!--v-html--></div>";
                                    echo "</div><br>";
                                    echo "<div class=\"alternativabutton\" id=\"C".$numero."\" onclick=\"check('C".$numero."','".$hash_c.$hash."')\">";
                                        echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">C</span> - ".$questoes[$i]['alternativa_c']."<!--v-html--></div>";
                                    echo "</div><br>";
                                    echo "<div class=\"alternativabutton\" id=\"D".$numero."\" onclick=\"check('D".$numero."','".$hash_d.$hash."')\">";
                                        echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">D</span> - ".$questoes[$i]['alternativa_d']."<!--v-html--></div>";
                                    echo "</div><br>";
                                    echo "<div class=\"alternativabutton\"  id=\"E".$numero."\" onclick=\"check('E".$numero."','".$hash_e.$hash."')\">";
                                        echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">E</span> - ".$questoes[$i]['alternativa_e']."<!--v-html--></div>";
                                    echo "</div><br>";
                                echo "</div>";
                                echo "<div class=\"botao-responder-container\" id=\"confim-button".$numero."\">";
                                    echo "<button id=\"button_effec".$numero."\" class=\"botao botao-responder-questao\" style=\"display: none\">Confirmar Resposta ()</button>";
                                echo "</div>";
                                $nquestao = $numero;
                                $sessaoString = $_SESSION['usuario']['simulado']['qmarcadas'];
                                $separador = "-".$nquestao."_";
                                if(strrpos($sessaoString,$separador) !== false){
                                    $splited = substr($sessaoString,strrpos($sessaoString,$separador),strlen($separador)+1);
                                    $letra = substr($splited,-1,1);
                                    echo "<script>setTimeout(forcedSelect(".$nquestao.",'".$letra."'),0);</script>";
                                }
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                }
        ?>

        <div class="TopPage" title="Topo da página." id="subir" style="cursor: pointer;position: fixed; right: 2px; bottom: 10px;"><img src="img/up-arrow.svg" width="50px"></div>

        <a onclick="muda_pagina(2)" class="next-page btn btn-block btn-lg btn-success" <?php if($_REQUEST['pagina']>=9){echo "disabled";} ?> >Próxima Página <span class="glyphicon glyphicon-chevron-right"></span></a>
        <div class="displaynone" id="SL"></div>
        <script type="text/javascript">
            $(document).ready(function() {
            $('#subir').click(function(){
                $('html, body').animate({scrollTop:0}, 'slow');
            return false;

                });
            });

        </script>
        <!--script type="text/javascript" src="js/scrollreveal.min.js"></script>
        <script type="text/javascript">
            window.sr = ScrollReveal();
            sr.reveal('.fadecss-transition',{ reset: true });
        </script-->
        <script src="js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>


    </body>
</html>
