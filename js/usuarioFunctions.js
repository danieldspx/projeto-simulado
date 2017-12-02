function verificaSenhas(){//Verifica a senha se são iguais
    var senha1 = document.getElementById("senha").value;
    var senha2 = document.getElementById("senhaConfirm").value;
    elemS = document.getElementById("idSenha");
    if(senha1 != senha2){
        document.getElementById("idSenha").className = "sub-red";
        elemS.innerHTML = "(Senhas não conferem!)";
    } else {
        document.getElementById("idSenha").className = "sub-green";
        elemS.innerHTML = "(Senhas conferem!)";
    }
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