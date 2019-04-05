$(function () {

    $(document).on("change", "#health_status_list", function (event) {
        var $this = $(this);

        if ($this.val() == 1) {
            $("#diagnosis_list").removeClass("hidden");
        } else {
            $("#diagnosis_list").addClass("hidden");
        }
    });

});
