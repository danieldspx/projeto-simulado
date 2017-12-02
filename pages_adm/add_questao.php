<?php
    require_once '../add/HeaderSession.php';
    require_once '../private_html_protected/config.php';
    require_once '../private_html_protected/connection.php';
    require_once '../private_html_protected/database.php';
    if($_SESSION['usuario']['nivelAcessoId'] != 1 && $_SESSION['usuario']['nivelAcessoId'] != 2){
        $_SESSION=array();
        session_destroy();
        header("Location: ../errorPagina");
        exit();
    }
    $idQuestaoUPDATE=$_POST['idquestao'];
    $numQuestao=$_POST['numQuestao'];
    $anoProva=$_POST['anoProva'];
    $idQuestao=(2016-$anoProva)*180 + $numQuestao;
    $enunciado=addslashes($_POST['enunciado']);
    $enunciado = str_replace("[code]","<span lang='latex'>",$enunciado);
    $enunciado = str_replace("[/code]","</span>",$enunciado);
    $area=$_POST['area'];
    $disciplina=$_POST['disciplina'];
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
    $alternativaCorreta=$_POST['alternativaCorreta'];
    $shouldBe=$_POST['source'];
    $shouldBe=strtoupper($shouldBe);
    if($shouldBe=="INSERT"){
        $comando="INSERT INTO questoes(idquestao, ano, numero, areas_idarea, disciplinas_id_disciplina, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, alternativa_correta, descricao) VALUES ($idQuestao, $anoProva, $numQuestao, $area, $disciplina, \"$alternativaA\", \"$alternativaB\", \"$alternativaC\", \"$alternativaD\", \"$alternativaE\", \"$alternativaCorreta\", \"$enunciado\")";
    } else {
        $comando="UPDATE questoes SET ano = $anoProva, numero = $numQuestao, areas_idarea = $area, disciplinas_id_disciplina = $disciplina, alternativa_a = \"$alternativaA\", alternativa_b = \"$alternativaB\", alternativa_c = \"$alternativaC\", alternativa_d = \"$alternativaD\", alternativa_e = \"$alternativaE\", alternativa_correta = $alternativaCorreta, descricao = \"$enunciado\" WHERE idquestao = $idQuestaoUPDATE;";
    }
    DBExecute($comando);
?>
<html>
    <head><script>setTimeout(function() {
            window.location.replace("../colaborador");
        }, 0);</script></head>
    <body><h1>Obrigado por Ajudar...</h1></body>
</html>
