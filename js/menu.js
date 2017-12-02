$(document).ready(function() {
    var prim =  null, segu = null;
    prim = $(window).scrollTop();
    $(window).scroll(function () {
        segu = $(window).scrollTop();
        if (prim < segu) {
            $("#menu-groupID").removeClass("fadeInDown");
            $("#menu-groupID").addClass("fadeOutUp");
            if(document.getElementById('hamburger-6').className.search("is-active") != -1){//Fecha menu ao scroll
                showMenu();
            }
            if(document.getElementById('iconPlus').className.search("rotate-iconPlus") != -1){//Fecha Links Uteis
                $("#iconPlus").toggleClass("rotate-iconPlus");
                var elemento = document.getElementById("linksUteis");
                if(elemento.style.display == "none"){
                    elemento.style.display = "inline-block";
                } else{
                    elemento.style.display = "none";
                }
            }
        prim = $(window).scrollTop();
        } else {
            $("#menu-groupID").removeClass("fadeOutUp");
            $("#menu-groupID").addClass("fadeInDown");
            prim = $(window).scrollTop();
        }
    });
});

$("#moreLink").click(function (){
    $("#iconPlus").toggleClass("rotate-iconPlus");
    var elemento = document.getElementById("linksUteis");
    if(elemento.style.display == "none"){
        elemento.style.display = "inline-block";
    } else{
        elemento.style.display = "none";
    }
});
function fixMenuButton(){ //CLEAR FIX
    var elemA = document.getElementById("hamburger-6");
    var elemento = document.getElementById("menu-active");
    if(elemento.style.display=="none" && (elemA.className).search("is-active") != -1){
        $(".hamburger").toggleClass("is-active");
    }
    
}
function logOut(){
    document.logOutForm.submit();
}

function showMenu(){
    $(".hamburger").toggleClass("is-active");
    var elemento = document.getElementById("menu-active");
    if((elemento.className).search("fadeOutLeft") != -1 || (elemento.className).search("first") != -1){
        elemento.style.display="block";
        if((elemento.className).search("first") != -1){//Primeira vez que usa
            $("#menu-active").removeClass("first");
        }
        $("#menu-active").removeClass("fadeOutLeft");
        $("#menu-active").addClass("fadeInLeft");
    } else {
        $("#menu-active").removeClass("fadeInLeft");
        $("#menu-active").addClass("fadeOutLeft");
        $('#menu-active').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
            if((elemento.className).search("fadeOutLeft") != -1){
                elemento.style.display="none";
            }
            fixMenuButton();
        });
    }
    setTimeout(fixMenuButton(),1000);
}