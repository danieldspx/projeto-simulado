<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type");
    $nome = addslashes($_POST["nome"]);
    $email = addslashes($_POST["email"]);
    $mensagem = addslashes($_POST["mensagem"]);
    
    $corpoEmail = "Nome: $nome<br><br>E-mail: $email<br><br>Mensagem: $mensagem";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $headers .= "From: contas@simuladoenemufv.com.br" . "\r\n";
    $headers .= 'X-Mailer: PHP/' . phpversion();
    if(!empty($nome) && !empty($email) && !empty($mensagem)){
        mail("danield1591998@gmail.com",'=?UTF-8?B?' . base64_encode("Solicitação de serviço") . '?=',$corpoEmail,$headers);
    }
?>