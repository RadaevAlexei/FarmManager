$(function () {

    var $selectCategoryList = $("#select-category");
    var $selectClassificationList = $("#select-classification");
    var $selectBetaClassificationList = $("#select-beta");

    var $selectPeriodMilkDay = $("#period_milk_day");
    var $selectPeriodMilkHour = $("#period_milk_hour");
    var $selectPeriodMeatDay = $("#period_meat_day");
    var $selectPeriodMeatHour = $("#period_meat_hour");

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

    $selectPeriodMilkDay.change(function (event) {
        var $this = $(event.target);
        var value  = $this.val();
        if (value < 0) {
            $this.val(0);
        }

        var result  = (value * 24).toFixed(1);

        $selectPeriodMilkHour.val(result);
    });

    $selectPeriodMilkHour.change(function (event) {
        var $this = $(event.target);
        var value  = $this.val();
        if (value < 0) {
            $this.val(0);
        }
        var result  = (value / 24).toFixed(1);

        $selectPeriodMilkDay.val(result);
    });

    $selectPeriodMeatDay.change(function (event) {
        var $this = $(event.target);
        var value  = $this.val();
        if (value < 0) {
            $this.val(0);
        }
        var result  = (value * 24).toFixed(1);

        $selectPeriodMeatHour.val(result);
    });

    $selectPeriodMeatHour.change(function (event) {
        var $this = $(event.target);
        var value  = $this.val();
        if (value < 0) {
            $this.val(0);
        }
        var result  = (value / 24).toFixed(1);

        $selectPeriodMeatDay.val(result);
    });
});