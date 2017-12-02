function settar(situacao, nquestao, area) { //Salva resposta no banco de dados (Serve para mostrar o desempenho)
    var page = "adicionar_questao.php";
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: page,
        data: {
            situacao: situacao,
            nquestao: nquestao,
            area: area
        },
        success: function (msg) {
            $("#SL").html(msg);
        }
    });
}

function callMathMl(nquestao) {
    var page = "MathMLcomment.php";
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: page,
        data: {
            nquestao: nquestao
        },
        success: function (msg) {
            $("#MathField" + nquestao).html(msg);
        }
    });
}

function showMathML(nquestao) { //Abre o campo para resolução de matematica
    var elem = document.getElementById('MathField'.concat(nquestao));
    elem.innerHTML = "<iframe style=\"width: 100%; height: 340px;\" frameborder=\"0\" scrolling=\"no\" onshow=\"resizeIframe(this)\" src=\"http://www.simuladoenemufv.com.br/MathMLcomment.php?nquestao=" + nquestao + "\"></iframe>";
}

function updateNotificacao() {
    if ($('#iconNotif').html() == "notifications_active") {
        $('#iconNotif').html("notifications");
    }
    $('#iconNotif').removeClass('shakeIt');

    $('#badge').css("opacity", "0");

    setTimeout(function () {
        $('#badge').html("");
        $('#badge').css("opacity", "1");
        $('#badge').addClass('dnone');
    }, 500);

    //Salvar que a notificação foi lida
    var identificacoes = new Array();
    var i = 0;
    document.querySelectorAll(".notificacao").forEach(function (elem) {
        identificacoes[i] = elem.id;
        i++;
    });
    var implode = identificacoes.join("-"); //Junta todos os Id's lidos

    var page = "db_notificacao.php";
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: page,
        data: {
            tipo: 0,
            id_notf: implode
        }
    });
}

function addResolucao(nquestao) { //Adiciona resolução do usuário
    var page = "adicionar_resolucao.php";
    var id = "#txtResolucao".concat(nquestao);
    var idSpan = "#digite_comentario".concat(nquestao);
    var texto;
    $(function () {
        texto = $(id).val();
    });
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: page,
        beforeSend: function () {
            $(idSpan).html("<i class=\"fa fa-refresh fa-spin fa-3x fa-fw\"></i><span class=\"sr-only\">Loading...</span>");
        },
        data: {
            texto: texto,
            nquestao: nquestao,
            excluir: "nao"
        },
        success: function (msg) {
            $("#section_usercmm" + nquestao).html("");
            var elemHTML = document.getElementById("comentariosGerais" + nquestao);
            var n = msg.search("&&&");
            var id_comm = parseInt(msg.substring(0, n));
            msg = msg.substring(n + 3);
            if (elemHTML.innerHTML.search("alert alert-danger") == -1) {
                elemHTML.innerHTML += msg;
            } else {
                elemHTML.innerHTML = msg;
            }
            conn.send("2" + id_comm + "-" + nquestao); //Propaga novo comentario
        }
    });
}

function removeResolucao(nquestao, idcomentario) { //Remove resolução do usuário
    var page = "adicionar_resolucao.php";
    var id = "#txtResolucao".concat(nquestao);
    var idPainel = "painelComentario".concat(nquestao, idcomentario);
    var texto;
    $(function () {
        texto = $(id).val();
    });
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: page,
        beforeSend: function () {
            document.getElementById("imagem".concat(nquestao, idcomentario)).innerHTML = "<i class=\"fa fa-spinner fa-pulse fa-1x fa-fw\"></i><span class=\"sr-only\">Loading...</span>";
        },
        data: {
            texto: texto,
            nquestao: nquestao,
            excluir: "sim",
            idcomentario: idcomentario
        },
        success: function (msg) {
            var node = document.getElementById(idPainel);
            node.parentNode.removeChild(node);
            var parent = document.getElementById("comentariosGerais" + nquestao);
            if (parent.innerHTML == "") {
                parent.innerHTML = "<tr><div class=\"alert alert-danger\" style='margin-top: 20px;' role=\"alert\"><strong>Nenhum comentário encontrado, atualize a página e tente novamente.</strong></div></tr>"; //Aviso de que não tem comentários
            }
            document.getElementById("section_usercmm" + nquestao).innerHTML = msg;
            conn.send("3" + nquestao + "-" + idcomentario); //Propaga exclusão do comentario
        }
    });
}

function pontuar(nquestao, user_receber) { //Pontuar resolução de algum usuário
    var page = "pontuacao.php";
    var idP = "#pontos" + user_receber;
    $.ajax({
        type: 'POST',
        dataType: 'html',
        url: page,
        beforeSend: function () {
            $(idP).html("<i class=\"fa fa-circle-o-notch fa-spin fa-fw\"></i><span class=\"sr-only\">Loading...</span>");
        },
        data: {
            nquestao: nquestao,
            user_receber: user_receber
        },
        success: function (msg) {
            $(idP).html(msg);
        }
    });
}

function writeResolution(nquestao) { //Mostra textarea para escrever alguma solução
    var classeatual = document.getElementById("write".concat(nquestao));
    var digite = document.getElementById("digite_comentario".concat(nquestao));
    var math = document.getElementById("math".concat(nquestao));
    if (classeatual.className == "btn btn-primary btn-info") {
        digite.className = "";
        classeatual.className = "btn btn-primary btn-danger";
        math.style.display = "inline-block";
        classeatual.innerHTML = "Fechar resolução <span class=\"glyphicon glyphicon-remove\"></span>";
    } else {
        digite.className = "displaynone";
        classeatual.className = "btn btn-primary btn-info";
        classeatual.innerHTML = "Escrever resolução <span class=\"glyphicon glyphicon-comment\"></span>";
        document.getElementById("txtResolucao".concat(nquestao)).value = "";
        math.style.display = "none";
        document.getElementById("MathField".concat(nquestao)).innerHTML = "";
    }
}

function showcomentario(nquestao, aparecer) { //Mostra os comentarios/resoluções
    var classeatual = document.getElementById("comentariosGerais".concat(nquestao)).className;
    var comentario = document.getElementById("comentariosGerais".concat(nquestao));
    var write = document.getElementById("write".concat(nquestao));
    var divButtons = document.getElementById("buttonsResolution".concat(nquestao));
    var botao = document.getElementById("buttoncomentario".concat(nquestao));
    var math = document.getElementById("math".concat(nquestao));
    var digite = document.getElementById("digite_comentario".concat(nquestao));
    if (classeatual == "comentario displaynone" && aparecer != false) {
        if (comentario) {
            comentario.className = "comentario";
        }
        if (write) {
            write.className = "btn btn-primary btn-info";
            write.style.display = "inline-block";
        }
        botao.innerHTML = "Esconder Resoluções";
        if (divButtons) {
            divButtons.style.display = "block";
        }
    } else {
        if (divButtons) {
            divButtons.style.display = "none";
        }
        if (write) {
            write.className = "btn btn-primary btn-info displaynone";
            write.style.display = "none";
            write.innerHTML = "Escrever resolução <span class=\"glyphicon glyphicon-comment\"></span>";
        }
        if (comentario) {
            comentario.className = "comentario displaynone";
        }
        if (math) {
            math.style.display = "none";
        }
        if (digite) {
            digite.className = "displaynone";
        }
        botao.innerHTML = "Resoluções";
        if (document.getElementById("MathField".concat(nquestao))) {
            document.getElementById("MathField".concat(nquestao)).innerHTML = "";
        }

    }
}

function check(letra, hash_t) { //Seleciona alguma alternativa
    var identificacao = letra.substring(0, 1);
    var hash2 = hash_t.substring(32);
    var hash1 = hash_t.substring(0, 32);
    var nquestao = letra.substring(1);
    var classe = document.getElementById(identificacao.concat(nquestao)).className;
    var classeButton = document.getElementById("button_effec".concat(nquestao)).className;
    if (classeButton != "botao msg-resposta btnright") {
        if (classe == "alternativa-checked") {
            document.getElementById(letra).className = "alternativabutton"; //Desmarca
            document.getElementById("buttoncomentario".concat(nquestao)).className = "btnComentario displaynone";
            showcomentario(nquestao, false);
            buttonVisibility(false, "N", "h1", "h2", nquestao);
        } else {
            document.getElementById('A'.concat(nquestao)).className = "alternativabutton"; //
            document.getElementById('B'.concat(nquestao)).className = "alternativabutton"; //
            document.getElementById('C'.concat(nquestao)).className = "alternativabutton"; //Desmarca todas
            document.getElementById('D'.concat(nquestao)).className = "alternativabutton"; //
            document.getElementById('E'.concat(nquestao)).className = "alternativabutton"; //
            document.getElementById(letra).className = "alternativa-checked";
            buttonVisibility(true, identificacao, hash2, hash1, nquestao);
        }
    }
}

function buttonVisibility(status, letra, hash2, hash1, nquestao) { //Altera a visibilidade do botão de confirmação
    var elemB = document.getElementById("confim-button".concat(nquestao));
    if (status == true) {
        elemB.innerHTML = "<button class='botao botao-responder-questao' id=\"button_effec".concat(nquestao, "\" onclick=\"try_it(\'".concat(nquestao, "','", hash1, hash2, "\')\"/>Confirmar Resposta (").concat(letra, ")</button>"));
    } else {
        elemB.innerHTML = "<button class='botao botao-responder-questao' style='display: none;' id=\"button_effec".concat(nquestao, "\"/></button>");
    }
}

function try_it(nquestao, letra) { //Verificar se a questão está correta ou errada
    document.getElementById("buttoncomentario".concat(nquestao)).className = "btnComentario";
    var ambiente = letra.substring("690286896ed71a0c8b9a228be1a30412".length);
    letra = letra.substring(0, "690286896ed71a0c8b9a228be1a30412".length);
    var elemB = document.getElementById("confim-button".concat(nquestao));
    var crypt = ambiente.charAt(0) == "9" ? Math.pow(2, ambiente.charCodeAt(12) - 39) : Math.pow(2, ambiente.charCodeAt(12) - 19);
    switch (crypt) {
        case 536870912:
            crypt = "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed678fe57bcca610140957afab5710d61f837Ocad1d412f80b84d143e1257f623e75af30e62bbd73d6df5b50bb7b53a3ea0cfc35332cedf6e5e9a32e94da";
            break;
        case 576460752303423500:
            crypt = "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed678fe57bcca610140957afab5710d61f8370cad1d412f80b84d143e1257f623e75af30e62bbd73d6df5bS0bb7b53a3ea0cfc35332cedf6e5e9a32e94da";
            break;
        case 1073741824:
            crypt = "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed67&fe57bcca610140957afab5710d61f8370cad1d412f80b84d143e1257f623e75af30e62bbd73d6df5b50bb7b53a3ea0cfc35332cedf6e5e9a32e94da";
            break;
        case 34359738368:
            crypt = "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed678fe57bcca610140957afab5710d61f8370cadld412f80b84d143e1257f623e75af30e62bbd73d6df5b50bb7b53a3ea0cfc35332cedf6e5e9a32e94da";
            break;
        case 4294967296:
            crypt = "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed678fe57bcca610140957afab5710d61f8370cad1d412f80b84d143el257f623e75af30e62bbd73d6df5b50bb7b53a3ea0cfc35332cedf6e5e9a32e94da";
            break;
    }
    if (letra == ambiente) { //Correto

        elemB.innerHTML = "<button id=\"button_effec".concat(nquestao, "\" class='botao msg-resposta btnright'/><i class=\"fa fa-check\" aria-hidden=\"true\"></i> Resposta Correta!</button>");
    } else { //Errado
        elemB.innerHTML = "<button id=\"button_effec".concat(nquestao, "\" class='botao msg-resposta btnwrong'/><i class=\"fa fa-times\" aria-hidden=\"true\"></i> Resposta Incorreta!</button><br><br><button id='resposta' class=\"showGabarito\" onclick=\"show_gabarito('").concat(nquestao, "','", crypt, "')\"><i class='fa fa-eye' aria-hidden='true' ></i> Ver Gabarito</button><span style='opacity: 0;' id='gabarito'></span>");
    }
    var resolvida;
    $.get("busca_questao.php", function (data) {
        resolvida = data;
    });
    if (resolvida == null || resolvida.indexOf("-".concat(nquestao, "-")) == -1) { //N Encontrada
        if (letra == ambiente) {
            settar("certo", nquestao, $("#area").html());
        } else {
            settar("errado", nquestao, $("#area").html());
        }
    }

}

function show_gabarito(nquestao, valor) { //Mostra o gabarito
    var answer;
    if (valor == "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed678fe57bcca610140957afab5710d61f837Ocad1d412f80b84d143e1257f623e75af30e62bbd73d6df5b50bb7b53a3ea0cfc35332cedf6e5e9a32e94da") {
        answer = "A";
    } else if (valor == "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed678fe57bcca610140957afab5710d61f8370cad1d412f80b84d143e1257f623e75af30e62bbd73d6df5bS0bb7b53a3ea0cfc35332cedf6e5e9a32e94da") {
        answer = "B";
    } else if (valor == "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed67&fe57bcca610140957afab5710d61f8370cad1d412f80b84d143e1257f623e75af30e62bbd73d6df5b50bb7b53a3ea0cfc35332cedf6e5e9a32e94da") {
        answer = "C";
    } else if (valor == "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed678fe57bcca610140957afab5710d61f8370cadld412f80b84d143e1257f623e75af30e62bbd73d6df5b50bb7b53a3ea0cfc35332cedf6e5e9a32e94da") {
        answer = "D";
    } else if (valor == "7fc56270e7a70fa81a5935b72eacbe29fc1fd7de8a89b256e0a7fceb10a301729d5ed678fe57bcca610140957afab5710d61f8370cad1d412f80b84d143el257f623e75af30e62bbd73d6df5b50bb7b53a3ea0cfc35332cedf6e5e9a32e94da") {
        answer = "E";
    }
    var elemG = document.getElementById("confim-button".concat(nquestao));
    elemG.innerHTML = "<button id=\"button_effec" + nquestao + "\" class='botao msg-resposta btnwrong'/><i class=\"fa fa-times\" aria-hidden=\"true\"></i> Resposta Incorreta!</button><br><br><button id='resposta' class=\"showGabarito\"><i class='fa fa-eye' aria-hidden='true' ></i> Ver Gabarito - " + answer + "</button>";
}

function muda_pagina(caso) { //Muda Página
    var valor = document.getElementById("numero_pagina").value;
    if (caso == 1) {
        valor--;
    } else if (caso == 2) {
        valor++;
    }
    if (valor > 0) {
        document.getElementById("numero_pagina").value = valor;
        window.location.replace("?pagina=".concat(valor));
    } else {
        document.getElementById("numero_pagina").value = 1;
        window.location.replace("?pagina=".concat(1));
    }
}
