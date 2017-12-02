<?php
    require_once 'add/HeaderSession.php';
    require_once 'private_html_protected/config.php';
    require_once 'private_html_protected/connection.php';
    require_once 'private_html_protected/database.php';

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
        echo "<script>window.location.replace(\"LoginSimultaneo.php\");</script>";
        exit();
    }
    unset($resposta);

    $resultado = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id']);
    if(empty($resultado[0])){
        $query = "INSERT INTO dados_usuario_simulado (usuarios_id) VALUES (".$_SESSION['usuario']['id'].");";
        DBExecute($query);
    }

    if(isset($_SESSION['usuario']['simulado']['qmarcadas'])){
        $sql = "UPDATE dados_usuario_simulado SET questoes_marcadas = '".$_SESSION['usuario']['simulado']['qmarcadas']."' WHERE dados_usuario_simulado.usuarios_id = ".$_SESSION['usuario']['id'].";";
        DBExecute($sql);
    }
    $id_simulado = DBSearch("data_simulado","WHERE status = 1","id_simulado");
    $pesquisa = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id']." AND id_simulado = \"".$id_simulado[0]["id_simulado"]."\"","questoes_marcadas");
    $string = $pesquisa[0]["questoes_marcadas"];
    $string = substr($string,1,strlen($string)-2);
    $vetor = explode("-",$string);
    for($i=0; $i<count($vetor);$i++){
        $vetor[$i] = substr($vetor[$i],0,strpos($vetor[$i],"_"));
    }
    $nowData = null;
    for($i=0; $i<count($vetor);$i++){
        for ($j = 0; $j < count($vetor); $j++){
            if($vetor[$i] < $vetor[$j]){
                $nowData = $vetor[$i];
                $vetor[$i] = $vetor[$j];
                $vetor[$j] = $nowData;
            }
        }
    }
    if(90-count($vetor)!=0){
		echo (90-count($vetor))." questões.";
    } else {
        echo "done";
    }