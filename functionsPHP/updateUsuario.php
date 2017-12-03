<?php
	require_once '../add/HeaderSession.php';
    require_once '../private_html_protected/config.php';
    require_once '../private_html_protected/connection.php';
    require_once '../private_html_protected/database.php';

    $dados['nome'] = $_POST['nome'];
    $dados['sobrenome'] = $_POST['sobrenome'];
    $dados['foto_perfil'] = "img/".$_POST['foto_perfil'];
    $dados['celular'] = $_POST['celular'];

    if(DBUpdate("usuarios","WHERE id = ".$_SESSION['usuario']['id'],$dados)){
        echo "1";
    } else {
        echo "0";
    }