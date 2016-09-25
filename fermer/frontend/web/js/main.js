$(function() {

    $(document).on("click", "table.f-table-list tr", function (e) {
        var id = $(this).data("id");
        document.location.href = "/detail/" + id + "/";
    });

});