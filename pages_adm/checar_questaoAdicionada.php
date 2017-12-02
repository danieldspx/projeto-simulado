<?php
    require_once '../add/HeaderSession.php';
    require_once '../private_html_protected/config.php';
    require_once '../private_html_protected/connection.php';
    require_once '../private_html_protected/database.php';
    $ano = $_POST['anoProva'];
    $mes = $_POST['mesProva'];
    if($mes < 10){
        $mes = intval($mes);
        $mes = "0".$mes;
    }
    $numero = $_POST['numQuestao'];
    $resposta = DBSearch("questoes_simulado","WHERE id_simulado = '".$mes.$ano."' AND numero = $numero","numero");
    if(isset($resposta[0]['numero'])){
        echo "Já foi adicionada.";
    } else {
        echo "Não foi adicionada ainda.";
    }
?>