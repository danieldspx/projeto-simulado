<?php
    require_once '../add/HeaderSession.php';
    $matricula = $_POST['matricula'];
    if($_SESSION['usuario']['nivelAcessoId'] != 1){
       $_SESSION = array();
       session_destroy();
       header("Location: ../../errorPagina");
       exit();
    }
    require '../private_html_protected/configCadastro.php';
    require '../private_html_protected/connection.php';
    require '../private_html_protected/database.php';
    $query = "UPDATE cadastro_previo SET status = 1 WHERE matricula = $matricula";
    DBExecute($query);
?>