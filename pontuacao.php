<?php
    require_once 'add/HeaderSession.php';
    require 'private_html_protected/config.php';
    require 'private_html_protected/connection.php';
    require 'private_html_protected/database.php';

    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
       echo "<script>window.location.replace(\"LoginSimultaneo.php\");</script>";
       exit();
    }
    
    $nquestao = $_POST['nquestao'];
    $user_receber = $_POST['user_receber'];
    $user_vot = $_SESSION['usuario']['id'];
    
    $dados = DBSearch("comentarios","WHERE questoes_idquestao = ".$nquestao." AND usuarios_id = ".$user_receber,"avaliacoes,pontuacao");
    $pontos = $dados[0]['pontuacao'];
    if(empty($dados[0]['pontuacao'])){
        $pontos = 0;
    }
    if(strpos($dados[0]['avaliacoes'],"-".$user_vot."-") === false && $user_receber!=$user_vot){//Não votou ainda
        if(empty($dados[0]['avaliacoes'])){ // Preenchido pela primeira vez
            $query = "UPDATE comentarios SET comentarios.pontuacao = ".($pontos+1).", avaliacoes = '-".$user_vot."-' WHERE usuarios_id = $user_receber;";
            DBExecute($query);
        } else {
            $query = "UPDATE comentarios SET comentarios.pontuacao = ".($pontos+1).", avaliacoes = '".$user_vot."-' WHERE usuarios_id = $user_receber;";
            DBExecute($query);
        }
        $fQuery = "UPDATE usuarios SET pontuacao =  (pontuacao+1) WHERE  id =$user_receber;";
        DBExecute($fQuery);
    }
    $pontos = DBSearch("comentarios","WHERE questoes_idquestao = ".$nquestao." AND usuarios_id = ".$user_receber,"pontuacao");
    echo $pontos[0]['pontuacao'];
?>