$(function () {

    $(document).on("click", "#print-rectal-report-button", function (event) {
        event.preventDefault();
        $.ajax({
            type: "get",
            url: $(this).data("url"),
            success: function (data, status, response) {
                var $modal = $("#print-rectal-report-modal");
                $modal.find(".modal-body").html(data);
                $modal.modal("show");
            }
        });
    });

});
