<?php
    require_once 'add/HeaderSession.php';
    require 'private_html_protected/config.php';
    require 'private_html_protected/connection.php';
    require 'private_html_protected/database.php';

    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        echo "<script>window.location.replace(\"LoginSimultaneo.php\");</script>";
        $resposta = array();
        unset($resposta);
        exit();
    }
    $resposta = array();
    unset($resposta);
            
    $id_notf = $_POST['id_notf'];
    $tipo = $_POST['tipo'];
    $pesquisa = DBSearch("notificacoes_dados","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"notif_ms,id");
    if($tipo==1){//Salvar no DB que a Notificação ainda n foi lida
        if(empty($pesquisa[0])){//Não há registro do usuário no DB
            $dados['usuarios_id']=$_SESSION['usuario']['id'];
            $dados['notif_ms']=$id_notf."-";
            DBCadastro("notificacoes_dados",$dados);
            $dados = array();
            unset($dados);
        } else {
            $notif_texto = $pesquisa[0]['notif_ms']."$id_notf-";
            $query="UPDATE notificacoes_dados SET notif_ms='$notif_texto' WHERE id = ".$pesquisa[0]['id'];
            DBExecute($query);
            unset($notif_texto,$query);
        }
    } else {//Salvar que a notificação foi lida
        $identificacoes = explode("-",$id_notf);
        $notif_ms = explode("-",$pesquisa[0]['notif_ms']);
        $notif_ms = str_replace($identificacoes,null,$notif_ms);
        $notif_ms = array_filter($notif_ms);
        $novas_notif = implode("-",$notif_ms);
        $query="UPDATE notificacoes_dados SET notif_ms='$novas_notif' WHERE id = ".$pesquisa[0]['id'];
        DBExecute($query);
        unset($identificacoes,$notif_ms,$novas_notif,$query);
    }

    $pesquisa = array();
    unset($pesquisa,$id_notf,$tipo);

?>