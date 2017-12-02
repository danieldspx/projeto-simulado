<?php
    require_once '../add/HeaderSession.php';
    if($_SESSION['usuario']['nivelAcessoId'] != 1){
       $_SESSION = array();
       session_destroy();
       header("Location: ../errorPagina");
    }
    $logout = $_GET['slogout'];
    if($logout == '1'){
        $_SESSION = array();
        session_destroy();
    }
    if (empty($_SESSION['usuario'])) {
        session_start();
        $_SESSION['logout'] = "Logout efetuado com sucesso.";
        header("Location: ../principal");
    }

    require '../private_html_protected/config.php';
    require '../private_html_protected/connection.php';
    require '../private_html_protected/database.php';
    date_default_timezone_set('America/Sao_Paulo');
    $dados['nome'] = $_POST['nome'];
    $dados['sobrenome'] = $_POST['sobrenome'];
    $dados['senha'] = $_POST['senha'];
    $dados['foto_perfil'] = "img/".$_POST['iconePerfil'];
    $dados['email'] = $_POST['email'];
    $email = $dados['email'];
    $dados['situacao_id'] = 1;
    $dados['niveis_acesso_id'] = $_POST['acesso'];
    $dados['pontuacao'] = 0;
    $dados['matricula'] = $_POST['matricula'];
    $dados['celular'] = $_POST['celular'];
    $dados['instituicao'] = $_POST['instituicao'];
    $dados['ano'] = $_POST['ano'];
    $dados['metodo_pgt'] = $_POST['metodo_pgt'];
    $dados['plano'] = $_POST['plano_pgt'];
    $dados['created'] = date('d'.'-'.'m'.'-'.'Y'.' '.'H'.':'.'i'.':'.'s');
    DBCadastro("usuarios",$dados);
    unset($dados);
    $corpoEmailTOuser = "<html> <head> <link href=\"https://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css\" rel=\"stylesheet\"> <link href=\"https://fonts.googleapis.com/css?family=Montserrat\" rel=\"stylesheet\"> <title>Termos de Contrato</title> <meta charset=\"UTF-8\"> </head> <body style=\"background-color: #2c3e50;\"> <div style=\"background-color: #2c3e50;padding: 50px 0px;text-align: center;\"> <h1 style=\"text-align: center;font-size: 30px;font-family: \'Montserrat\', sans-serif;color: #ecf0f1;font-weight: 800;\">Confirmação de Cadastro - Simulado Enem UFV</h1> <hr style=\"width: 30%;text-align: center;height: 1.5%;background-color: #ecf0f1;\" id=\"split\"> </div> <div style=\"text-align: center; background-color: #34495e; padding: 50px 0px; border-radius: 10px; box-shadow: 0px 0px 8px rgba(236, 240, 241,0.5);\"> <h1 style=\"color: #ecf0f1; font-family: \'Montserrat\', sans-serif; font-size: 28px; font-weight: 700; margin-bottom: 2.5%;\">Você foi cadastrado com sucesso! A partir deste instante você pode acessar sua conta quando quiser.</h1> </div> <h1 style=\"text-align: center;font-size: 30px;font-family: \'Montserrat\', sans-serif;color: #ecf0f1;font-weight: 800;\">Grêmio Estudantil Diogo Alves de Melo</h1> </body> </html>";
    $corpoEmailTOuser = wordwrap($corpoEmailTOuser,70);
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= 'From: contas@simuladoenemufv.com.br' . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();
    mail($email,'=?UTF-8?B?' . base64_encode("Confirmação de Cadastro") . '?=',$corpoEmailTOuser,$headers);
    header("Location: ../administrador");
?>
