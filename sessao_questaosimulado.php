<?php
    require_once 'add/HeaderSession.php';
    require_once 'private_html_protected/config.php';
    require_once 'private_html_protected/connection.php';
    require_once 'private_html_protected/database.php';

    //Atualiza a sessao do usuario, para as questoes selecionadas

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
        header("Location: LoginSimultaneo.php");
    }
    unset($resposta);

    $nquestao = $_POST['nquestao'];
    $letra = $_POST['letra'];

    switch($letra){
        case "7fc56270e7a70fa81a5935b72eacbe29":
            $letra = "A";
            break;
        case "9d5ed678fe57bcca610140957afab571":
            $letra = "B";
            break;
        case "0d61f8370cad1d412f80b84d143e1257":
            $letra = "C";
            break;
        case "f623e75af30e62bbd73d6df5b50bb7b5":
            $letra = "D";
            break;
        case "3a3ea00cfc35332cedf6e5e9a32e94da":
            $letra = "E";
            break;
    }

    if(strpos($_SESSION['usuario']['simulado']['qmarcadas'],"-".$nquestao."_")===false){//Não está na sessão
        if(empty($_SESSION['usuario']['simulado']['qmarcadas'])){ //Sessao vazia
            $_SESSION['usuario']['simulado']['qmarcadas'] = "-".$nquestao."_".$letra."-";
        } else {
            $_SESSION['usuario']['simulado']['qmarcadas'] = $_SESSION['usuario']['simulado']['qmarcadas'].$nquestao."_".$letra."-";
        }
    }else{//Está na sessão
        $sessaoString = $_SESSION['usuario']['simulado']['qmarcadas'];
        $separador = "-".$nquestao."_";
        $splited = substr($sessaoString,strrpos($sessaoString,$separador),strlen($separador)+1);
        $_SESSION['usuario']['simulado']['qmarcadas'] = str_replace($splited,$separador.$letra,$sessaoString);
    }



?>
