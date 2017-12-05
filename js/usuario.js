(function () {
    /*1*/
    var customSelects = document.querySelectorAll(".custom-dropdown__select");
    /*2*/
    for (var i = 0; i < customSelects.length; i++) {
        if (customSelects[i].hasAttribute("disabled")) {
            customSelects[i].parentNode.className += " custom-dropdown--disabled";
        }
    }
})()

function activeSenha() {
    document.getElementById('perfilSection').style.display = 'none';
    document.getElementById('senhaSection').style.display = 'block';
}

function activePerfil() {
    document.getElementById('perfilSection').style.display = 'block';
    document.getElementById('senhaSection').style.display = 'none';
}
$('.tagItem').click(function () {
    if (!$(this).hasClass('activeTag')) {
        $('.activeTag').removeClass('activeTag');
        $(this).toggleClass('activeTag');
    }
});

function changeIcon(select){
    document.getElementById('imgView').setAttribute('src',"img/"+select.options[select.selectedIndex].value);
}

function salvaDados () {
    var page = "functionsPHP/updateUsuario.php";
    $.ajax({
        type: 'POST',
        dataType: 'html',
        data: {
            nome: $('#nNome').val(),
            sobrenome: $('#nSobrenome').val(),
            foto_perfil: $('#iconePerfil').val(),
            celular: $('#nCelular').val()
        },
        url: page,
        success: function (msg) {
            if(msg=="1"){//Ok
                $("#dialogContainer").html("<div id='dialogBox' class='dialogGreen' style='position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>Alterações concluidas</p></div>");
                if(document.getElementById('imgUserView') != null){
                   document.getElementById('imgUserView').setAttribute('src',"img/"+$('#iconePerfil').val());
                }
            } else {
                $("#dialogContainer").html("<div id='dialogBox' class='dialogRed' style='position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>Algo deu errado, tente novamente.</p></div>");
            }
            setTimeout(closeAlert,4000);
        }
    });
}

function changePassword () {
    var page = "functionsPHP/updateUsuarioPass.php";
    if(verificaSenhas()){
        $.ajax({
            type: 'POST',
            dataType: 'html',
            url: page,
            data: {
                senhaAtual: $("#senhaAtual").val(),
                senhaNova: $("#nSenha").val()
            },
            success: function (msg) {
                if(msg=="1"){//Ok
                    $("#dialogContainer").html("<div id='dialogBox' class='dialogGreen' style='position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>Alterações concluidas</p></div>");
                } else if (msg=="2") {
                    $("#dialogContainer").html("<div id='dialogBox' class='dialogRed' style='position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>Senha não corresponde à do Banco de Dados.</p></div>");
                } else {
                    $("#dialogContainer").html("<div id='dialogBox' class='dialogRed' style='position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>Algo deu errado, tente novamente.</p></div>");
                }
                $("#nSenha").css("border-bottom-color","#9e9e9e").val("");
                $("#nSenhaConf").css("border-bottom-color","#9e9e9e").val("");
                $("#senhaAtual").css("border-bottom-color","#9e9e9e").val("");
                setTimeout(closeAlert,4000);
            }
        });  
    } else {
         $("#dialogContainer").html("<div id='dialogBox' class='dialogRed' style='position: fixed; right: 2px; bottom: 10px;'><i style='float: right; cursor: pointer;' onclick='closeAlert()'  class='fa fa-window-close fa-lg' aria-hidden='true'></i><p>As senhas não conferem.</p></div>");
        setTimeout(closeAlert,2000);
    }
}

function changeVisibility(elem,idElem){
    if(elem.innerHTML=="visibility"){
        elem.innerHTML = "visibility_off";
        $("#"+idElem).attr('type','text');
    } else {
        elem.innerHTML = "visibility";
        $("#"+idElem).attr('type','password');
    }
}

function verificaSenhas(){//Verifica a senha se são iguais
    if($("#nSenha").val() != $("#nSenhaConf").val() && $("#nSenhaConf").val() != ""){
        $("#nSenha").css("border-bottom-color","#f44336");
        $("#nSenhaConf").css("border-bottom-color","#f44336");
    } else {
        if($("#nSenha").val() != "" && $("#nSenhaConf").val() != ""){
            $("#nSenha").css("border-bottom-color","#26a69a");
            $("#nSenhaConf").css("border-bottom-color","#26a69a");
            return true;
        }
    }
    return false;
}

var trava = false;
var iCount1, iCount2, iCount, iTexto, nChar;
function MaskDown(e) {
        if(trava == false) {
                iTexto = e.value;
                iCount1 = e.value.length;
                trava = true;
        }
}

function closeAlert(){
        var elem = document.getElementById("dialogBox");
        elem.style.opacity = "0";
        setTimeout(function() {elem.style.display = "none";},500);
}

function MaskUp(e,evt,msc) {
iCount2 = e.value.length;
var key_code = evt.keyCode ? evt.keyCode : evt.charCode ? evt.charCode : evt.which ? evt.which : void 0;
if (key_code == 9) {
                iCount1 = iCount2-1;
                e.select;
                
} else {
if (iCount2 > iCount1) {
        e.value = e.value.substr(0,iCount1+1);
        if(e.value.length > msc.length) {
                e.value = e.value.substr(0,msc.length);
        }
        if(iCount1 == 0) {
                if (msc.substring(iCount1,iCount1+1) != "#") {
                        nChar=1;
                        while (msc.substring(iCount1+nChar,iCount1+nChar+1) != "#" && nChar <= msc.length) {
                                nChar++;        
                        }
                        e.value = msc.substring(0,iCount1+nChar) + e.value.substr(0,iCount1+1);
                } 
        } else {
                if (msc.substring(iCount1+1,iCount1+2) != "#") {
                        var nChar=1;
                        while (msc.substring(iCount1+nChar,iCount1+nChar+1) != "#" && nChar <= msc.length) {
                                nChar++;        
                        }
                        e.value = e.value.substr(0,iCount1+1) + msc.substring(iCount1+1,iCount1+nChar);
                }
        }
} else if (iCount2 == iCount1) {
        e.value = e.value;
} else {        
        if (msc.substr(iCount2,1) != "#") {     

                nChar = 1;
                while (msc.substr(iCount1-nChar,1) != "#" && nChar <= iCount1) {
                        nChar++;        
                }
                e.value = iTexto.substr(0,iCount2-nChar+1);
        }

}
trava = false;
}}