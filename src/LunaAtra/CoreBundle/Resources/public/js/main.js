$(function(){

    $(document).on("click", function(e){
        console.log($(e.target));
        if(!$(e.target).hasClass("dropdown")
            && $(e.target).closest(".dropdown-menu").length == 0
            && $(e.target).closest(".dropdown").length == 0
            && !$(e.target).hasClass("dropdown-menu")
            ){
            $(".dropdown.open").removeClass("open");
        }
    });
    $(".dropdown").on("click", function(e){
        $(this).toggleClass("open");
        return false;
    });


})