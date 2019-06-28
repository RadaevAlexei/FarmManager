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
        event.preventDefault();

        $.ajax({
            type: "post",
            data: {
                animal_id: $(this).data("animal_id"),
                appropriation_scheme_id: $(this).data("appropriation_scheme_id")
            },
            url: $(this).data("url"),
            success: function (data, status, response) {
                $("#exampleModal").find(".modal-body").html(data);
                $("#exampleModal").modal("show");
                $("#closeschemeform-date_health").datepicker();
            }
        });
    });

});
