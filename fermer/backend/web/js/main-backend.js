$(function() {

    $(document).on("click", "button.addItem", function (e) {
        url = $(this).data("url");
        document.location.href = url;
    });

});