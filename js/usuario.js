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