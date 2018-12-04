$(function () {

    var $listBlock = $("#list_block_item");
    var buttonAddItem = $("#add-action-item");
    var buttonRemoveItem = $("[remove-action-item]");
    var inputNewItem = $("#new-item");

    function removeItem(event) {
        $(event.target).closest("li").remove();
    }

    // Событие при удалении пункта меню
    buttonRemoveItem.click(removeItem);

    // Событие при добавлении пункта меню
    buttonAddItem.click(function (event) {
        var $newItem = $(event.target).closest(".input-group").find("#new-item");

        var $icon = $("<li>" + $newItem.val() + " <i remove-action-item class='fa fa-minus-circle'></i></li>");
        $icon.on("click", $('[remove-action-item]'), removeItem);

        $listBlock.append($icon);
        $newItem.val("").focus();
    });

    inputNewItem.on("keyup", function (event) {
        (!$(event.target).val()) ? buttonAddItem.attr("disabled", true) : buttonAddItem.attr("disabled", false);
    });

});