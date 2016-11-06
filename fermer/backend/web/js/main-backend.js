$(function() {

    $(document).on("click", "button#addFunction, button#addEmployee, button#addGroup", function (e) {
        url = $(this).data("url");
        document.location.href = url;
    });

});