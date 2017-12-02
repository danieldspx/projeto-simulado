function clockAssync() {//Atualiza o relógio
    var page = "../clockassync.php";
    $.ajax
            ({
                type: 'POST',
                dataType: 'html',
                url: page,
                data: {},
                success: function (msg)
                {
                    $("#variaveisJS").html(msg);
                }
                });
}

function settar(){//Salvar a questão marcada
    var page = "../salvar_qsimulado.php";
    document.getElementById("Saved").style.display = "inline-block";
    $.ajax
            ({
                type: 'POST',
                dataType: 'html',
                url: page,
                data: {},
                beforeSend: function () {
                    $("#Saved").html("<img src=\"../img/hourglass.svg\" alt=\"Carregando...\"/>");
                    $('#Saved').removeClass('animated zoomIn');
                    $('#Saved').removeClass('animated zoomOut');
                },
                success: function (msg)
                {
                    $("#Saved").html(msg);
                    $('#Saved').addClass('animated zoomIn');
                    setTimeout(function(){$('#Saved').removeClass('animated zoomIn'); $('#Saved').addClass('animated zoomOut');},5000);
                }
            });
}
function mobile_change (){ //Mostra o Botão Finalizar no menu Mobile
    var elemento = document.getElementById("btnStart");
    elemento.setAttribute("onclick","verifyQuestions()");
    elemento.innerHTML="<i class=\"fa fa-graduation-cap\" aria-hidden=\"true\"></i> Finalizar Simulado!";
    elemento.setAttribute("id","endSimulado");
    document.getElementById("endSimulado").style.display="inline-block";
    
}
function finalizaSimulado(){//Finalizar simulado
    var page = "../salvar_qsimulado.php";
    var finalizar = 1;
    $.ajax
        ({
            type: 'POST',
            dataType: 'html',
            url: page,
            data: {finalizar:finalizar},
            success: function (msg)
            {
                closeBox();
                $("#savingEnd").html("<div class=\"alert alert-dismissible alert-info\" role=\"alert\"><strong>Finalizando o simulado em 10 segundos... Suas questões serão salvas, não se preocupe.</strong></div>");
                setTimeout(function(){location.href="http://www.simuladoenemufv.com.br/simuladoDone.php";},10000);
            }
        });
}

window.addEventListener('visibilitychange', clockAssync, false);
function contagem_tempo(){//Altera o tempo,como um cronometro
    document.getElementById("horas").innerHTML = "0".concat(horas);
    document.getElementById("horasBox").innerHTML = "0".concat(horas);
    if(minutos < 10){
        document.getElementById("minutos").innerHTML = "0".concat(minutos);
        document.getElementById("minutosBox").innerHTML = "0".concat(minutos);
    } else {
        document.getElementById("minutos").innerHTML = minutos;
        document.getElementById("minutosBox").innerHTML = minutos;
    }
    if(segundos < 10){
        document.getElementById("segundos").innerHTML = "0".concat(segundos);
        document.getElementById("segundosBox").innerHTML = "0".concat(segundos);
    } else {
        document.getElementById("segundos").innerHTML = segundos;
        document.getElementById("segundosBox").innerHTML = segundos;
    }
    segundos--;
    if(segundos== -1){
        if(horas == 0 && minutos == 0){
            segundos =0;
        } else {
            segundos = 59;
            minutos--;
        }
    }
    if (minutos == -1) {
        minutos = 59;
        horas--;
    }
    if(horas == -1){
        horas = 0;
    }
    if((horas+minutos+segundos) != 0){
        setTimeout("contagem_tempo()",1000);
    }
    if(horas+minutos+segundos == 0){
        setTimeout(function(){document.getElementById("segundos").innerHTML = "0".concat(segundos);},1000);
        finalizaSimulado();
    }
}

function setTime(){//Salva a data de inicio do simulado
    var page = "../salvar_data.php";
    $.ajax
            ({
                type: 'POST',
                dataType: 'html',
                url: page,
                data: {},
            });
}
function disappear(){//Mostra as opções disponiveis, Finalizar ou Iniciar simulado
    document.getElementById("bodyTag").style.overflowX = "visible";
    document.getElementById("btnStart").style.display = "none";
    document.getElementById("simuladoOpcoes").innerHTML = "<a id=\"endSimulado\" onclick=\"verifyQuestions()\"><i class=\"fa fa-graduation-cap\" aria-hidden=\"true\"></i> Finalizar Simulado!</a>";
}
function changeVisibility(){//Muda a visibilidade do campo de questões
    var elemento1 = document.getElementById("campoQuestoes");
    elemento1.style.visibility = "hidden";
    var elemento2 = document.getElementById("fieldT");
    elemento2.style.visibility = "visible";
}
function generalBox(condition){
    var gb = document.getElementById('generalBox');
    if(condition == 1){
        setTimeout(function(){gb.style.opacity = '1';},500);
    } else {
        gb.style.opacity = '0';
    }
}
function endSimulado(continuar,questoes){//Mostra a opção de finalizar simulado e quais questões foram resolvidas
    document.getElementById("finalSimulado").style.visibility="visible";
    document.getElementById("finalSimulado").className = "container-fluid animated bounceInDown";
    document.getElementById("navbar").style.display="none";
    document.getElementById("campoQuestoes").style.display="none";
    document.getElementById("fieldT").style.display="none";
    if(continuar!=1){
        document.getElementById("textoSimuladoBox").innerHTML = "<p>Parece que você não resolveu:<br>"+questoes+"<br>Deseja finalizar mesmo assim?</p>";
    } else {
        document.getElementById("textoSimuladoBox").innerHTML = "<p>Você resolveu todas as questões. Prossiga ao clicar em confirmar.</p>";
    }
}
function closeBox(){//Fecha o box para finalizar o simulado
    document.getElementById("finalSimulado").className = "container-fluid animated bounceOutDown";
    setTimeout(function(){
                            document.getElementById("finalSimulado").style.visibility="hidden";
                            document.getElementById("campoQuestoes").style.display="block";
                            document.getElementById("fieldT").style.display="block";
                            document.getElementById("navbar").style.display="block";
                        },700);
}
function verifyQuestions(){//Verifica quais questões foram resolvidas
    var page = "../verificaResolvidaSimulado.php";
    $.ajax
            ({
                type: 'POST',
                dataType: 'html',
                url: page,
                data: {},
                success: function (msg)
                {
                    console.log(msg);
                    if(msg=="done"){
                        endSimulado(1); //Todas as questões resolvidas
                    } else {
                        endSimulado(0,msg); //Há questões a serem mostradas
                    }
                }
            });
}
