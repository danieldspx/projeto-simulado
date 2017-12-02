<?php
    require_once 'add/HeaderSession.php';
    if (isset($_SESSION['usuario'])) {
        session_start();
        $_SESSION['logout'] = "Você não pode se cadastrar logado.";
        header("Location: principal");
    }
    $cifra = $_GET['keyEn'];
    $email = $_POST['email'];
    if(isset($email)){
        if(strpos($email,"@") !== false){
            $email = substr($email,0,strpos($email,"@"));
        }
        $email = $email."@ufv.br";
    }
    $senha = $_POST['newRpswd'];

    # the key should be random binary, use scrypt, bcrypt or PBKDF2 to
    # convert a string into a key
    # key is specified using hexadecimal
    $key = pack('H*', md5(md5("#%67*Se;+=cu}rit-_y@")));

    # create a random IV to use with CBC encoding
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

   if(empty($cifra)){
      # --- ENCRYPTION ---
      # Texto a ser criptografado
      $plaintext = ";&explode&;".$email.";&explode&;";
      
      # creates a cipher text compatible with AES (Rijndael block size = 128)
      # to keep the text confidential 
      # only suitable for encoded input that never ends with value 00h
      # (because of default zero padding)
      $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,$plaintext, MCRYPT_MODE_CBC, $iv);

      # prepend the IV for it to be available for decryption
      $ciphertext = $iv . $ciphertext;
      
      # encode the resulting cipher text so it can be represented by a string
      $ciphertext_base64 = base64_encode($ciphertext);

      if(isset($email) && strpos($email,"@ufv.br") !== false){
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: contas@simuladoenemufv.com.br' . "\r\n";
        $corpoEmail = "<html> <head> <link href=\"https://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css\" rel=\"stylesheet\"> <link href=\"https://fonts.googleapis.com/css?family=Montserrat\" rel=\"stylesheet\"> <title>Termos de Contrato</title> <meta charset=\"UTF-8\"> <style> .btn:hover { background: #0db896; background-image: -webkit-linear-gradient(top, #0db896, #0a9478); background-image: -moz-linear-gradient(top, #0db896, #0a9478); background-image: -ms-linear-gradient(top, #0db896, #0a9478); background-image: -o-linear-gradient(top, #0db896, #0a9478); background-image: linear-gradient(to bottom, #0db896, #0a9478); text-decoration: none; } </style> </head> <body style=\"background-color: #2c3e50;\"> <div style=\"background-color: #2c3e50;padding: 50px 0px;text-align: center;\"> <h1 style=\"text-align: center;font-size: 30px;font-family: \'Montserrat\', sans-serif;color: #ecf0f1;font-weight: 800;\">Solicitação de alteração de senha</h1> <hr style=\"width: 10%;text-align: center;height: 1.5%;background-color: #ecf0f1;\" id=\"split\"> </div> <div style=\"text-align: center; background-color: #34495e; padding: 50px 0px; border-radius: 10px; box-shadow: 0px 0px 8px rgba(236, 240, 241,0.5);\"> <h1 style=\"color: #ecf0f1; font-family: \'Montserrat\', sans-serif; font-size: 28px; font-weight: 700; margin-bottom: 2.5%;\">Alteração de Senha</h1> <a href=\"http://www.simuladoenemufv.com.br/recuperacao/?keyEn='".$ciphertext_base64."'\" style=\"text-decoration: none;color: #ecf0f1;\" target=\"_BLANK\" ><button class=\"btn\" style=\"border: none;background: #1abc9c; background-image: -webkit-linear-gradient(top, #1abc9c, #1acca8); background-image: -moz-linear-gradient(top, #1abc9c, #1acca8); background-image: -ms-linear-gradient(top, #1abc9c, #1acca8); background-image: -o-linear-gradient(top, #1abc9c, #1acca8); background-image: linear-gradient(to bottom, #1abc9c, #1acca8); -webkit-border-radius: 7; -moz-border-radius: 7; border-radius: 7px; font-family: Arial; color: #ecf0f1; font-size: 20px; padding: 10px 20px 10px 20px; text-decoration: none; border-radius: 10px;\">Clique aqui para alterar a senha</a></button> <p style=\"font-family: Arial, sans-serif; font-weight: 600; margin-left: 20px; color: #f1c40f; line-height: 1.3;\">Caso o botão acima não funcione, cole este link no seu navegador: <a id=\"link\" style=\"font-family: \'Montserrat\', sans-serif; color: #3498db; text-decoration: none;\" href=\"http://www.simuladoenemufv.com.br/recuperacao/?keyEn='".$ciphertext_base64."'\" target=\"_BLANK\">http://www.simuladoenemufv.com.br/recuperacao/?keyEn='".$ciphertext_base64."'</a></p> </div> </body> </html>";
        mail($email,"Alterar Senha",$corpoEmail,$headers);
      }

   } elseif (isset($senha)) {
       # --- DECRYPTION ---

      $ciphertext_dec = base64_decode($cifra);
      
      # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
      $iv_dec = substr($ciphertext_dec, 0, $iv_size);
      
      # retrieves the cipher text (everything except the $iv_size in the front)
      $ciphertext_dec = substr($ciphertext_dec, $iv_size);

      # may remove 00h valued characters from end of plain text
      $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
      
      $emailForm = explode(";&explode&;",$plaintext_dec);
      include_once 'private_html_protected/config.php';
      include_once 'private_html_protected/connection.php';
      include_once 'private_html_protected/database.php';
      $exite = DBSearch("usuarios","WHERE email = '".$emailForm[1]."';","email");
      if(isset($exite[0]['email'])){
        $query ="UPDATE usuarios SET senha = '".md5($senha)."' WHERE email = '".$emailForm[1]."';";
        DBExecute($query);
      }
   }

?>
<!doctype html>
<html lang="pt" class="no-js">
<head>
  <meta name="robots" content="noindex">
  <meta charset="UTF-8" />
  <title>Recuperação</title>
  <link rel="sortcut icon" href="img/favicon/forms.png" type="image/png"/>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  <!-- remember, jQuery is completely optional -->
  <!-- <script type='text/javascript' src='js/jquery-1.11.1.min.js'></script> -->
  <script type='text/javascript' src='js/jquery.particleground.js'></script>
  <script type='text/javascript' src='js/passwordReset.js'></script>
  <link rel="stylesheet" href="css/passwordReset.css" />
  <?php include_once("add/analyticstracking.php") ?>
</head>
<body>
<div id="particles">
  <div id="intro">
    <?php if(empty($cifra) && empty($email)){ ?>
      <script>
          function vEmail(elemento){
                var email = elemento.value;
                var n = email.search("@");
                if(n != -1){
                    elemento.value = email.substr(0,n);
                }
            }
      </script>
      <h1>RECUPERAÇÃO</h1>
      <p>Esqueceu sua senha? Digite seu email abaixo para iniciar o processo de reinicialização.</p>
      <hr id="splitter">
        <form class="form-horizontal" name="redefinicao" method="POST" style="margin-bottom: 30px;" action="" autocomplete="off">
            <fieldset>
                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="email"><p>E-mail</p></label>  
                <div class="col-md-4">
                <div class="input-group">
                <input id="email" name="email" class="form-control" onblur="vEmail(this)" onmouseout="vEmail(this)" placeholder="Não digite o @ufv.br" type="text" required="">
                <span class="input-group-addon">@ufv.br</span>
                </div>
                </div>
                </div>
                <table id="links">
                    <tr>
                    <td>
                        <button class="button" onclick="vEmail(document.getElementById('email'))" onmouseover="vEmail(document.getElementById('email'))"  type="submit">Confirmar <span class="glyphicon glyphicon-send"></span></button>
                    </td>
                    </tr>
                </table>
            </fieldset>
        </form>
        <table id="links">
            <tr>
                <td>
                    <a style="text-decoration: none;" href="principal" class="button">Cancelar <span class="glyphicon glyphicon-remove-sign"></span></a>
                </td>
            </tr>
        </table>
    <?php } elseif (isset($email)) { ?>
      <h1 id="completo"><sub><span class="glyphicon glyphicon-cloud"></span></sub> Envio Completo!</h1>
      <p>Sua solicitação foi enviada com sucesso. Clique no link que enviamos para <?php echo $email?>.</p>
      <p>Verifique a caixa de Spam caso não encontre o e-mail.</p>
      <a href="principal" class="button"><span class="glyphicon glyphicon-arrow-left"></span> Voltar</a>
    <?php } elseif (empty($senha)) { ?>
    <script type='text/javascript'>
        function verificaSenha(trying){
            var pass1 = document.getElementById('newRpswd');
            var pass2 = document.getElementById('newPswd');
            if(pass1.value != pass2.value){
                document.getElementById('verifSenha').style.display="block";
            } else {
                document.getElementById('verifSenha').style.display="none";
                if(trying == 1){
                    document.novaSenha.submit();
                }
            }
        }
  </script>
    <h1><sub><span class="glyphicon glyphicon-cloud-upload"></span></sub> Alteração de Senha</h1>
            <form class="form-horizontal" name="novaSenha" method="POST" action="">
                <fieldset>

                <!-- Form Name -->

                <!-- Password input-->
                <div class="form-group">
                <label class="col-md-4 control-label" id="txtNS" for="newPswd">Nova senha</label>
                <div class="col-md-4">
                    <input id="newPswd" name="newPswd" onblur="verificaSenha(0)" type="password" placeholder="Digite a nova senha" class="form-control input-md" required="">
                </div>
                </div>
                <!-- Password input-->
                <div class="form-group">
                <label class="col-md-4 control-label" id="txtCS" for="newRpswd">Confirme a senha</label>
                <div class="col-md-4">
                    <input id="newRpswd" name="newRpswd" onblur="verificaSenha(0)" type="password" placeholder="Digite novamente" class="form-control input-md" required="">
                </div>
                </div>
                </fieldset>
                <p id="verifSenha" style="display: none; color: #e74c3c">Senhas não conferem</p>
                <a onclick="verificaSenha(1)" class="btn btn-lg btn-success"><span class="glyphicon glyphicon-floppy-open"></span> Alterar!</a>
            </form>
    <?php }else { ?>
        <h1><sub><span class="glyphicon glyphicon-saved"></span></sub> Senha alterada com sucesso!</h1>
        <a href="principal" class="button"><span class="glyphicon glyphicon-arrow-left"></span> Voltar</a>
    <?php }; ?>
  </div>
</div>
</body>
</html>
