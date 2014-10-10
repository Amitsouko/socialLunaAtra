$(function(){
    //DropDown
    $(document).on("click", function(e){
        if(!$(e.target).hasClass("dropdown")
            && $(e.target).closest(".dropdown-menu").length == 0
            && $(e.target).closest(".dropdown").length == 0
            && !$(e.target).hasClass("dropdown-menu")
            ){
            $(".dropdown.open").removeClass("open");
        }
      
    });
    $(".dropdown").on("click", function(e){
        $(".dropdown.open").not($(this)).removeClass("open");
        $(this).toggleClass("open");
        return false;
    });

    //check privacy
    $("#form_privacy input[type='checkbox']").on("change", function(e){
        if($(this).is( ":checked") && $(this).val() == "_100_") 
        {
            //onley me unchecked
            $("#form_privacy input[type='checkbox']").not($(this)).prop("checked", false);
        }else if($("#form_privacy input[type='checkbox']:checked").length == 0)
        {
            //everything unchecked
            $("#form_privacy input[value='_0_']").prop("checked",true);

        }else if($(this).is( ":checked") && $(this).val() == "_0_"){
            //if check everything
            $("#form_privacy input[type='checkbox']").not($(this)).prop("checked", false);
        }else{
            //else uncheck extrems
            $("#form_privacy input[value='_0_']").prop("checked",false);
            $("#form_privacy input[value='_100_']").prop("checked",false);
        }
        console.log($(this) != $("#form_privacy input[value='_0_']"));
    });

    $(".sortable-table").sortable({
        axis:"y",
        containment:"parent",
        tolerance:"pointer",
        items: "li:not(.header)",
        revert:100
    })
    .disableSelection()
    .on("sortupdate", function(e,ui){
        $(".form-reorder").removeClass("hidden");
        $(".form-reorder-success").addClass("hidden");
    });

    $(".form-reorder form").on("submit",function(e){
        e.preventDefault();
        e.stopPropagation();
        $(this).find("input[type='submit']").hide();

        $.ajax({
            type: "POST",
            url: $(".form-reorder form").attr("action"),
            data: { order: $(".sortable-table").sortable("toArray").toString() }
        })
        .done(function( msg ) {
            if(typeof(msg["success"]) != "undefined" )
            {
                 $(".form-reorder form").find("input[type='submit']").show();
                $(".form-reorder-success").removeClass("hidden");
                $(".form-reorder").addClass("hidden");
            }
        });
    });


    // $('#image-cover-profile').waitForImages(function() {
    //    //   $(this).animate({opacity: "1"}, 1500);
    // },
    //  function(loaded, count, success) {
    //        alert(loaded + ' of ' + count + ' images has ' + (success ? 'loaded' : 'failed to load') +  '.');
    //        $(this).addClass('loaded');
    //     }

    // );

    // $('#image-cover-profile').waitForImages(function() {
    //         //alert('All images have loaded.');
    //     }, function(loaded, count, success) {
    //        alert(loaded + ' of ' + count + ' images has ' + (success ? 'loaded' : 'failed to load') +  '.');
    //        $(this).addClass('loaded');
    //     });

    $('#image-cover-profile').waitForImages({
        finished: function() {
            console.log("end");
        },
        each: function() {
            $(this).animate({opacity: "1"}, 1500);
          // console.log($(this));
        },
        waitForAll: true
    });

})