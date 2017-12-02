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
    date_default_timezone_set('America/Sao_Paulo');

    $dataSimulado = DBSearch("data_simulado","WHERE status = 1 LIMIT 1","dataInicioP,dataInicioS,dataFimP,dataFimS");

    $dateNow = new DateTime(date('Y')."-".date('m')."-".date('d')." ".date('H').":".date('i').":".date('s')); // Data Atual

    $dataInicio[1] = new DateTime($dataSimulado[0]['dataInicioP']); // Data do inicio do 1 simulado
    $dataFim[1] = new DateTime($dataSimulado[0]['dataFimP']); // Data do termino do 1 simulado

    $dataInicio[2] = new DateTime($dataSimulado[0]['dataInicioS']); // Data do inicio do 2 simulado
    $dataFim[2] = new DateTime($dataSimulado[0]['dataFimS']); // Data do termino do 1 simulado

    if($dateNow >= $dataInicio[1] && $dateNow <= $dataFim[1]){ // Se estiver entre o periodo do 1º Simulado
        $prova = 1;
    } else if ($dateNow >= $dataInicio[2] && $dateNow <= $dataFim[2]){ // Se estiver entre o periodo do 2º Simulado
        $prova = 2;
    } else {
        exit();
    }
    $killer = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id']." AND prova = ".$prova,"status");
    if($killer[0]['status'] == 0 || empty($killer)){ //Se o usuario já finalizou o simulado, não salvar OU se o usuario não fez a prova
        exit();
    }
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        echo "<script>window.location.replace(\"LoginSimultaneo.php\");</script>";
        exit();
    }
    unset($resposta);
    $resultado = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id']);
    if(empty($resultado[0])){
        $id_simuladoVigente = DBSearch("data_simulado","WHERE status = 1","id_simulado");
        $query = "INSERT INTO dados_usuario_simulado (usuarios_id,status,prova,id_simulado) VALUES (".$_SESSION['usuario']['id'].",1,$prova,'".$id_simuladoVigente[0]['id_simulado']."');";
        DBExecute($query);
        unset($id_simuladoVigente);
    }

    if(isset($_SESSION['usuario']['simulado']['qmarcadas'])){
        $sql = "UPDATE dados_usuario_simulado SET questoes_marcadas = '".$_SESSION['usuario']['simulado']['qmarcadas']."' WHERE dados_usuario_simulado.usuarios_id = ".$_SESSION['usuario']['id'].";";
        DBExecute($sql);
    }

    if($_POST['finalizar']==1){
        $sql = "UPDATE dados_usuario_simulado SET status = 0 WHERE dados_usuario_simulado.usuarios_id = ".$_SESSION['usuario']['id'].";";
        DBExecute($sql);

        //********* Correção das Questões ******

        $pesquisa = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"questoes_marcadas,pontos");
        if($dateNow>$dataFim[1] && $dateNow<$dataInicio[2]){
            $fimQuestoes = 90; //DE ONDE TERMINA O NUMERO DAS QUESTÕES QUE ESTAMOS BUSCANDO
        } else if ($dateNow > $dataFim[2]){
            $fimQuestoes = 180; //DE ONDE TERMINA O NUMERO DAS QUESTÕES QUE ESTAMOS BUSCANDO
        }

        $respostaCorreta = DBSearch("questoes_simulado","WHERE numero >= 1 AND numero <= ".$fimQuestoes." ORDER BY numero ASC;","alternativa_correta");
        $string = $pesquisa[0]["questoes_marcadas"];
        for($i=1;$i<=$fimQuestoes;$i++){
            $separador = "-".$i."_";
            if(strrpos($string,$separador) !== false){
                $splited = substr($string,strrpos($string,$separador),strlen($separador)+1);
                $letra = substr($splited,-1,1);
                $respostas[$i]=$letra;
            } else{
                $respostas[$i]='-';
            }
        }
        if(!empty($respostas)){
            $pontuacao = 0;
            for($i=1; $i<=$fimQuestoes; $i++){
                $j = $i-1;
                switch($respostaCorreta[$j]['alternativa_correta']){ //Transforma número p/ letra
                        case 1:
                            $respostaCorreta[$j]['alternativa_correta'] = 'A';
                            break;
                        case 2:
                            $respostaCorreta[$j]['alternativa_correta'] = 'B';
                            break;
                        case 3:
                            $respostaCorreta[$j]['alternativa_correta'] = 'C';
                            break;
                        case 4:
                            $respostaCorreta[$j]['alternativa_correta'] = 'D';
                            break;
                        case 5:
                            $respostaCorreta[$j]['alternativa_correta'] = 'E';
                            break;
                }
                if($respostas[$i]==$respostaCorreta[$j]['alternativa_correta']){
                    $pontuacao++;
                }
            }
            $pontuacao *= 5;
            $queryUpdatePontos = "UPDATE dados_usuario_simulado SET pontos = ".$pontuacao." WHERE usuarios_id = ".$_SESSION['usuario']['id'].";";
            DBExecute($queryUpdatePontos);
        }
    }

    echo "Salvo!";
