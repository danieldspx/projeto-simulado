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
    $pesquisa = DBSearch("notificacoes","","MAX(id) AS maximo");
    $valor = $pesquisa[0]['maximo'] + 1;
    if(empty($pesquisa[0]['maximo'])){
        $valor = 1;
    }
    $dados['id'] = $valor;
    $dados['conteudo'] = $_POST['mensagem'];
    DBCadastro("notificacoes",$dados);
    echo "$valor&&&";