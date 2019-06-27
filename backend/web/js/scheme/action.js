$(function () {

    var $selectTypeField = $("#selectTypeField");
    var $selectList = $("#selectList");
    var $selectPreparation = $("#preparationListBlock");

    $selectTypeField.change(function (event) {
        var $this = $(event.target);
        var value  = $this.val();
        var typeList = $this.data('type-list');
        var typeNumber = $this.data('type-number');

        var styleListBlock = "none";
        $selectPreparation.addClass("hidden");

        if (value == typeList) {
            styleListBlock = "block";
        } else if (value == typeNumber) {
            $selectPreparation.removeClass("hidden");
        }

        $selectList.closest("#actionListBlock").css("display", styleListBlock);
    });
});