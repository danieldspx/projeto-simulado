<?php
    require_once 'add/HeaderSession.php';
    include 'private_html_protected/config.php';
    include 'private_html_protected/database.php';
    include 'private_html_protected/connection.php';
    $dados = DBSearch("dados_usuario","WHERE usuarios_id = ".$_SESSION['usuario']['id'].";","questoes_resolvidas");
    echo $dados[0]['questoes_resolvidas'];
?>