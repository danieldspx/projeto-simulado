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
        header("Location: LoginSimultaneo.php");
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
        <link href="css/footer.css" rel="stylesheet" type="text/css"/>
        <title>Questões</title>
        <link rel="sortcut icon" href="img/favicon/questaoicon.png" type="image/png" />
        <meta charset="UTF-8">
        <link href="css/questoes.css" rel="stylesheet" type="text/css"/>
        <script src="js/questoesFunction.js" type="text/javascript"></script>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script>window.MathJax = { MathML: { extensions: ["mml3.js", "content-mathml.js"]}};</script>
        <script type="text/javascript" async src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js?config=MML_HTMLorMML"></script>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <script src="http://www.wiris.net/demo/editor/editor"></script>
        <script type="text/javascript" src="http://latex.codecogs.com/latexit.js"></script>
        <link href="iconfont/material-icons.css" rel="stylesheet">
        <script type="text/javascript" src="js/websockets.js"></script>
        <?php
            if($is_mobile){
                echo "<link href='css/styleMenu.css' rel='stylesheet'>";
                echo "<link href='css/animateMenu.css' rel='stylesheet'>";   
            }
        ?>
        <script>
            var editor;
            window.onload = function () {
                editor = com.wiris.jsEditor.JsEditor.newInstance({'language': 'pt_br', 'toolbar' : 'evaluate'});
                editor.insertInto(document.getElementById('editorContainer'));
            }
            function addMath(){
                document.getElementById('code').innerHTML=editor.getMathML();
            }
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
        <?php include_once("add/analyticstracking.php") ?>
    </head>
    <body style="background-color: #fafafa" onload="essencial()">

        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <?php if($is_mobile){ ?>
            <div id="navbar-mobile">
                <div class="menu-group animated" id="menu-groupID">
                    <div class="menu-section">
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
                            <li class="menu-lista-item li-active" onclick="window.location.href='questoes';"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span> Questões</li>
                            <li class="menu-lista-item" onclick="window.location.href='ranking';"><i class="fa fa-trophy" aria-hidden="true"></i> Ranking</li>
                            <li class="menu-lista-item" onclick="window.location.href='desempenho';"><i class="fa fa-pie-chart" aria-hidden="true"></i> Desempenho</li>
                            <li class="menu-lista-item" onclick="window.location.href='filtro';"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span> Filtro</li>
                            <li class="menu-lista-item" onclick="window.location.href='simulado';"><span class="glyphicon glyphicon-check" aria-hidden="true"></span> Simulado</li>
                            <li class="menu-lista-item" onclick="window.location.href='usuario';"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Meus Dados</li>
                            <?php include_once("add/notif_mob.php"); ?>
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
                    <a class="navbar-brand" title="Dados do Usuário" href="usuario" style="font-family: Montserrant,sans-serif;font-weight: 600;color: #ecf0f1;"><span class="glyphicon glyphicon-user"></span>&nbsp; Olá, <?php echo $_SESSION['usuario']['nome']; ?>.</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="questoes"><span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>&nbsp; Questões <span class="sr-only">(current)</span></a></li>
                        <li><a href="ranking"><i class="fa fa-trophy" aria-hidden="true"></i>&nbsp; Ranking</a></li>
                        <li><a href="desempenho"><i class="fa fa-pie-chart" aria-hidden="true"></i>&nbsp; Desempenho</a></li>
                        <li><a href="filtro"><span class="glyphicon glyphicon-filter" aria-hidden="true"></span>&nbsp; Filtro</a></li>
                        <li><a href="simulado"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>&nbsp; Simulado</a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Links Úteis <span class="caret"></span></a>
                            <?php include "linksUteis.add"; ?>
                        </li>
                        <?php include_once("add/notif_desktp.php"); ?>
                    </ul>
                    <form class="navbar-form navbar-right" method="GET" action="">
                        <input value="1" name="slogout" style="display: none;">
                        <button type="submit" class="btn btn-primary btn-danger">Logout</button>
                    </form>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
        <?php }; ?><!--//FIM DO TESTE IS_MOBILE-->
        <!--COMEÇA A PAGINAÇÃO E AS QUESTOES-->
        <table class="paginacao" <?php if($is_mobile){echo "style=\"margin-top: 100px; display: inline-block;\"";} ?>>
            <tr>
                <td>

                        <button id="previousPage" name="previousPage" class="btn btn-success" onclick="muda_pagina(1)" style="font-weight: 900; height: 35px;"><span class="glyphicon glyphicon-chevron-left"></span></button>
                </td>
                <td>
                        <input name="paginaAtual" type="text" placeholder="" class="form-control input-md" style="color:black; font-weight: 900; text-align: center ;font-family: arial,sans-serif; font-size: 15px; width: 50px; height: 35px;" <?php /*valor inicial da paginação*/ $pagina_atual = $_GET['pagina'];if(!isset($pagina_atual)){$pagina_atual = 1;}echo("value=\"$pagina_atual\"")?> type="number"  id="numero_pagina" onchange="muda_pagina(3)">
                </td>
                <td>
                        <button id="nextPage" name="nextPage" class="btn btn-success" onclick="muda_pagina(2)" style="font-weight: 900;height: 35px;"><span class="glyphicon glyphicon-chevron-right"></span></button>

                </td>
            </tr>
        </table>

        <?php
            require_once 'private_html_protected/config.php';
            require_once 'private_html_protected/connection.php'; //Chamando Arquivos Necessarios para Conexão
            require_once 'private_html_protected/database.php';
            // -- Divisor de pesquisas --
            $pagina_atual = $_GET['pagina'];
            if(!isset($pagina_atual)){
                $pagina_atual = 1;
            }
            if($pagina_atual<=0){
                echo "<script>setTimeout(muda_pagina(),0);</script>";
            }
            $filtro_ano = $_POST['ano'];
            $filtro_area = $_POST['area'];
            $filtro_disciplina = $_POST['disciplina'];
            $wasFiltro = false;
            if(isset($is_mobile) && $is_mobile==true){
                $qpp = 2; //Questoes por pagina
            } else {
                $qpp = 10;
            }
            
            $inicio = ($pagina_atual-1) * $qpp; //Subtrai 1 para o calculo do inicio da pagina estar correto
            if (isset($filtro_ano) || isset($filtro_area) || isset($filtro_disciplina)){
                $comando_area=null;
                $comando_disciplina=null;
                $comando_ano=null;
                $qtd1 = count($filtro_ano);
                $qtd2 = count($filtro_area);
                $qtd3 = count($filtro_disciplina);
                if($filtro_ano[$qtd1-1]!="*" && isset($filtro_ano)){
                    $comando_ano = "ano = ".$filtro_ano[0];
                    for($i=1; $i<$qtd1; $i++){
                        $comando_ano = $comando_ano." OR ano = ".$filtro_ano[$i];
                    }
                    $comando_ano = "(".$comando_ano.")";
                }

                if($filtro_area[$qtd2-1] != '*' && isset($filtro_area)){
                    $comando_area = "areas_idarea = ".$filtro_area[0];
                    for($i=1; $i<$qtd2; $i++){
                        $comando_area = $comando_area." OR areas_idarea = ".$filtro_area[$i];
                    }
                    $comando_area = "(".$comando_area.")";
                }
                if($filtro_disciplina[$qtd3-1] != '*' && isset($filtro_disciplina)){
                    $comando_disciplina = "disciplinas_id_disciplina = ".$filtro_disciplina[0];
                    for($i=1; $i<$qtd3; $i++){
                        $comando_disciplina = $comando_disciplina." OR disciplinas_id_disciplina = ".$filtro_disciplina[$i];
                    }
                    $comando_disciplina = "(".$comando_disciplina.")";
                }
                $contador = 0;
                $comandoT = null;
                if(isset($comando_ano)){
                    $comandoT[$contador]= $comando_ano;
                    $contador++;
                }
                if(isset($comando_area)){
                    $comandoT[$contador]= $comando_area;
                    $contador++;
                }
                if(isset($comando_disciplina)){
                    $comandoT[$contador]= $comando_disciplina;
                    $contador++;
                }
                if(isset($comandoT)){
                    $comandoTimplode = implode(" AND ", $comandoT);
                } else {
                    $comandoTimplode = '1';
                }
                if(isset($comandoT)){
                    $_SESSION['questoesFiltro'] = "JOIN areas ON areas.idarea = questoes.areas_idarea JOIN disciplinas ON disciplinas.id_disciplina = questoes.disciplinas_id_disciplina WHERE $comandoTimplode ORDER BY ano DESC , numero ASC";
                    $questoes = DBSearch("questoes","JOIN areas ON areas.idarea = questoes.areas_idarea JOIN disciplinas ON disciplinas.id_disciplina = questoes.disciplinas_id_disciplina WHERE $comandoTimplode ORDER BY ano DESC , numero ASC LIMIT $inicio, $qpp;","ano, idquestao,numero, areas.desc_area AS area, disciplinas.desc_disciplina AS disciplina, descricao, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, alternativa_correta");
                } else{
                    $questoes = DBSearch("questoes","JOIN areas ON areas.idarea = questoes.areas_idarea JOIN disciplinas ON disciplinas.id_disciplina = questoes.disciplinas_id_disciplina LIMIT $inicio, $qpp","ano, idquestao,numero, areas.desc_area AS area, disciplinas.desc_disciplina AS disciplina, descricao, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, alternativa_correta");
                }
                $quantidade = DBSearch("questoes","JOIN areas ON areas.idarea = questoes.areas_idarea JOIN disciplinas ON disciplinas.id_disciplina = questoes.disciplinas_id_disciplina WHERE $comandoTimplode","COUNT(idquestao) AS quantidade"); //Resultados encontrados
                $nquestoes = intval($quantidade[0]['quantidade']);
                $wasFiltro = true; //Entrou no filtro
                $_SESSION['nquestoes'] = $nquestoes;
            } else{
                if(empty($_SESSION['questoesFiltro'])){
                    $quantidade = DBSearch("questoes",null,"COUNT(idquestao) AS quantidade"); //Resultados encontrados, poderia usar o COUNT tbm
                    $nquestoes = intval($quantidade[0]['quantidade']);
                    $_SESSION['nquestoes'] = $nquestoes;
                    $questoes = DBSearch("questoes","JOIN areas ON areas.idarea = questoes.areas_idarea JOIN disciplinas ON disciplinas.id_disciplina = questoes.disciplinas_id_disciplina ORDER BY ano DESC , numero ASC LIMIT $inicio, $qpp","ano, idquestao,numero, areas.desc_area AS area, disciplinas.desc_disciplina AS disciplina, descricao, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, alternativa_correta");
                }
            }
            if(!$wasFiltro && isset($_SESSION['questoesFiltro'])){
                $nquestoes = intval($_SESSION['nquestoes']);
                $questoes = DBSearch("questoes",$_SESSION['questoesFiltro']." LIMIT $inicio, $qpp","ano, idquestao,numero, areas.desc_area AS area, disciplinas.desc_disciplina AS disciplina, descricao, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, alternativa_correta");
            }
            $npaginas = ceil($nquestoes/$qpp);
            $hash_a=md5("A");
            $hash_b=md5("B");
            $hash_c=md5("C");
            $hash_d=md5("D");
            $hash_e=md5("E");
            $numero_pagina = $_GET['pagina'];
            if(!isset($numero_pagina)){
                $numero_pagina = 1;
            }
            for($i=0;$i<$qpp;$i++){
                if(empty($questoes[$i])){//Se ja tiver acabado as questões
                    break;
                }
                $id = $questoes[$i]['idquestao'];
                $get_comentarios = DBSearch("comentarios","JOIN usuarios ON usuarios_id = usuarios.id WHERE questoes_idquestao =".$id,"comentarios.pontuacao as pontos,comentario,CONCAT(usuarios.nome,' ',usuarios.sobrenome) as nome,usuarios_id,idcomentario"); //Pegando os comentarios
                $ncomentarios = count($get_comentarios); // Quantidade de comentarios
                $comentarioUsuario = false;
                echo "<div class=\"fadecss-transition\">";
                    echo "<div class='painel-questao' id='painelq$id'>";
                        echo "<div class=\"cabecalho\">";
                            echo "<div class=\"numero-questao\">".($numero_pagina*$qpp+$i+1-$qpp)."</div>";
                            echo "<div class=\"descricao\">";
                                echo "<a class=\"topo\">#Q".$questoes[$i]['numero']."</a>";
                                echo "<span class=\"pipe\"></span>ENEM ".$questoes[$i]['ano']."<span class=\"pipe hidden-xs\"></span>";
                                echo "<a id=\"area\" class=\"hidden-xs topo\" >".$questoes[$i]['area']."</a>";
                                echo "<span class=\"pipe\"></span>";
                                echo "<a class=\"topo\">".$questoes[$i]['disciplina']."</a>";
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
                                echo "<div class=\"alternativabutton\"  id=\"A".$id."\" onclick=\"check('A".$id."','".$hash_a.$hash."')\">";
                                    echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">A</span> &nbsp; ".$questoes[$i]['alternativa_a']."<!--v-html--></div>";
                                echo "</div><br>";
                                echo "<div class=\"alternativabutton\" id=\"B".$id."\" onclick=\"check('B".$id."','".$hash_b.$hash."')\">";
                                    echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">B</span> &nbsp; ".$questoes[$i]['alternativa_b']."<!--v-html--></div>";
                                echo "</div><br>";
                                echo "<div class=\"alternativabutton\" id=\"C".$id."\" onclick=\"check('C".$id."','".$hash_c.$hash."')\">";
                                    echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">C</span> &nbsp; ".$questoes[$i]['alternativa_c']."<!--v-html--></div>";
                                echo "</div><br>";
                                echo "<div class=\"alternativabutton\" id=\"D".$id."\" onclick=\"check('D".$id."','".$hash_d.$hash."')\">";
                                    echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">D</span> &nbsp; ".$questoes[$i]['alternativa_d']."<!--v-html--></div>";
                                echo "</div><br>";
                                echo "<div class=\"alternativabutton\" id=\"E".$id."\" onclick=\"check('E".$id."','".$hash_e.$hash."')\">";
                                    echo "<div class=\"alternativa-texto\"><span class=\"alternativa-letra\">E</span> &nbsp; ".$questoes[$i]['alternativa_e']."<!--v-html--></div>";
                                echo "</div><br>";
                            echo "</div>";
                            echo "<div class=\"botao-responder-container\" id=\"confim-button".$id."\">";
                                echo "<button id=\"button_effec".$id."\" class=\"botao botao-responder-questao\" style=\"display: none\">Confirmar Resposta ()</button>";
                            echo "</div>";
                            echo "<br><button id=\"buttoncomentario".$id."\" onclick='showcomentario(".$id.")' class='btnComentario displaynone'>Resoluções</button>";
                            if(isset($get_comentarios[0])){
                                    echo "<div class='container comentario displaynone' id='comentariosGerais$id'>";
                                    for($j=0; $j<$ncomentarios; $j++){
                                        $get_comentarios[$j] = str_replace("[text]","<span lang=\"latex\">",$get_comentarios[$j]);
                                        $get_comentarios[$j] = str_replace("[/text]","</span>",$get_comentarios[$j]);
                                            if($_SESSION['usuario']['id'] == $get_comentarios[$j]['usuarios_id']){ //Caso o comentario  seja do usuario logado
                                                $comentarioUsuario = true;
                                                echo "<div style='margin-left: 0px;' class='row' id='painelComentario".$id.$get_comentarios[$j]['idcomentario']."'>";
                                                    echo "<div class=\"panel panel-primary\">";
                                                        echo "<div class=\"panel-heading\"><h3 style=\"padding-left: 0px;\" class=\"panel-title col-md-10\">".$get_comentarios[$j]['nome']."</h3><span id=\"pontos".$get_comentarios[$j]['usuarios_id']."\" class=\"badge\" >".$get_comentarios[$j]['pontos']."</span>&nbsp;&nbsp;&nbsp;<span id=\"imagem".$id.$get_comentarios[$j]['idcomentario']."\"><i class='material-icons' title='Excluir resolução' style='cursor: pointer; vertical-align: middle;' onclick='removeResolucao(".$id.",".$get_comentarios[$j]['idcomentario'].")'>delete_forever</i></span></div>";
                                                        echo "<div class=\"panel-body\"><p style=\"position: relative; display: inline-block;\">".$get_comentarios[$j]['comentario']."</p></div>";
                                                    echo "</div>";
                                                echo "</div>";
                                            } else { //Caso o comentario não seja do usuario logado
                                                echo "<div style='margin-left: 0px;' class='row' id='painelComentario".$id.$get_comentarios[$j]['idcomentario']."'>";
                                                    echo "<div class=\"panel panel-primary\" >";
                                                        echo "<div class=\"panel-heading\"><h3 style=\"padding-left: 0px;\" class=\"panel-title col-md-10\">".$get_comentarios[$j]['nome']."</h3><span id=\"pontos".$get_comentarios[$j]['usuarios_id']."\" class=\"badge\" >".$get_comentarios[$j]['pontos']."</span>&nbsp;&nbsp;&nbsp;<span id=\"imagem".$id.$get_comentarios[$j]['idcomentario']."\"><span class='badge' style='padding: 4px;border-radius: 15px;background-color: #00e676;color: #333;cursor: pointer;' onclick='pontuar($id,".$get_comentarios[$j]['usuarios_id'].")'><span class='glyphicon glyphicon-menu-up' aria-hidden='true'></span></span></span></div>";
                                                        echo "<div class=\"panel-body\"><p style=\"position: relative; display: inline-block;\">".$get_comentarios[$j]['comentario']."</p></div>";
                                                    echo "</div>";
                                                echo "</div>";
                                            }
                                    }
                                    echo "</div>";
                            } else {
                                echo "<div id='comentariosGerais$id' class='comentario displaynone'>";
                                     echo "<tr>";
                                        echo "<div class=\"alert alert-danger\" style='margin-top: 20px;' role=\"alert\"><strong>Nenhum comentário encontrado, atualize a página e tente novamente.</strong></div>";
                                    echo "</tr>";
                                echo "</div>";
                            }
                            echo "<div id='section_usercmm$id'>";
                                if(!$comentarioUsuario){
                                    echo "<div id=\"buttonsResolution$id\" style=\"margin: 20px 0px;\"><button onclick=\"writeResolution(".$id.")\" style=\"margin-right: 15px;\" id=\"write".$id."\" class=\"btn btn-primary btn-info displaynone\">Escrever resolução <span class=\"glyphicon glyphicon-comment\"></span></button>";
                                    echo "<button onclick=\"showMathML('".$id."')\" style=\"display: none;\" id=\"math".$id."\" class=\"btn btn-primary btn-warning\">Símbolos Matemáticos <i class=\"fa fa-calculator\" aria-hidden=\"true\"></i></button></div>";
                                    echo "<span id=\"MathField".$id."\"></span>";
                                    echo "<span id=\"digite_comentario".$id."\" class=\"displaynone\">";
                                        echo "<textarea type=\"text\" rows='4' style=\"display: block; margin-bottom: 20px;\" id=\"txtResolucao".$id."\" cols='50' maxlength='800' placeholder='Digite sua solução. Máx: 800 caracteres'></textarea>";
                                        echo "<div id=\"editorContainer\"></div>";
                                        echo "<input class='btn btn-info btn-sm' type='submit' value='Enviar Resolução' onclick=\"addResolucao(".$id.")\">";
                                    echo "</span>";
                                }
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
            }
            if(empty($questoes[0]) && $wasFiltro==true){
                echo "<br><div style=\"padding: 20px; font-weight: 700; font-famili: Arial,sans-serif; background-color: rgba(255,250,250,0.8); width: 50%; margin-left:25%; border-radius: 20px; font-size: 30px; margin-top: 90px; margin-bottom: 90px;\"><p>Nenhum resultado encontrado. Tente com outras opções de filtro.</p></div><br>";
                echo "<script>setTimeout(function(){document.getElementById('nextPageBtn').style.display='none';},0);</script>";
                unset($_SESSION['questoesFiltro']);
            } else if($pagina_atual>$npaginas || $pagina_atual<=0){
                echo "<br><div style=\"padding: 20px; font-weight: 700; font-famili: Arial,sans-serif; background-color: rgba(255,250,250,0.8); width: 50%; margin-left:25%; border-radius: 20px; font-size: 30px; margin-top: 90px; margin-bottom: 90px;\"><p>Não há resultados a serem exibidos.</p></div><br>";
                echo "<script>setTimeout(function(){document.getElementById('nextPageBtn').style.display='none';},0);</script>";
                unset($_SESSION['questoesFiltro']);
            }
        ?>

        <div class="TopPage" title="Topo da página." id="subir" style="cursor: pointer;position: fixed; right: 2px; bottom: 10px;"><img src="img/up-arrow.svg" width="50px"></div>

        <a id="nextPageBtn" onclick="muda_pagina(2)" class="next-page btn btn-lg btn-success">Próxima Página <span class="glyphicon glyphicon-chevron-right"></span></a>
        <div class="displaynone" id="SL"></div>
        <!--FOOTER INICIA AQUI-->
        <footer>
            <div class="container">
                <div class="row">
                <div class="col-md-4 col-sm-6 footerleft ">
                    <div class="logofooter"> Créditos de Imagem</div>
                    <div>Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/vectors-market" title="Vectors Market">Vectors Market</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/popcorns-arts" title="Popcorns Arts">Popcorns Arts</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/flat-icons" title="Flat Icons">Flat Icons</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>
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
        <script type="text/javascript">
            $(document).ready(function() {
                $('#subir').click(function(){
                    $('html, body').animate({scrollTop:0}, 'slow');
                    return false;
                });
            });
        </script>
        <script type="text/javascript" src="js/scrollReveal.js"></script>
        <script type="text/javascript">
            window.scrollReveal = new scrollReveal();
        </script>
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
