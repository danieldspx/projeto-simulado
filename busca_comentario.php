<?php
    require_once 'add/HeaderSession.php';
    require_once 'private_html_protected/config.php';
    require_once 'private_html_protected/connection.php';
    require_once 'private_html_protected/database.php';
    
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        echo "<script>window.location.replace(\"LoginSimultaneo.php\");</script>";
        $resposta = array();
        unset($resposta);
        exit();
    }
    $resposta = array();
    unset($resposta);
    $id_comentario = $_POST['id_comentario']; 
    $id_questao = $_POST['id_questao'];
    $getComment = DBSearch("comentarios","INNER JOIN usuarios ON usuarios.id = usuarios_id WHERE questoes_idquestao = $id_questao AND idcomentario = $id_comentario","comentario,CONCAT(usuarios.nome,' ',usuarios.sobrenome) AS nome,usuarios_id,comentarios.pontuacao AS pontuacao");
    
    echo "<div style='margin-left: 0px;' class='row' id='painelComentario".$id_questao.$id_comentario."'>";
        echo "<div class=\"panel panel-primary\" >";
            echo "<div class=\"panel-heading\"><h3 style=\"padding-left: 0px;\" class=\"panel-title col-md-10\">".$getComment[0]['nome']."</h3><span id=\"pontos".$getComment[0]['usuarios_id']."\" class=\"badge\" >".$getComment[0]['pontuacao']."</span>&nbsp;&nbsp;&nbsp;<span id=\"imagem".$id_questao.$id_comentario."\"><span class='badge' style='padding: 4px;border-radius: 15px;background-color: #00e676;color: #333;cursor: pointer;' onclick='pontuar($id_questao,".$getComment[0]['usuarios_id'].")'><span class='glyphicon glyphicon-menu-up' aria-hidden='true'></span></span></span></div>";
            echo "<div class=\"panel-body\"><p style=\"position: relative; display: inline-block;\">".$getComment[0]['comentario']."</p></div>";
        echo "</div>";
    echo "</div>";
?>
