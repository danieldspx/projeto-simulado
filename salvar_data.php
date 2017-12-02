<?php
    require_once 'add/HeaderSession.php';
    require_once 'private_html_protected/config.php';
    require_once 'private_html_protected/connection.php';
    require_once 'private_html_protected/database.php';

    $logout = $_GET['slogout'];
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
        echo "<script>window.location.replace(\"LoginSimultaneo.php\");</script>";
        exit();
    }
    unset($resposta);
    $busca = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id'].";");
    date_default_timezone_set('America/Sao_Paulo');

    $dataSimulado = DBSearch("data_simulado","WHERE status = 1 LIMIT 1","dataInicioP,dataInicioS,dataFimP,dataFimS");

    $dateNow = new DateTime(date('Y')."-".date('m')."-".date('d')." ".date('H').":".date('i').":".date('s')); // Data Atual

    $dataInicio[1] = new DateTime($dataSimulado[0]['dataInicioP']); // Data do inicio do 1 simulado
    $dataFim[1] = new DateTime($dataSimulado[0]['dataFimP']); // Data do termino do 1 simulado

    $dataInicio[2] = new DateTime($dataSimulado[0]['dataInicioS']); // Data do inicio do 2 simulado
    $dataFim[2] = new DateTime($dataSimulado[0]['dataFimS']); // Data do termino do 1 simulado

    if($dateNow >= $dataInicio[1] && $dateNow <= $dataFim[1]){ // Se estiver entre o periodo do 1º Simulado
        $prova = 1;
    } else if ($dateNow >= $dataInicio[2] && $dateNow <= $dataFim[2]){ // Se estiver entre o periodo do 2º Simulado
        $prova = 2;
    } else {
        exit();
    }
    if(empty($busca[0])){
        $id_simuladoVigente = DBSearch("data_simulado","WHERE status = 1","id_simulado");
        $queryCreation = "INSERT INTO dados_usuario_simulado(usuarios_id,hora_inicio,data_inicio,status,id_simulado) VALUES (".$_SESSION['usuario']['id'].",'".date("H:i:s")."','".date("d/m/Y")."',1,'".$id_simuladoVigente[0]['id_simulado']."');";
        DBExecute($queryCreation);
        unset($id_simuladoVigente);
    } else {
        $queryUpdate = "UPDATE dados_usuario_simulado SET hora_inicio='".date("H:i:s")."',data_inicio='".date("d/m/Y")."',status = 1, prova = ".$prova." WHERE usuarios_id = ".$_SESSION['usuario']['id'];
        DBExecute($queryUpdate);
    }
