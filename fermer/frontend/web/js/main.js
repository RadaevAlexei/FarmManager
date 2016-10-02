$(function() {

    /*$(document).on("click", "table.f-table-list tr", function (e) {
        var id = $(this).data("id");
        document.location.href = "/detail/" + id + "/";
    });*/


    $(document).on("click", "button#addFunction, button#addEmployee", function (e) {
        url = $(this).data("url");
        document.location.href = url;
    });

    $('#datetimepicker1').datetimepicker({
        language: 'ru',
        format: "DD-MM-YYYY",
        pickTime: false
    });

});