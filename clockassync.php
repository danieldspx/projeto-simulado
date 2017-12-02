<?php
    require_once 'add/HeaderSession.php';
    require_once 'private_html_protected/config.php';
    require_once 'private_html_protected/connection.php';
    require_once 'private_html_protected/database.php';
    date_default_timezone_set('America/Sao_Paulo');
    $respostaTime = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id']." LIMIT 1","hora_inicio,data_inicio");
    $_SESSION['usuario']['condicao'] = intval(empty($respostaTime[0]["hora_inicio"]));
    if($_SESSION['usuario']['condicao']==1){
        echo "<script>var horas = 4;";
        echo "var minutos = 0;";
        echo "var segundos = 0;</script>";
    } else {
        $data_explode = explode("/", $respostaTime[0]["data_inicio"]);
        $hora_explode = explode(":", $respostaTime[0]["hora_inicio"]);
        
        $horaAnterior = $hora_explode[0];
        $minutoAnterior = $hora_explode[1];
        $segundoAnterior = $hora_explode[2];
        $diaAnterior = $data_explode[0];
        $mesAnterior = $data_explode[1];
        $anoAnterior = $data_explode[2];
        
        $horaAtual = date("H");
        $minutoAtual = date("i");
        $segundoAtual = date("s");
        $diaAtual = date("d");
        $mesAtual = date("m");
        $anoAtual = date("Y");

        $strStart = $mesAnterior."/".$diaAnterior."/".$anoAnterior." ".$horaAnterior.":".$minutoAnterior.":".$segundoAnterior;
        $strEnd = $mesAtual."/".$diaAtual."/".$anoAtual." ".$horaAtual.":".$minutoAtual.":".$segundoAtual;
        $dteStart = new DateTime($strStart);
        $dteEnd   = new DateTime($strEnd);
        $dteDiff  = $dteStart->diff($dteEnd);
        $permission = true;
        if(intval($dteDiff->format("%Y"))==0 && intval($dteDiff->format("%M"))==0 && intval($dteDiff->format("%D"))==0){
            $thora = intval($dteDiff->format("%H"));
            $tminuto = intval($dteDiff->format("%I"));
            $tsegundo = intval($dteDiff->format("%S"));
            $tTotal = $thora*3600 + $tminuto*60 + $tsegundo;
            if($tTotal>14400){
                $permission = false;
            }
        } else {
            $permission = false;
        }
        if($permission){
            $thora = 3 - $thora;
            $tminuto =  59 - $tminuto;
            $tsegundo = 59 - $tsegundo;
            echo "<script>horas = ".$thora.";";
            echo "minutos = ".$tminuto.";";
            echo "segundos = ".$tsegundo.";</script>";
        } else {
                        //TEMPO ACABOU
        }
    }
?>