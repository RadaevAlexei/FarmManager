$(function () {

    var $actionSelect = $("[name='groups-action-list']");
    var $actionListBlock = $("#action_list_block");
    var $buttonAddAction = $("#add-action");
    var $buttonRemoveAction = $("[remove-action]");
    var $buttonRemoveGroupsAction = $(".remove-groups-action");

    $actionSelect.change(function (event) {
        if ($(event.target).val()) {
            $buttonAddAction.attr("disabled", false);
        } else {
            $buttonAddAction.attr("disabled", true);
        }
    });

    function removeAction(event) {
        var $li = $(event.target).closest("li");

        $.ajax({
            type: "get",
            url: $li.data("remove-action-url"),
            success: function (data, status, response) {
                if (response.status == 200) {
                    $li.closest("li").remove();
                } else {
                }
            }
        });
    }

    $buttonAddAction.click(function (event) {
        $this = $(event.target);
        var $newAction = $this.closest(".input-group").find("[name='groups-action-list']");

        $.ajax({
            type: "post",
            data: {
                groups_action_id: $(this).data("groups-action-id"),
                action_id: $newAction.val()
            },
            url: $this.data("add-action-url"),
            success: function (data, status, response) {
                // var type = "success";
                if (response.status == 200) {
                    $actionListBlock.append(data.render);
                    $('[remove-action]').on("click", removeAction);
                } else {
                    // type = "danger";
                }
                // notify(data.message, type);
            }
        });
    });

    /**
     * Удаление группы действий
     * @param event
     */
    function removeGroupsAction(event) {
        var $this = $(event.target);
        var $tr = $this.closest("tr");

        $.ajax({
            type: "get",
            url: $this.data("url"),
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

    // Событие при удалении действия из группы
    $buttonRemoveAction.click(removeAction);

    // Событие при удалении группы действий
    $buttonRemoveGroupsAction.click(removeGroupsAction);

});