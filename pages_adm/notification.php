<?php
    require_once '../add/HeaderSession.php';
    require_once '../private_html_protected/config.php';
    require_once '../private_html_protected/connection.php';
    require_once '../private_html_protected/database.php';
    if($_SESSION['usuario']['nivelAcessoId'] != 1 && $_SESSION['usuario']['nivelAcessoId'] != 2){
        $_SESSION=array();
        session_destroy();
        header("Location: ../errorPagina");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Push Notification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/font-awesome.css" rel="stylesheet">
    <script src="../js/jquery-1.11.1.min.js"></script>
    <script src="../js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    <link href="../css/icon.css" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="../css/materialize.min.css" media="screen,projection" />
    <style>
        @font-face {
            font-family: 'Montserrat';
            font-style: normal;
            font-weight: normal;
            src: url('../fonts/Montserrat-Regular.ttf');
        }

        @font-face {
            font-family: 'Material Icons';
            font-style: normal;
            font-weight: normal;
            src: url('../fonts/MaterialIcons-Regular.ttf');
        }

        body {
            background: -webkit-radial-gradient(circle, #ffffff, #f7f7f7, #ecf0f1);
            background: -o-radial-gradient(circle, #ffffff, #f7f7f7, #ecf0f1);
            background: -moz-radial-gradient(circle, #ffffff, #f7f7f7, #ecf0f1);
            background: radial-gradient(circle, #ffffff, #f7f7f7, #ecf0f1);
        }

        #btnSubmit {
            font-family: 'Helvetica', sans-serif;
            height: 150px;
            font-weight: 600;
            font-size: 20px;
        }

        .center {
            text-align: center;
        }
        
        button:focus{
            outline: 0 !important;
        }

    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="row" id="3main">
                <div class="input-field col s12">
                    <textarea id="textNotification" class="materialize-textarea"></textarea>
                    <label for="textarea1">Notificação</label>
                </div>
                <div class="center">
                    <button class="btn waves-effect waves-light green accent-3" id="btnSubmit" type="submit" name="action"><span style="">Push Notificação</span><i style="display: inline-block;margin-left: 10px;font-size: 30px;vertical-align: bottom;" class="material-icons">send</i></button>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/jquery.js"></script>
    <script>
        window.jQuery || document.write('<script src="../js/vendor/jquery-1.11.2.js"><\/script>')
    </script>
    <script type="text/javascript" src="../js/materialize.min.js"></script>
    <script type="text/javascript">
        function pushNotification(){
            if($('#textNotification').val()!=""){
                var mensagem = $('#textNotification').val();
                var page = "pushNotification.php";
                $.ajax({
                    type: 'POST',
                    dataType: 'html',
                    url: page,
                    data: {mensagem: mensagem},
                    success: function(msg){
                        conn.send( "1"+msg+ $('#textNotification').val()); //Envia 1 no começo para identificar que é Notificacao
                        $('#textNotification').val("");
                    } 
                });
            } else{
                console.log("Ficou");
            }
        }
        
        var conn = new WebSocket('ws://localhost:8080');
        
        $("#btnSubmit").click(function(){
            pushNotification();
        });
    </script>
</body>

</html>
