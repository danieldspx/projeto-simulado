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

    $situacao = $_POST['situacao'];
    $nquestao = $_POST['nquestao'];
    $area = $_POST['area'];
    $narea;
    $restArea;
    switch($area){
        case "Matemática e suas Tecnologias":
            $restArea = "certas_area2,total_area2,certas_area3,total_area3,certas_area4,total_area4";
            $narea = 1;
            break;
        case "Ciências da Natureza e suas Tecnologias":
            $restArea = "certas_area1,total_area1,certas_area3,total_area3,certas_area4,total_area4";
            $narea = 2;
            break;
        case "Ciências Humanas e suas Tecnologias":
            $restArea = "certas_area1,total_area1,certas_area2,total_area2,certas_area4,total_area4";
            $narea = 3;
            break;
        case "Linguagens, Códigos e suas Tecnologias":
            $restArea = "certas_area1,total_area1,certas_area2,total_area2,certas_area3,total_area3";
            $narea = 4;
            break;
    }
    $dados = DBSearch("dados_usuario","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"questoes_resolvidas");
    
    if(strpos($dados[0]['questoes_resolvidas'],"-".$nquestao."-") === false){//Não resolvida ainda
    
        $existe = DBSearch("dados_usuario","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"questoes_resolvidas, usuarios_id");
        if(isset($existe[0]['questoes_resolvidas'])){
           if($situacao == "certo"){
               $query = "UPDATE dados_usuario SET certas_mes = (certas_mes+1), respostas_mes = (respostas_mes+1), certas_total = (certas_total+1), respostas_total = (respostas_total+1), certas_area".$narea." = (certas_area".$narea."+1), total_area".$narea." = (total_area".$narea."+1), questoes_resolvidas = concat(questoes_resolvidas,\"".$nquestao."-\") WHERE usuarios_id = ".$_SESSION['usuario']['id'].";";
               DBExecute($query);     
           } else {
               $query = "UPDATE dados_usuario SET respostas_mes = (respostas_mes+1), respostas_total = (respostas_total+1), total_area".$narea." = (total_area".$narea."+1), questoes_resolvidas = concat(questoes_resolvidas,\"".$nquestao."-\")WHERE usuarios_id = ".$_SESSION['usuario']['id'].";";
               DBExecute($query);
           }
        } else{
            if($situacao == "certo"){
                $query = "INSERT INTO dados_usuario(usuarios_id,questoes_resolvidas,certas_mes,respostas_mes,certas_total,respostas_total,certas_area".$narea.",total_area".$narea.",$restArea) VALUES (".$_SESSION['usuario']['id'].","."'-".$nquestao."-'".",1,1,1,1,1,1,0,0,0,0,0,0);";
                DBExecute($query);            
            } else {
                $query = "INSERT INTO dados_usuario(usuarios_id,questoes_resolvidas,respostas_mes,respostas_total,total_area".$narea.",certas_area".$narea.",$restArea,certas_mes,certas_total) VALUES (".$_SESSION['usuario']['id'].","."'-".$nquestao."-'".",1,1,1,0,0,0,0,0,0,0,0,0);";
                DBExecute($query);   
            } 
        }
    }

?>