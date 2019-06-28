$(function () {

    $(document).on("change", "#health_status_list", function (event) {
        var $this = $(this);

        if ($this.val() == 1) {
            $("#diagnosis_list").removeClass("hidden");
        } else {
            $("#diagnosis_list").addClass("hidden");
        }
    });

    $(document).on("click", "#close-form-button", function (event) {
        $.ajax({
            type: "post",
            data: {
                action_list_id: 11,
                name: 1
            },
            url: $(this).data("url"),
            done: function (data, status, response) {
            }
        });
    });

});
