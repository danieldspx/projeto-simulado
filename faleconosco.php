<?php
    $formulario = $_POST['formulario'];
    $showit = false;
    $nome = addslashes($_POST['nome']);
    $email = addslashes($_POST['email']);
    $assunto = addslashes($_POST['assunto']);
    $mensagem = addslashes($_POST['msg']);
    if(isset($mensagem) && $mensagem != '' && isset($nome) && isset($email) && isset($assunto)){
        //mail("contato@simuladoenemufv.com.br",$assunto,$mensagem,"From: ".$email);
    } else {
        $showit = true;
    }
    

?>
<html>
    <head>
        <link href="css/reset.css" rel="stylesheet" type="text/css">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <title>Fale Conosco</title>
        <meta charset="UTF-8">
        <link rel="sortcut icon" href="img/favicon/speech-bubble.png" type="image/png" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            @import url('https://fonts.googleapis.com/css?family=Francois+One');
            body{
                background-color: #34495e;
            }
            .contato{
                margin-top: 10px;
                width: 90%;
                margin-left: auto;
                margin-right: auto;
                padding: 20px 20px 20px 50px;
                background-color: rgba(245,255,250,1);
                box-shadow: 3px 3px 3px rgba(0,0,0,0.7);
                margin-bottom: 100px;
            }
            legend{
                font-family: 'Francois One', sans-serif;
                font-size: 50px;
            }
            @media (max-width: 480px) {
                .contato{
                width: 95%;
                margin-left: auto;
                margin-right: auto;
                padding: auto auto auto auto;
                background-color: rgba(245,255,250,1);
                box-shadow: 3px 3px 3px rgba(0,0,0,0.7);
                margin-bottom: 10px;
                }
                legend{
                    font-family: 'Francois One', sans-serif;
                    font-size: 30px;
                }
                label{
                    font-family: Arial, sans-serif;
                    font-size: 25px;
                }
            }
            div.sessao1{
                opacity: 1;
                background-color: rgba(231, 76, 60,.9);
                border-radius: 15px;
                padding: 10px;
            }
            div.sessao2{
                opacity: 1;
                background-color: rgba(46, 204, 113,.9);
                border-radius: 15px;
                padding: 10px;
            }
            #dialogBox{
                transition: 0.5s opacity;
            }
            div.sessao2>p{
                color: #ecf0f1;
                font-size: 20px;
            }
            div.sessao1>p{
                color: #ecf0f1;
                font-size: 20px;
            }
        </style>
        <script>
            function closeAlert(){
                var elem = document.getElementById("dialogBox");
                elem.style.opacity = "0";
                setTimeout(function() {elem.style.display = "none";},500);
            }
        </script>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <?php include_once("add/analyticstracking.php") ?>
    </head>
    <body>
        <?php
            if($formulario == 1){
                if($showit === false){
                    echo "<div id='dialogBox' class='sessao2' style='position: fixed; right: 5px; bottom: 10px; width: 30%;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>Sua mensagem foi enviada com sucesso! Responderemos o mais rápido possível</p></div>";
                    echo "<script>setTimeout(closeAlert,8000);</script>";
                } else {
                    echo "<div id='dialogBox' class='sessao1' style='position: fixed; right: 2px; bottom: 10px; width: 30%;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>Sua mensagem não foi enviada! Parece que você não preencheu todos os campos corretamente. Tente novamente.</p></div>";
                    echo "<script>setTimeout(closeAlert,10000);</script>";
                }
            }
        ?>
        <form class="form-horizontal contato" method="POST" action="">
            <form class="form-horizontal">
                <fieldset>

                <!-- Form Name -->
                <legend>Fale Conosco</legend>

                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="nome">Nome:</label>  
                <div class="col-md-5">
                <input id="nome" name="nome" type="text" placeholder="" class="form-control input-md" required="">
                    
                </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="email">E-mail:</label>  
                <div class="col-md-5">
                <input id="email" name="email" type="email" placeholder="" class="form-control input-md" required="">
                    
                </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                <label class="col-md-4 control-label" for="assunto">Assunto:</label>  
                <div class="col-md-4">
                <input id="assunto" name="assunto" type="text" placeholder="" class="form-control input-md" required="">
                    
                </div>
                </div>

                <!-- Textarea -->
                <div class="form-group">
                <label class="col-md-4 control-label" for="msg">Mensagem:</label>
                <div class="col-md-4">                     
                    <textarea class="form-control" id="msg" name="msg" placeholder="Escreva sua mensagem aqui."></textarea>
                </div>
                </div>
                <input type="hidden" value="1" name="formulario"/>
                <p style="text-align: center"><button type="submit" class="btn btn-lg btn-info"><span class="glyphicon glyphicon-envelope"></span> Enviar</button></p>
                </fieldset>
            </form>
            <p style="text-align: center"><a href="" class="btn btn-lg btn-warning"><span class="glyphicon glyphicon-home"></span> Página Inicial</a></p>
    </body>
</html>