$(function () {

    var $groupsActionList = $("[name='groups-action-list']");
    var $buttonAddGroupAction = $('[add-groups-action]');
    var $buttonRemoveGroupsAction = $("[remove-groups-action]");
    var $buttonAddNewDay = $("#add-day");
    var $newDay = $("#new-day");

    $newDay.change(function (event) {
        var value = $(event.target).val();
        if (value && value > 0) {
            $buttonAddNewDay.attr("disabled", false);
        } else {
            $(event.target).val(0);
            $buttonAddNewDay.attr("disabled", true);
        }
    });

    $buttonAddNewDay.click(function (event) {
        var $this = $(event.target);
        var $newDay = $this.closest(".input-group").find("#new-day");

        $.ajax({
            type: "post",
            data: {
                number_day: $newDay.val()
            },
            url: $this.data("add-day-url"),
            success: function (data, status, response) {
                // var type = "success";
                if (response.status == 200) {
                    console.log(1);
                    // $this.closest(".day_block").find("table tbody").append(data.render);
                    // $('[remove-action]').on("click", removeAction);
                } else {
                    // type = "danger";
                }
                // notify(data.message, type);
            }
        });
    });

    $groupsActionList.change(function (event) {
        if ($(event.target).val()) {
            $buttonAddGroupAction.attr("disabled", false);
        } else {
            $buttonAddGroupAction.attr("disabled", true);
        }
    });

    $buttonAddGroupAction.click(function (event) {
        var $this = $(event.target);
        var $newAction = $this.closest(".input-group").find("[name='groups-action-list']");

        $.ajax({
            type: "post",
            data: {
                scheme_day_id: $(this).data("day-id"),
                groups_action_id: $newAction.val()
            },
            url: $this.data("add-group-action-url"),
            success: function (data, status, response) {
                // var type = "success";
                if (response.status == 200) {
                    $this.closest(".day_block").find("table tbody").append(data.render);
                    // $('[remove-action]').on("click", removeAction);
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
        var $tr = $(event.target).closest("tr");

        $.ajax({
            type: "get",
            url: $tr.data("remove-group-action-url"),
            success: function (data, status, response) {
                if (response.status == 200) {
                    $tr.closest("tr").remove();
                } else {
                }
            }
        });
    }

    // Событие при удалении группы действий у дня
    $buttonRemoveGroupsAction.click(removeGroupsAction);

});