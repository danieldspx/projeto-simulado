<?php
    require_once 'add/HeaderSession.php';
    $logout = $_GET['slogout'];
    if($logout == '1'){
        $_SESSION = array();
        session_destroy();
    }
    if (empty($_SESSION['usuario'])) {
        session_start();
        $_SESSION['logout'] = "Logout efetuado com sucesso.";
        header("Location: principal");
    }
    include_once 'private_html_protected/config.php';
    include_once 'private_html_protected/connection.php';
    include_once 'private_html_protected/database.php';
    $resposta = DBSearch("usuarios","WHERE id = ".$_SESSION['usuario']['id'],"id_sessao");
    if($resposta[0]["id_sessao"]!=$_SESSION['usuario']['sessao']){
        header("Location: LoginSimultaneo.php");
    }
    unset($resposta);
?>
<html>
    <head>
        <title>Simulado</title>
        <link rel="sortcut icon" id="favicon" href="img/favicon/checked.png" type="image/png"/>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <link rel="stylesheet" href="css/simuladoDone.css">
        <?php include_once("add/analyticstracking.php") ?>
    </head>
    <body>

        <svg class="labSVG" xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 600 600"   >
        <defs>
        <linearGradient id="frontGrad" gradientUnits="userSpaceOnUse" x1="300" y1="100" x2="300" y2="600">
            <stop  offset="0.5" style="stop-color:#005DE9"/>
            <stop  offset="0.8" style="stop-color:#ED1E79"/>
        </linearGradient>
          <mask id="liquidMask">
        <path class="liquidMask" fill="#FFFFFF" d="M337,273.9V129h-74v144.8c-37,14.7-63.1,50.8-63.1,93c0,55.2,44.8,100,100,100
                s100-44.8,100-100C400,324.7,373.9,288.6,337,273.9z"/>
          </mask>
          <clipPath id="sphereMask">
          <circle fill="red" stroke="none" stroke-width="0.4957" stroke-miterlimit="10" cx="300" cy="367" r="100"/>
          </clipPath>
          <filter id="goo" color-interpolation-filters="sRGB">
              <feGaussianBlur in="SourceGraphic" stdDeviation="7 7" result="blur" />
              <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -7" result="cm" />

                <feComposite in="SourceGraphic" in2="cm" />
            </filter>
        </defs>
        <g class="liquidMaskGroup" clip-path="url(#sphereMask)">
          <path class="liquidBack" fill="none" d="M1199.9,365.1c-41.8,0-79.4,9.8-107.4,8.1c-38.9-2.3-54.5-16.4-83.6-19.9
            c-29.1-3.5-71.5,3.4-110.4,1c-28-1.7-56.4-13.7-98.2-13.7c-41.8,0-70.2,12-98.2,13.7c-38.9,2.3-81.4-4.6-110.4-1
            c-29.1,3.5-44.7,17.5-83.6,19.9c-28,1.7-65.7-8.2-107.5-8.2c-41.8,0-79.5,9.9-107.5,8.2c-38.9-2.3-54.5-16.3-83.6-19.9
            c-29.1-3.5-72,3.4-110.9,1c-28-1.7-56.7-13.7-98.7-13.7V438h1200L1199.9,365.1z"/>
          <g class="liquidBubblesGroup" fill="url(#frontGrad)" clip-path="url(#sphereMask)">
            <path class="liquidFront" fill="url(#frontGrad)"  d="M1199.9,329.6c-44,0-70.6,29.4-96.4,33c-36.1,5.1-70.7-14.5-106.8-9.4    c-25.8,3.7-52.4,33.3-96.4,33.3c-44,0-70.7-29.7-96.4-33.4c-36.1-5.1-70.7,14.4-106.8,9.2c-25.8-3.7-52.4-33.3-96.5-33.3    c-44,0-70.7,29.7-96.5,33.3c-36.1,5.1-70.7-14.4-106.8-9.3c-25.8,3.7-52.4,33.3-96.5,33.3c-44,0-70.7-29.7-96.5-33.3    c-36.1-5.1-71.2,14.4-107.3,9.3c-25.8-3.7-52-33.3-97-33.3V533h1200L1199.9,329.6z"/>
              <circle class="bubble0" cx="350" cy="400" r="8"/>
              <circle class="bubble1" cx="320" cy="400" r="6"/>
              <circle class="bubble2" cx="300" cy="400" r="12"/>
              <circle class="bubble3" cx="276" cy="400" r="3"/>
              <circle class="bubble4" cx="244" cy="400" r="4"/>
              <circle class="bubblePop0" cx="280" cy="400" r="5"/>
              <circle class="bubblePop1" cx="310" cy="390" r="5"/>
              <circle class="bubblePop2" cx="350" cy="410" r="5"/>
            </g>
          <g class="darkBubbleGroup" fill="none" stroke="none">
            <circle class="darkBubble" cx="310" cy="480" r="7"/>
            <circle class="darkBubble" cx="360" cy="480" r="5"/>
            <circle class="darkBubble" cx="230" cy="480" r="6"/>
            <circle class="darkBubble" cx="345" cy="480" r="3"/>
            <circle class="darkBubble" cx="290" cy="480" r="8"/>
            <circle class="darkBubble" cx="320" cy="480" r="2"/>
            <circle class="darkBubble" cx="260" cy="480" r="9"/>
          </g>
          <path class="pop" fill="none" stroke="none" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="
            M37.4,9c2.1-2.1,4.2-4.2,6.3-6.3 M2,44.4c2.2-2.2,4.5-4.5,6.7-6.7 M37.4,37.4c2.1,2.1,4.2,4.2,6.3,6.3 M2,2c2.2,2.2,4.5,4.5,6.7,6.7
            "/>
          </g>

                <!--path class="flask" opacity="1" fill="none" stroke="none" stroke-width="10" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" d="        M379.2,108.2l-20.5,20.5l0.1,126.3h0.2c39.7,21,66.7,62.9,66.7,111c0,69.4-56.3,125.7-125.7,125.7S174.3,435.4,174.3,366
                c0-48.1,27.1-89.9,66.8-111l0.1-126.3l-20.5-20.5"/-->
        <radialGradient id="shine" cx="280" cy="337" r="100" gradientUnits="userSpaceOnUse">

            <stop offset="0.02"  style="stop-color:#fff;stop-opacity:0.2"/>

            <stop  offset="1" style="stop-color:#1B52D4;stop-opacity:0.1"/>
        </radialGradient>

          <circle opacity="0.9" fill="url(#shine)" stroke="none" stroke-width="0.4957" stroke-miterlimit="10" cx="300" cy="367" r="100"/>

        </svg>

        <h1>Simulado <?php
                        require_once 'private_html_protected/config.php';
                        require_once 'private_html_protected/connection.php'; //Chamando Arquivos Necessarios para Conexão
                        require_once 'private_html_protected/database.php';
                        
                        $pesquisaProva = DBSearch("dados_usuario_simulado","WHERE usuarios_id = ".$_SESSION['usuario']['id'],"prova");
                        echo $pesquisaProva[0]['prova'];
                    ?>/2 finalizado.</h1>
        <button onclick="window.location.href='principal'" style="text-align: center;">
            Pagina Inicial
        </button>
        <br><br>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/gsap/1.16.1/TweenMax.min.js'></script>
        <script src='js/simuladoDone.js'></script>
    </body>
</html>
