$(function () {

    var $listBlock = $("#list_block_item");
    var buttonAddItem = $("#add-action-item");
    var buttonRemoveItem = $("[remove-action-item]");
    var inputNewItem = $("#new-item");
    var buttonRemoveActionList = $(".remove-groups-action");

    function removeItem(event) {
        var $li = $(event.target).closest("li");

        $.ajax({
            type: "get",
            url: $li.data("remove-url"),
            success: function (data, status, response) {
                var type = "success";
                if (response.status == 200) {
                    $li.closest("li").remove();
                } else {
                    type = "danger";
                }
                // notify(data.message, type);
            }
        });
    }

    /**
     * Удаление списка
     * @param event
     */
    function removeActionList(event) {
        var $tr = $(event.target).closest("tr");

        $.ajax({
            type: "get",
            url: $(event.target).data("url"),
            success: function (data, status, response) {
                // var type = "success";
                if (response.status == 200) {
                    $tr.remove();
                } else {
                    // type = "danger";
                }
                // notify(data.message, type);
            }
        });
    }

    // Событие при удалении списка
    buttonRemoveActionList.click(removeActionList);

    // Событие при удалении пункта меню
    buttonRemoveItem.click(removeItem);

    // Событие при добавлении пункта меню
    buttonAddItem.click(function (event) {
        var $newItem = $(event.target).closest(".input-group").find("#new-item");

        $.ajax({
            type: "post",
            data: {
                action_list_id: $(this).data("action-list-id"),
                name: $newItem.val()
            },
            url: buttonAddItem.data("add-item-url"),
            success: function (data, status, response) {
                var type = "success";
                if (response.status == 200) {
                    $listBlock.append(data.render);
                    $newItem.val("").focus();
                    $('[remove-action-item]').on("click", removeItem);
                } else {
                    type = "danger";
                }
                // notify(data.message, type);
            }
        });
    });

    inputNewItem.on("keyup", function (event) {
        (!$(event.target).val()) ? buttonAddItem.attr("disabled", true) : buttonAddItem.attr("disabled", false);
    });

});