<?php
	require_once '../add/HeaderSession.php';
    require_once '../private_html_protected/config.php';
    require_once '../private_html_protected/connection.php';
    require_once '../private_html_protected/database.php';

    $dados['senha'] = md5($_POST['senhaNova']);
    
    $senhaDB = DBSearch('usuarios',"WHERE id = ".$_SESSION['usuario']['id'],"senha");

    if($senhaDB[0]['senha'] == md5($_POST['senhaAtual'])){
        if(DBUpdate("usuarios","WHERE id = ".$_SESSION['usuario']['id'],$dados)){
            echo "1";
        } else {
            echo "0";
        }
    } else {
        echo "2";
    }

    unset($dados);
    unset($senhaDB);