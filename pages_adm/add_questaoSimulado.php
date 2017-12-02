<?php
    require_once '../add/HeaderSession.php';
    require '../private_html_protected/config.php';
    require '../private_html_protected/connection.php';
    require '../private_html_protected/database.php';

     if($_SESSION['usuario']['nivelAcessoId'] != 1 && $_SESSION['usuario']['nivelAcessoId'] != 2){
        $_SESSION = array();
        session_destroy();
        header("Location: ../errorPagina");
        exit();
    }
    $numQuestao=$_POST['numQuestao'];
    $enunciado=addslashes($_POST['enunciado']);
    $area=$_POST['area'];
    $alternativaA=$_POST['alternativaA'];
    $alternativaA=addslashes($alternativaA);
    $alternativaB=$_POST['alternativaB'];
    $alternativaB=addslashes($alternativaB);
    $alternativaC=$_POST['alternativaC'];
    $alternativaC=addslashes($alternativaC);
    $alternativaD=$_POST['alternativaD'];
    $alternativaD=addslashes($alternativaD);
    $alternativaE=$_POST['alternativaE'];
    $alternativaE=addslashes($alternativaE);
    $mesProva=$_POST['mesProva'];
    $anoProva=$_POST['anoProva'];
    $idSimulado = $mesProva.$anoProva;
    $idQuestaoUPDATE = $_POST['idquestao'];
    $idSimuladoUPDATE = $_POST['id_simulado'];
    while(stripos($enunciado,"&lt;math") && stripos($enunciado,"&lt;/math&gt;")){ //Trata as expressões matemáticas MathML
        $inicio=stripos($enunciado,"&lt;math");
        $fim=stripos($enunciado,"&lt;/math&gt;")-$inicio+14;//14 É O TAMANHO DO </math>
        $tratamento = substr($enunciado,$inicio,$fim);
        $arrayT=array("<",">");
        $arrayF=array("&lt;","&gt;");
        $replaced=str_replace($arrayF,$arrayT,$tratamento);
        $string=str_replace($tratamento,$replaced,$enunciado);
    }
    if(empty($_SESSION['adm']['mesProva'])){
        $_SESSION['adm']['mesProva']=$mesProva;
    }
    if(empty($_SESSION['adm']['anoProva'])){
        $_SESSION['adm']['anoProva']=$anoProva;
    }
    if(strlen($idSimulado) == 3){
        $idSimulado = '0'.$idSimulado;
    }
    $alternativaCorreta=$_POST['alternativaCorreta'];
    $shouldBe=$_POST['source'];
    $shouldBe=strtoupper($shouldBe);
    if($shouldBe=="INSERT"){
        $comando="INSERT INTO questoes_simulado (id_simulado, numero, areas_idarea, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, alternativa_correta, descricao) VALUES (\"$idSimulado\",$numQuestao,$area,\"$alternativaA\",\"$alternativaB\",\"$alternativaC\",\"$alternativaD\",\"$alternativaE\",\"$alternativaCorreta\",\"$enunciado\");";
    } else {
        $comando="UPDATE questoes_simulado SET numero = $numQuestao, areas_idarea = $area, alternativa_a = \"$alternativaA\", alternativa_b = \"$alternativaB\", alternativa_c = \"$alternativaC\", alternativa_d = \"$alternativaD\", alternativa_e = \"$alternativaE\", alternativa_correta = $alternativaCorreta, descricao = \"$enunciado\" WHERE idquestao = $idQuestaoUPDATE;";
    }
    
    DBExecute($comando);
?>
<html>
<head><script>setTimeout(function() {
    window.location.replace("adicionar_questaoSimulado.php");
}, 0);</script></head>
<body><h1>Obrigado por Ajudar...</h1></body>
</html>
