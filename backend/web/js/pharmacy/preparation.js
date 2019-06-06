$(function () {

    var $selectCategoryList = $("#select-category");
    var $selectClassificationList = $("#select-classification");
    var $selectBetaClassificationList = $("#select-beta");

    $selectCategoryList.change(function (event) {
        var $this = $(event.target);
        var value  = $this.val();

        if (value == 1) {
            $(".select-classification-block").removeClass("hidden");
        } else {
            $(".select-classification-block").addClass("hidden");
            $(".select-beta-block").addClass("hidden");
        }

        $selectClassificationList.val('');
        $selectBetaClassificationList.val('');
    });

    $selectClassificationList.change(function (event) {
        var $this = $(event.target);
        var value  = $this.val();

        if (value == 1) {
            $(".select-beta-block").removeClass("hidden");
        } else {
            $(".select-beta-block").addClass("hidden");
        }

        $selectBetaClassificationList.val('');
    });
});