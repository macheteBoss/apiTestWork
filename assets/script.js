$(document).ready(function () {

    $("#loadData").on("submit", function (e) {
        e.preventDefault();

        var formData = new FormData($(this).get(0));

        $.ajax({
            url: "/ajax/loadData.php",
            processData: false,
            contentType: false,
            cache:false,
            type: "post",
            dataType: 'json',
            data: formData,
            success: function(data) {

                let errors = $("form#loadData").find(".errorField");
                if(errors.length > 0) {
                    errors.remove();
                }
                if(!data.fatal) {
                    let fatals = $("form#loadData").find(".fatalError");
                    if(fatals.length > 0) {
                        fatals.remove();
                    }
                }

                if(data.status == "success") {
                    $("form#loadData").trigger("reset");
                    $("form#loadData .fields").append("<div class = 'field field--success'>"+data.result+"</div>");
                    setTimeout(function () {
                        window.location.href = "/";
                    }, 2500);
                } else {
                    if(data.fatal) {
                        $("#"+data.invalid["fatal"]).append('<span class = "errorField fatalError">'+data.result["fatal"]+'</span>');
                    }
                    $.each(data.invalid, function (index, value) {
                        let errors = $("form#loadData #"+value).parents(".field").find(".errorField");
                        if(errors.length == 0) {
                            $("form#loadData #"+value).parents(".field").append('<span class = "errorField">'+data.result[index]+'</span>');
                        } else {
                            $("form#loadData #"+value).parents(".field").find(".errorField").text(data.result[index]);
                        }
                    });
                }

            }
        });
    });

});