<?php
    session_name(md5("security".$_SERVER['REMODE_ADDR']."2c70c2374151e7850f8b6b6828ef4962"));
	session_start();
    $_SESSION = array();
    session_destroy();
?>
<html>
    <head>
        <meta name="robots" content="noindex">
        <title>Oopss</title>
        <link href="https://fonts.googleapis.com/css?family=Francois+One" rel="stylesheet">
        <style>
            body{
                background-color: rgba(0, 153, 255,0.6);
            }
            div{
                margin: 0 auto 0 auto;
                background-color: rgba(255,106,106,0.7);
                padding: 20px;
                border-radius: 17px;
                font-size: 25px;
                text-align: left;
                font-family: 'Francois One', sans-serif;
            }
            a{
                color: black;
                text-decoration: none;
                transition: color 1s;
            }
            a:hover{
                color: rgba(0,191,255,0.7);
            }

        </style>
    </head>
    <body>
        <div>Alguém fez login utilizando sua conta, modifique sua senha imediatamente. Caso necessario contate o Administrador do Site. Vá para a página inicial clicando <a href="index.php" title="Pagina Inicial!">aqui.</a></div>
        <img src="img/database.svg" width="20%"/>
        <img src="img/tv-screen.svg" width="40%"/>
        <img src="img/ninja.svg" title="Malditos movimentos, mal posso ver seus Ninjas!" width="20%"/>
            
    </body>   
</html>