$(function () {

    let $childAnimals = $("#child_animals");

    $(document).on("change", "#health_status_list", function (event) {
        var $this = $(this);

        if ($this.val() == 1) {
            $("#diagnosis_list").removeClass("hidden");
        } else {
            $("#diagnosis_list").addClass("hidden");
        }
    });

    $(document).on("click", "#close-form-button", function (event) {
        event.preventDefault();

        $.ajax({
            type: "post",
            data: {
                animal_id: $(this).data("animal_id"),
                appropriation_scheme_id: $(this).data("appropriation_scheme_id")
            },
            url: $(this).data("url"),
            success: function (data, status, response) {
                $("#exampleModal").find(".modal-body").html(data);
                $("#exampleModal").modal("show");
                $("#closeschemeform-date_health").datepicker();
            }
        });
    });

    $(document).on("click", "#edit-insemination-button", function (event) {
        event.preventDefault();
        $.ajax({
            type: "get",
            url: $(this).data("url"),
            success: function (data, status, response) {
                var $modal = $("#edit-insemination-modal");
                $modal.find(".modal-body").html(data);
                $modal.modal("show");
                var id = "#" + $modal.find(".edit-insemination-datepicker").attr('id');
                $(id).datepicker();
            }
        });
    });

    function updateIndex() {
        let index = 0;
        let arr = ["sex", "label", "weight"];
        $childAnimals.find(".calving_row_block").each((i, el) => {
            $(el).find("select, input").each((ind, elem) => {
                $(elem)[0].name = `Calving[child][${index}][${arr[ind]}]`;
            });
            index++;
        });
    }

    $(document).on("click", "#add_calving_template", function (event) {
        let count = $childAnimals.find('.calving_row_block').length;

        let new_calving_template = _.template($("#newCalving").html())({
            index: count
        });

        $childAnimals.append(new_calving_template);
    });

    $(document).on("click", "#remove_calving", function (event) {
        let $calvingBlock = $(event.target).closest('.calving_row_block');
        let id = $(event.target).closest('.calving_row_block').attr('id');

        if (id) {
            console.log("ajax...");
        } else {
            $calvingBlock.remove();
            updateIndex();
        }
    });

    $(document).on("click", "#edit-calving-button", function (event) {
        event.preventDefault();
        $.ajax({
            type: "get",
            url: $(this).data("url"),
            success: function (data, status, response) {
                var $modal = $("#edit-calving-modal");
                $modal.find(".modal-body").html(data);
                $modal.modal("show");
                var id = "#" + $modal.find(".edit-calving-datepicker").attr('id');
                $(id).datepicker();
            }
        });
    });

    $(document).on("click", "#edit-rectal-button", function (event) {
        event.preventDefault();
        $.ajax({
            type: "get",
            url: $(this).data("url"),
            success: function (data, status, response) {
                var $modal = $("#edit-rectal-modal");
                $modal.find(".modal-body").html(data);
                $modal.modal("show");
                var id = "#" + $modal.find(".edit-rectal-datepicker").attr('id');
                $(id).datepicker();
            }
        });
    });

    $(document).on("click", "#add-rectal-button", function (event) {
        event.preventDefault();
        $.ajax({
            type: "get",
            url: $(this).data("url"),
            success: function (data, status, response) {
                var $modal = $("#add-rectal-modal");
                $modal.find(".modal-body").html(data);
                $modal.modal("show");
                var id = "#" + $modal.find(".add-rectal-datepicker").attr('id');
                $(id).datepicker();
            }
        });
    });

});
