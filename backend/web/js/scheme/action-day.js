$(function () {
    
    $(".execute-action").click(function(event){
        var $this = $(event.target);

        var $form = $this.closest("form#execute-action-form");

        $.ajax({
            type: "post",
            data: $form.serialize(),
            url: $form.attr("action"),
            success: function (data, status, response) {
                // var type = "success";
                if (response.status == 200) {
                    // $this.closest(".day_block").find("table tbody").append(data.render);
                    // $('[remove-action]').on("click", removeAction);
                } else {
                    // type = "danger";
                }
                // notify(data.message, type);
            }
        });
    });

});