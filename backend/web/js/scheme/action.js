$(function () {

    var $selectTypeField = $("#selectTypeField");
    var $selectList = $("#selectList");

    $selectTypeField.change(function (event) {
        var $this = $(event.target);
        var value  = $this.val();
        var typeList = $this.data('type-list');

        var styleListBlock = "none";
        if (value == typeList) {
            styleListBlock = "block";
        }

        $selectList.closest("#actionListBlock").css("display", styleListBlock);
    });
});