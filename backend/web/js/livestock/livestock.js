$(function () {

    $(document).on("click", "#print-livestock-report-button", function (event) {
        event.preventDefault();
        $.ajax({
            type: "get",
            url: $(this).data("url"),
            success: function (data, status, response) {
                var $modal = $("#print-livestock-report-modal");
                $modal.find(".modal-body").html(data);
                $modal.modal("show");
            }
        });
    });

});
