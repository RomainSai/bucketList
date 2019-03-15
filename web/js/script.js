//Fermeture du DropDown au click
$().ready(function () {
    $('.nav-link').on('click', function(){
            $('.navbar-collapse').collapse('hide');
    });
});
