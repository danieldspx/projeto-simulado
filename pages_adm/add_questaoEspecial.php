 <?php
    require_once '../add/HeaderSession.php';
    if($_SESSION['usuario']['nivelAcessoId'] != 1 && $_SESSION['usuario']['nivelAcessoId'] != 2){
       $_SESSION = array();
       session_destroy();
       header("Location: ../errorPagina");
       exit();
    }
 ?>
<html>
<head>
        <title>Adicionando Questao</title>
    </head>
    <body>
        <?php
            require '../private_html_protected/config.php';
            require '../private_html_protected/connection.php';
            require '../private_html_protected/database.php';

            $numQuestao = $_POST['numQuestao'];
            $anoProva = $_POST['anoProva'];
            $idQuestao = $_POST['idQuestao'];
            $enunciado = $_POST['enunciado'];
            $enunciado = addslashes($enunciado);
            $area = $_POST['area'];
            $disciplina = $_POST['disciplina'];
            $alternativaA = $_POST['alternativaA'];
            $alternativaA = addslashes($alternativaA);
            $alternativaB = $_POST['alternativaB'];
            $alternativaB = addslashes($alternativaB);
            $alternativaC = $_POST['alternativaC'];
            $alternativaC = addslashes($alternativaC);
            $alternativaD = $_POST['alternativaD'];
            $alternativaD = addslashes($alternativaD);
            $alternativaE = $_POST['alternativaE'];
            $alternativaE = addslashes($alternativaE);
            $alternativaCorreta = $_POST['alternativaCorreta'];
            $comando = "INSERT INTO questoes(idquestao, ano, numero, areas_idarea, disciplinas_id_disciplina, alternativa_a, alternativa_b, alternativa_c, alternativa_d, alternativa_e, alternativa_correta, descricao) VALUES ($idQuestao, $anoProva, $numQuestao, $area, $disciplina, \"$alternativaA\", \"$alternativaB\", \"$alternativaC\", \"$alternativaD\", \"$alternativaE\", \"$alternativaCorreta\", \"$enunciado\")";
            DBExecute($comando);
            echo "<h1>ADICIONADO COM SUCESSO! Obrigado ".$_SESSIO['usuario']['nome']."</h1>";
            sleep(4);
            header("Location: ../administrador")
        ?>
    </body>
</html>
