$(function() {

    $(document).on("click", "button#addFunction, button#addEmployee, button#addGroup, button#addCalf, button#addSuspension", function (e) {
        url = $(this).data("url");
        document.location.href = url;
    });

});