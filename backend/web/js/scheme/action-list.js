$(function () {

    var $listBlock = $("#list_block_item");
    var buttonAddItem = $("#add-action-item");
    var buttonRemoveItem = $("[remove-action-item]");
    var inputNewItem = $("#new-item");

    function notify(message, type) {
        $.notify({
            // options
            message: message,
            url: 'https://github.com/mouse0270/bootstrap-notify',
            target: '_blank'
        }, {
            // settings
            element: 'body',
            position: null,
            type: type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "right"
            },
            offset: 20,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title">{1}</span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
    }

    function removeItem(event) {
        var $li = $(event.target).closest("li");
        // $(event.target).closest("li").remove();
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
                notify(data.message, type);
            }
        });
    }

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
                notify(data.message, type);
            }
        });
    });

    inputNewItem.on("keyup", function (event) {
        (!$(event.target).val()) ? buttonAddItem.attr("disabled", true) : buttonAddItem.attr("disabled", false);
    });

});