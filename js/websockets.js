var conn = new WebSocket('ws://localhost:8080'); //Local de Conexão

conn.onopen = function (e) {
    console.log("Connection established!"); //Apenas para teste
};

conn.onmessage = function (msg) {
    var str = msg.data;
    if (str.charAt(0) == '1') { //Notificação
        str = str.substring(1); //Retirando o Caractere de Identificação
        var n = str.search("&&&");
        var texto = str.substring(n + 3);
        var id_notf = parseInt(str.substring(0, n)); //ID da Notificação
        var page = "db_notificacao.php";
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: page,
            data: {
                tipo: 1,
                id_notf: id_notf
            }
        });
        //Salvar no DB que a Notificação ainda n foi lida

        var separador = "";
        if (document.getElementById('addNotification').innerHTML.search("li") != -1) {
            separador = "<li role='separator' class='divider'></li>";
        }
        $('#addNotification').append(separador + "<li class='notificacao' id='" + id_notf + "'>" + texto + "</li>");
        var elem = document.getElementById('badge');
        var numero = isNaN(parseInt(elem.innerHTML)) ? 1 : parseInt(elem.innerHTML) + 1;
        if (numero > 99) {
            numero = "99+"; //Evitar que o numero de notificações estoure
        }
        elem.innerHTML = numero;
        $('#badge').removeClass('dnone');
        if ($('#iconNotif').html() == "notifications") {
            $('#iconNotif').html("notifications_active");
        }
        $('#iconNotif').addClass('shakeIt');
        var bell = document.getElementById('iconNotif');
        $("body").append("<audio autoplay id='audioNotif'><source src='sounds/notificacao.mp3' type='audio/mp3'></audio>");
        bell.addEventListener("animationend", function(){
            $('#iconNotif').removeClass('shakeIt');
        }, false);
        setTimeout(function(){
            var audio = document.getElementById('audioNotif');
            audio.parentNode.removeChild(audio);
        },1000)
        
    } else if (str.charAt(0) == '2') { //Comentário novo

        str = str.substring(1); //Retirando o Caractere de Identificação
        var identidades = str.split('-'); //[0] => COMENTARIO  [1] => NÚMERO QUESTAO
        if (document.getElementById('painelq' + identidades[1])) { //O comentário deve ser incluido na Página que o usuário está
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: 'busca_comentario.php',
                data: {
                    id_comentario: identidades[0],
                    id_questao: identidades[1]
                },
                success: function (retorno) {
                    var elemHTML = document.getElementById("comentariosGerais" + identidades[1]);
                    if (elemHTML.innerHTML.search("alert alert-danger") == -1) {
                        elemHTML.innerHTML += retorno;
                    } else {
                        elemHTML.innerHTML = retorno;
                    }
                }
            });
        }
    } else if (str.charAt(0) == '3') {//Excluir Comentario

        str = str.substring(1); //Retirando o Caractere de Identificação
        var identidades = str.split('-');; //[1] => COMENTARIO  [0] => NÚMERO QUESTAO
        var elemHTML = document.getElementById('painelComentario' + identidades[0] + identidades[1]);
        if (elemHTML) { //Comentário existe na Página
            elemHTML.parentNode.removeChild(elemHTML); //Excluir o Elemento
        }
        var parent = document.getElementById("comentariosGerais" + identidades[0]);
        if (parent.innerHTML=="") {
            parent.innerHTML = "<tr><div class=\"alert alert-danger\" style='margin-top: 20px;' role=\"alert\"><strong>Nenhum comentário encontrado, atualize a página e tente novamente.</strong></div></tr>"; //Aviso de que não tem comentários
        }
    }

};