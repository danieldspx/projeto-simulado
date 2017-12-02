<?php
    require_once 'add/HeaderSession.php';
    if (isset($_SESSION['usuario'])) {
        session_start();
        $_SESSION['logout'] = "Você não pode se cadastrar logado.";
        header("Location: principal");
    }
    //Biblioteca do ReCaptcha
    include_once 'recaptchalib.php';
    $secret = "6LeVshcUAAAAAAFsEuZNNHWhE19726P9n4FgTwt8";
    $response = null; //Resposta vazia
    $reCaptcha = new ReCaptcha($secret); //Verifica a chave secreta
    // se submetido, verifique a resposta
    if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"],
            $_POST["g-recaptcha-response"]
        );
    }
    if(($response != null && $response->success) || ($_SESSION['responseTrue'] == true && isset($_SESSION['responseTrue']))){
      if(empty($_SESSION['responseTrue'])){
        $_SESSION['responseTrue'] = true;
      } else {
        unset($_SESSION['responseTrue']);
      }
      $nome = $_POST['nome'];
      $sobrenome = $_POST['sobrenome'];
      $matricula = $_POST['matricula'];
      if(strlen($matricula>4) && substr($matricula,0,1)==0){
          $matricula=substr($matricula,1);
      }
      $telefone = $_POST['telefone'];
      $email = $_POST['emailConfirm']."@ufv.br";
      $ano = $_POST['ano'];
      $senha = md5($_POST['senhaConfirm']);
      $pagamento = $_POST['pagamento'];
      $metodo = $_POST['r-group-metodo'];
      $cifra = $_POST['send'];
      switch(intval($_POST['instituicao'])){
          case 1:
            $instituicao = "Central de Ensino e Desenvolvimento Agrário de Florestal";
            $instituto = 1;
            break;
          case 2:
            $instituicao = "Escola Estadual Serafim Ribeiro de Rezende";
            $instituto = 2;
            break;
      }

     if((empty($nome) || empty($email) || empty($senha) || empty($pagamento)) && empty($cifra)){
        header("Location: principal");
        exit();
      }

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
        $plaintext = $nome.";&explode&;".$matricula.";&explode&;".$telefone.";&explode&;".$email.";&explode&;".$ano.";&explode&;".$senha.";&explode&;".$metodo.";&explode&;".$pagamento.";&explode&;".$instituto.";&explode&;".$sobrenome;

        # creates a cipher text compatible with AES (Rijndael block size = 128)
        # to keep the text confidential
        # only suitable for encoded input that never ends with value 00h
        # (because of default zero padding)
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                    $plaintext, MCRYPT_MODE_CBC, $iv);

        # prepend the IV for it to be available for decryption
        $ciphertext = $iv . $ciphertext;

        # encode the resulting cipher text so it can be represented by a string
        $ciphertext_base64 = base64_encode($ciphertext);
    } else {
        # --- DECRYPTION ---

        $ciphertext_dec = base64_decode($cifra);

        # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);

        # retrieves the cipher text (everything except the $iv_size in the front)
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        # may remove 00h valued characters from end of plain text
        $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);

        $formulario = explode(";&explode&;",$plaintext_dec);

        include_once 'private_html_protected/configCadastro.php';
        include_once 'private_html_protected/connection.php';
        include_once 'private_html_protected/database.php';
        date_default_timezone_set("America/Sao_Paulo");
        $dataHJ = date('d'.'-'.'m'.'-'.'Y'.' '.'H'.':'.'i'.':'.'s');
        $query = "INSERT INTO cadastro_previo(data, nome, sobrenome, matricula, celular, email, instituicao, ano, senha, pagamento, plano) VALUES ('$dataHJ','".$formulario[0]."','".$formulario[9]."','".$formulario[1]."','".$formulario[2]."','".$formulario[3]."',".$formulario[8].",'".$formulario[4]."','".$formulario[5]."',".$formulario[6].",".$formulario[7].")";
        DBExecute($query);//Insere no Cadastro Previo (Onde o ADM aprova o cadastro)

        $corpoEmail = "Nome: ".$formulario[0]."\nSobrenome: ".$formulario[9]."\nMatricula: ".$formulario[1]."\nTelefone: ".$formulario[2]."\nE-mail: ".$formulario[3]."\nAno: ".$formulario[4]."\nSenha: ".$formulario[5]."\nMetodo de pagamento: ".$formulario[6]."\nPlano: ".$formulario[7]."\nInstituicao: ".$formulario[8];
        mail("cadastro@simuladoenemufv.com.br","Solicitação de Cadastro",$corpoEmail,"From: contas@simuladoenemufv.com.br");
        mail("danieldsp1998@gmail.com","Solicitação de Cadastro",$corpoEmail,"From: contas@simuladoenemufv.com.br");
        $corpoEmailTOuser = "<html> <head> <link href=\"https://fortawesome.github.io/Font-Awesome/assets/font-awesome/css/font-awesome.css\" rel=\"stylesheet\"> <link href=\"https://fonts.googleapis.com/css?family=Montserrat\" rel=\"stylesheet\"> <title>Termos de Contrato</title> <meta charset=\"UTF-8\"> </head> <body style=\"background-color: #2c3e50;\"> <div style=\"background-color: #2c3e50;padding: 50px 0px;text-align: center;\"> <h1 style=\"text-align: center;font-size: 30px;font-family: \'Montserrat\', sans-serif;color: #ecf0f1;font-weight: 800;\">Informações de Cadastro - Simulado Enem UFV</h1> <hr style=\"width: 30%;text-align: center;height: 1.5%;background-color: #ecf0f1;\" id=\"split\"> </div> <div style=\"text-align: center; background-color: #34495e; padding: 50px 0px; border-radius: 10px; box-shadow: 0px 0px 8px rgba(236, 240, 241,0.5);\"> <h1 style=\"color: #ecf0f1; font-family: \'Montserrat\', sans-serif; font-size: 28px; font-weight: 700; margin-bottom: 2.5%;\">Solicitação de cadastro enviada com sucesso!</h1> <p style=\"font-weight: 600; margin-left: 20px; color: #f1c40f; line-height: 1.3; font-size: 25px; font-family: \'Montserrat\', sans-serif;\">Aguarde um e-mail de confirmação. Caso você tenha escolhido a opção de pagamento por cartão, você receberá um e-mail onde poderá efetuar o pagamento de modo seguro.</p> </div> <h1 style=\"text-align: center;font-size: 30px;font-family: \'Montserrat\', sans-serif;color: #ecf0f1;font-weight: 800;\">Grêmio Estudantil Diogo Alves de Melo</h1> </body> </html> ";
        $corpoEmailTOuser = wordwrap($corpoEmailTOuser,70);
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: contas@simuladoenemufv.com.br' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        mail($formulario[3],'=?UTF-8?B?' . base64_encode("Solicitação de Cadastro") . '?=',$corpoEmailTOuser,$headers);
    }
?>
<!doctype html>
<html lang="pt" class="no-js">
<head>
  <meta name="robots" content="noindex">
  <meta charset="UTF-8" />
  <title>Cadastro</title>
  <link rel="sortcut icon" href="img/favicon/forms.png" type="image/png"/>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/bootstrap-theme.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
  <!-- remember, jQuery is completely optional -->
  <!-- <script type='text/javascript' src='js/jquery-1.11.1.min.js'></script> -->
  <script type='text/javascript' src='js/jquery.particleground.js'></script>
  <script type='text/javascript' src='js/confirmCadastro.js'></script>
  <link rel="stylesheet" href="css/confirmCadastro.css" />
  <?php include_once("add/analyticstracking.php") ?>
</head>

<body>
<div id="particles">
  <div id="intro">
    <?php if(empty($cifra)){ ?>
      <h1 style="margin-top: 50px;">Quase lá...</h1>
      <p id="userData"><span class="glyphicon glyphicon-user"></span> Dados do Usuário</p>
      <p id="dadosEfetivos"><?php echo "Nome: $nome $sobrenome<br>Matrícula: $matricula<br>Instituição: $instituicao<br>E-mail: $email<br>"?></p>
      <hr id="splitter">
      <?php if($metodo == 1){?>
        <p>Você escolheu pagar com o Cartão. Em alguns dias você recebera um e-mail com um link onde será possivel realizar o pagamento com o cartão. Basta confirmar o cadastro. Este método é utilizado para que apenas alunos da Cedaf possam acessar o sistema.</p>
      <?php } else { ?>
        <p>Você escolheu o pagamento direto. Confirme seu cadastro logo abaixo. Procure o Diretor Tesoureiro, Daniel dos Santos (Sala: PVC 6) ou vá até a sede do GEDAM (Perto da lagoinha), para efetuar o pagamento e obter acesso ao sistema. Caso tenha dificuldades ou dúvidas, procure algum integrante do GEDAM ou entre em contato: "gremio@ufv.br". Utilize o assunto "Pagamento direto". </p>
      <?php }; ?>
      <table id="links">
        <tr>
          <td>
            <form action="" method="POST" name="confirmation">
              <input type="text" value="<?php echo $ciphertext_base64; ?>" name="send" id="send"/>
              <a class="btn" onclick="document.confirmation.submit()">Confirmar <span class="glyphicon glyphicon-send"></span></a>
            </form>
          </td>
          <td>
            <a href="principal" class="btn">Cancelar <span class="glyphicon glyphicon-remove-sign"></span></a>
          </td>
        </tr>
      </table>
    <?php } else { ?>
      <h1 id="completo" style="margin-top: 50px;"><sub><span class="glyphicon glyphicon-cloud"></span></sub> Envio Completo!</h1>
      <p>Sua solicitação foi enviada com sucesso. Sua conta será liberada após o pagamento.</p>
      <p>Em breve você receberá um e-mail com mais informações. Fique atento!</p>
      <p><span class="glyphicon glyphicon-heart"></span> A Equipe GEDAM agradece o seu cadastro <span class="glyphicon glyphicon-heart"></span></p>
      <a href="principal" class="btn"><span class="glyphicon glyphicon-arrow-left"></span> Voltar</a>
    <?php }; ?>
  </div>
</div>
</body>
</html>
    <?php } else {
            header("Location: cadastro");
          } ?>
