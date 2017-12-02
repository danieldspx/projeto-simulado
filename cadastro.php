<?php
    require_once 'add/HeaderSession.php';
    if (isset($_SESSION['usuario'])) {
        session_start();
        $_SESSION['loginErro'] = "Você não pode se cadastrar logado.";
        header("Location: principal");
    }
    $plano = $_GET['plano'];
?>
<html>
    <head>
        <link href="css/reset.css" rel="stylesheet" type="text/css">
        <link href="css/font-awesome.min.css" rel="stylesheet">
        <link href="css/footer.css" rel="stylesheet" type="text/css">
        <title>Cadastro</title>
        <meta charset="UTF-8">
        <link rel="sortcut icon" href="img/favicon/forms.png" type="image/png" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="js/jquery-1.11.1.min.js"></script>
        <script src="js/cadastro.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            @import url('https://fonts.googleapis.com/css?family=Francois+One');
            body{
                background: url("img/pattern_filter.svg");
            }
            a.topo{
                text-decoration: none;
                cursor: default;
                color: black;
                font-weight: 700;
                font-family: arial, sans-serif
            }
            a.link{
                text-decoration: none;
                color: #3498db;
            }
            table.recaptcha{
                position: relative;
                width: 100%;
                margin: 20px 0px;
            }
            .cadastro{
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
            #confEmail{
                position: relative;
                width = 100px;
                margin-left:50%;
                left: -100px;
                font-family: 'Francois One', sans-serif;
                font-size: 20px;
                display: none;
            }
            #email-group{
                color: none;
            }
            #confSenha{
                position: relative;
                width = 100px;
                margin-left:50%;
                left: -100px;
                font-family: 'Francois One', sans-serif;
                font-size: 20px;
                display: none;
            }
            #senha-group{
                color: none;
            }
            label{
                font-family: Arial, sans-serif;
                font-size: 25px;
            }
            span.ano{
                font-family: Arial, sans-serif;
                font-size: 20px;
                font-weight: 600;
                padding: 10px 15px 10px 4px;
            }
            span.area,span.disciplina{
                font-family: Arial, sans-serif;
                font-size: 20px;
                font-weight: 600;
                padding: 10px 15px 10px 4px;
            }
            .navbar-brand{
                font-size: 30px;
            }
            @media (max-width: 480px) {
                .navbar-brand{
                    font-size: 20px;
                }
                .cadastro{
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
                span.ano{
                    font-family: Arial, sans-serif;
                    font-size: 15px;
                    font-weight: 600;
                    padding: 10px 15px 10px 4px;
                }
                span.area,span.disciplina{
                    font-family: Arial, sans-serif;
                    font-size: 15px;
                    font-weight: 600;
                    padding: auto auto auto auto;
                }
            }
            
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
        <?php include_once("add/analyticstracking.php") ?>
    </head>
    <body style="background-color: #fafafa">
        <form class="form-horizontal cadastro" method="POST" id="cadastroForm" name="cadastroForm" action="cadastro/confirmacao/">
            <fieldset>

            <!-- Form Name -->
            <legend>Formulário de Cadastro</legend>

            <!-- Text input-->
            <div class="form-group">
            <label class="col-md-4 control-label" for="nome">Nome</label>  
            <div class="col-md-5">
            <input id="nome" name="nome" type="text" placeholder="Digite seu Nome" maxlength="10" class="form-control input-md" required="">
                
            </div>
            </div>

            <div class="form-group">
            <label class="col-md-4 control-label" for="sobrenome">Sobrenome</label>  
            <div class="col-md-5">
            <input id="sobrenome" name="sobrenome" type="text" placeholder="Digite seu Sobrenome" class="form-control input-md" required="">
                
            </div>
            </div>

            <!-- Text input-->
            <div class="form-group">
            <label class="col-md-4 control-label" for="matricula">Matrícula</label>  
            <div class="col-md-2">
            <input id="matricula" name="matricula" type="number" onblur="vMatricula()" onchange="vMatricula()" placeholder="Digite sua matrícula" class="form-control input-md" required="">
            <span class="help-block">Digite a matrícula do técnico.</span>  
            </div>
            </div>

            <div class="form-group">
            <label class="col-md-4 control-label" for="telefone">Celular</label>  
            <div class="col-md-3">
            <input id="telefone" name="telefone" type="text" onkeydown="MaskDown(this)" onkeyup="MaskUp(this,event,'(##) #####-####')" class="form-control input-md" placeholder="(DDD) 00000-0000">
            </div>
            </div>

            <!-- Appended Input-->
        <div id="email-group" class="form-group">
            <div class="form-group">
            <label class="col-md-4 control-label" for="email">E-mail</label>
            <div class="col-md-4">
                <div class="input-group">
                <input id="email" name="email" class="form-control" onblur="vEmail(this),verifEqual(this,'emailConfirm','confEmail','email-group');" placeholder="Não digite o @ufv.br" type="text" required="">
                <span class="input-group-addon">@ufv.br</span>
                </div>
                <p class="help-block">Exemplo: Se o seu e-mail é "gremio@ufv.br", digite apenas "gremio". Encontre o seu e-mail <a href="http://www.simuladoenemufv.com.br/ajuda" class="link" target="_BLANK">aqui</a></p>
            </div>
            </div>
            <!-- Appended Input-->
            <div class="form-group">
            <label class="col-md-4 control-label" for="emailConfirm">Confirmação de e-mail</label>
            <div class="col-md-4">
                <div class="input-group">
                <input id="emailConfirm" name="emailConfirm" class="form-control" onblur="vEmail(this),verifEqual(this,'email','confEmail','email-group');" placeholder="Não digite o @ufv.br" type="text" required="">
                <span class="input-group-addon">@ufv.br</span>
                </div>
            </div>
            <div id="confEmail" class="col-md-4">
                <span>E-mail's não conferem</span>
            </div>
            </div>
        </div>
            <!-- Select Basic -->
            <div class="form-group" class="form-group">
            <label id="lblInstituicao" class="col-md-4 control-label" for="instituicao">Instituição de Ensino</label>
            <div class="col-md-8">
                <select id="instituicao" name="instituicao" class="form-control" onchange="vSelect()" required="">
                <option value="">Escola que você estuda</option>
                <option value="1">Central de Ensino e Desenvolvimento Agrário de Florestal</option>
                <option value="2">Escola Estadual Serafim Ribeiro de Rezende</option>
                </select>
            </div>
            </div>

            <!-- Select Basic -->
            <div class="form-group">
            <label id="lblAno" class="col-md-4 control-label" for="ano">Ano</label>
            <div class="col-md-6">
                <select id="ano" name="ano" class="form-control" onchange="vSelect()" required="">
                <option value="">Escolha o seu ano</option>
                <option value="1">1º Ano do Ensino Médio</option>
                <option value="2">2º Ano do Ensino Médio</option>
                <option value="3">3º Ano do Ensino Médio</option>
                </select>
            </div>
            </div>

        <div id="senha-group" class="form-group">
            <!-- Password input-->
            <div class="form-group">
            <label class="col-md-4 control-label" for="senha">Senha</label>
            <div class="col-md-5">
                <input id="senha" name="senha" type="password" onkeyup="verifEqual(this,'senhaConfirm','confSenha','senha-group')" placeholder="Digite sua senha" class="form-control input-md" required="">
            </div>
            </div>

            <!-- Password input-->
            <div class="form-group">
            <label class="col-md-4 control-label" for="senhaConfirm">Confirme a senha</label>
            <div class="col-md-5">
                <input id="senhaConfirm" name="senhaConfirm" type="password" onkeyup="verifEqual(this,'senha','confSenha','senha-group')" placeholder="Digite a senha novamente" class="form-control input-md" required="">
                
            </div>
            <div id="confSenha" class="col-md-4">
                <span>Senhas não conferem</span>
            </div>
            </div>
        </div>

        <!-- Multiple Checkboxes -->
        <div class="form-group">
        <label class="col-md-4 control-label" for="termos">Termos e condições de uso</label>
        <div class="col-md-6">
        <div class="checkbox">
            <label for="termos-0">
            <input type="checkbox" name="termos" id="termos-0" value="1" required="">
            Li e aceito os <a href="termos" target="_BLANK">termos e condições de uso e a política de privacidade</a>.
            </label>
            </div>
        </div>
        </div>

        <div class="form-group">
            <label class="col-md-4 control-label" for="r-group-metodo">Forma de pagamento</label>
            <div class="col-md-6">
                <div style="display:inline-block; margin-right: 20px;">
                    <input id='radio-1' type="radio" name='r-group-metodo' value="1" />
                    <label for="radio-1" style="font-weight: 500;cursor:pointer;">Cartão</label>
                </div>
                <div style="display:inline-block;">
                    <input id='radio-2' type="radio" name='r-group-metodo' value="2" checked='checked' />
                    <label for="radio-2" style="font-weight: 500; cursor:pointer;">Dinheiro(Passar para o GEDAM)</label>
                </div>
            </div>
        </div>

            <!-- Multiple Radios -->
            <div class="form-group">
            <label class="col-md-4 control-label" for="pagamento">Plano</label>
            <div class="col-md-5">
            <div class="radio">
                <label for="pagamento-0">
                <input type="radio" name="pagamento" id="pagamento-0" value="1" <?php if(($plano != 2 && $plano != 3 && $plano != 4) || $plano == 1){ echo "checked=\"checked\""; }?>>
                Acesso grátis por 3 dias
                </label>
                </div>
            <div class="radio">
                <label for="pagamento-1">
                <input type="radio" name="pagamento" id="pagamento-1" value="2" <?php if($plano ==  2){echo "checked=\"checked\"";}?>>
                R$ 5,00/mês - Simulado mensal
                </label>
                </div>
            <div class="radio">
                <label for="pagamento-2">
                <input type="radio" name="pagamento" id="pagamento-2" value="3" <?php if($plano ==  3){echo "checked=\"checked\"";}?>>
                R$ 20,00/ano - Simulado anual
                </label>
                </div>
            <div class="radio">
                <label for="pagamento-3">
                <input type="radio" name="pagamento" id="pagamento-3" value="4" <?php if($plano ==  4){echo "checked=\"checked\"";}?>>
                R$ 30,00/ano - Simulado anual + Associação ao GEDAM
                </label>
                </div>
            </div>
            </div>
            <table class="recaptcha"><tr><td align="center"><div style="display: inline-block;" class="g-recaptcha" data-sitekey="6LeVshcUAAAAABq_Makq8D5mhgVXGp0xxYqkPID3"></div></td></tr></table>
            <!-- Button -->
            <div class="form-group">
            <label class="col-md-4 control-label"></label>
            <div class="col-md-4">
                <button onclick="vSelect()" id="btnCadastro" class="btn btn-info">Cadastrar</button>
                <span style="margin-left: 50px" onclick="window.location.replace('http://www.simuladoenemufv.com.br')" class="btn btn-warning">Voltar</span>
            </div>
            </div>
            </fieldset>
        </form>



        <!--FOOTER INICIA AQUI-->
        <footer>
            <div class="container">
                <div class="row">
                <div class="col-md-4 col-sm-6 footerleft ">
                    <div class="logofooter"> Créditos de Imagem</div>
                    <div>Icons made by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/vectors-market" title="Vectors Market">Vectors Market</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/popcorns-arts" title="Popcorns Arts">Popcorns Arts</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>Icons made by <a href="http://www.flaticon.com/authors/flat-icons" title="Flat Icons">Flat Icons</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a><br>
                    are licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>                
                </div>
                <div class="col-md-2 col-sm-6 paddingtop-bottom">
                    <h6 class="heading7">LINKS ÚTEIS</h6>
                    <ul class="footer-ul">
                        <?php include "footerLinks.add"; ?>
                    </ul>
                </div>
                <div class="col-md-3 col-sm-6 paddingtop-bottom">
                    <div class="fb-page" data-href="https://www.facebook.com/facebook" data-tabs="timeline" data-height="300" data-small-header="false" style="margin-bottom:15px;" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                    <div class="fb-xfbml-parse-ignore">
                        <?php include "footerSites.add"; ?>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            </footer>
            <!--footer start from here-->

            <div class="copyright">
            <div class="container">
                <div class="col-md-6">
                <p>© 2017</p>
                </div>
                <!--div class="col-md-6">
                <ul class="bottom_ul">
                    <li><a href="#">webenlance.com</a></li>
                    <li><a href="#">About us</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Faq's</a></li>
                    <li><a href="#">Contact us</a></li>
                    <li><a href="#">Site Map</a></li>
                </ul>
                </div-->
            </div>
            </div>
        <script src="js/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>
        <script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script>
        <script src="js/vendor/bootstrap.min.js"></script>
    </body>
</html>