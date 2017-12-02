function vMatricula(){ //Verifica se o campo matricula é menor que Zero 
    var elem = document.getElementById("matricula");
    if(parseInt(elem.value)<0){
        elem.value = null;
        return 1;
    } else {
        return 0;
    }
}

function vEmail(elemento){//Pega o e-mail até um caracter antes do '@'
    var email = elemento.value;
    var n = email.search("@");
    if(n != -1){
        elemento.value = email.substr(0,n);
    }
}

function vSelect(){
    var ano = document.getElementById("lblAno");
    var instituicao = document.getElementById("lblInstituicao");
    if(document.getElementById("ano").value == null){
        ano.style.color = "red";
    } else {
        ano.style.color = "#333333";
    }
    if(document.getElementById("instituicao").value == null){
        instituicao.style.color = "red";
    } else {
        instituicao.style.color = "#333333";
    }
}

function verifEqual(elemA,B,id,idC){
    var elemB = document.getElementById(B);
    if(elemA.value != elemB.value){
        document.getElementById(id).style.display = "block";
        document.getElementById(idC).style.color = "red";
    } else {
        document.getElementById(id).style.display = "none";
        document.getElementById(idC).style.color = "#333333";
    }
    verifSubmit();
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

function verifSubmit(){
        var pass1, pass2, email1, email2;
        pass1 = document.getElementById('senha').value;
        pass2 = document.getElementById('senhaConfirm').value;
        email1 = document.getElementById('email').value;
        email2 = document.getElementById('emailConfirm').value;
        if(email1 == email2 && pass1 == pass2 && email1 != '' && pass1 != ''){
                document.getElementById("btnCadastro").disabled=false;
        } else {
                document.getElementById("btnCadastro").disabled=true;
        }

}