function settar(){ //Salva questões realizadas no simulado
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

function check(letra,hash_t){ //Marca a alternativa clicada
    var identificacao = letra.substring(0,1);
    var hash2 = hash_t.substring(32);
    var hash1 = hash_t.substring(0,32);
    var nquestao = letra.substring(1);
    var classe = document.getElementById(letra).className;
        if(classe == "alternativa-checked"){
            document.getElementById(letra).className="alternativabutton";//Desmarca
            document.getElementById("button_effec".concat(nquestao)).style.display = "none";
        }
        else{
            console.log(letra);
            document.getElementById('A'.concat(nquestao)).className="alternativabutton";//
            document.getElementById('B'.concat(nquestao)).className="alternativabutton";//
            document.getElementById('C'.concat(nquestao)).className="alternativabutton";//Desmarca todas
            document.getElementById('D'.concat(nquestao)).className="alternativabutton";//
            document.getElementById('E'.concat(nquestao)).className="alternativabutton";//
            document.getElementById(letra).className="alternativa-checked";
            buttonVisibility(true,identificacao,hash2,hash1,nquestao);
        }
}

function buttonVisibility(status,letra,hash2,hash1,nquestao){ //Altera a visibilidade do botão de confirmar alternativa
    var elemB = document.getElementById("confim-button".concat(nquestao));
    if(status == true){
        elemB.innerHTML = "<button class='botao botao-responder-questao' id=\"button_effec".concat(nquestao,"\" onclick=\"try_it(\'".concat(nquestao,"','",hash1,hash2,"\')\"/>Confirmar Resposta (").concat(letra,")</button>"));
    } else {
        elemB.innerHTML = "<button class='botao botao-responder-questao' style='display: none;' id=\"button_effec".concat(nquestao,"\"/></button>");
    }
}

function forcedSelect(nquestao,letra){ //Não precisa descrever no caso de uso (TCC)
    var elemB = document.getElementById("confim-button".concat(nquestao));
    elemB.innerHTML = "<button id=\"button_effec".concat(nquestao,"\" class='botao msg-resposta btnright'/><img src='../img/check.png' height='15px'/>&nbsp;Resposta Selecionada!</button>");
    document.getElementById(letra.concat(nquestao)).className="alternativa-checked";
}

function try_it(nquestao,letra){ //Salva no banco de dados como questão selecionada
    var ambiente = letra.substring("690286896ed71a0c8b9a228be1a30412".length);
    letra = letra.substring(0,"690286896ed71a0c8b9a228be1a30412".length);
    var elemB = document.getElementById("confim-button".concat(nquestao));
    if(letra == ambiente){//Correto
        elemB.innerHTML = "<button id=\"button_effec".concat(nquestao,"\" class='botao msg-resposta btnright'/><img src='../img/check.png' height='15px'/>&nbsp;Resposta Selecionada!</button>");
    } else{//Errado
        elemB.innerHTML = "<button id=\"button_effec".concat(nquestao,"\" class='botao msg-resposta btnright'/><img src='../img/check.png' height='15px'/>&nbsp;Resposta Selecionada!</button>");
    }
    var page = "../sessao_questaosimulado.php";
    $.ajax
            ({
                type: 'POST',
                dataType: 'html',
                url: page,
                data: {letra: letra, nquestao: nquestao},
                success: function (msg)
                {
                    $("#SL").html(msg);
                    settar();
                }
            });
}

function muda_pagina(caso){ //Muda a página

    var valor = document.getElementById("numero_pagina").value;
    if(caso == 1){
        valor--;
    } else if(caso==2) {
        valor++;
    }
    document.getElementById("numero_pagina").value = valor;
    window.location.replace("?pagina=".concat(valor));
    /*if(valor<10){//Menor que 10 pois temos apenas 90 questões
        document.getElementById("numero_pagina").value = valor;
        window.location.replace("?pagina=".concat(valor));
    }*/
}
