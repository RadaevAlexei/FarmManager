/**
 *
 * @param $datepicker
 * @param $enabledDates
 */
function initDatepciker($datepicker, $enabledDates) {
    $datepicker.datepicker({
        format: "dd/mm/yyyy",
        language: "ru",
        calendarWeeks: true,
        showOtherMonths:false,
        todayHighlight: true,

        beforeShowDay: function (date){
            var curDate = date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
            if ($.inArray(curDate, $enabledDates) == -1) {
                return {
                    enabled: false,
                    tooltip: '',
                    classes: ''
                };

            } else {
                return {
                    enabled: true,
                    tooltip: 'Урааа',
                    classes: 'active'
                };
            }
        },
        beforeShowMonth: function (date){
        },
        beforeShowYear: function (date){
        }
    });
}

/**
 *
 * @param $datepicker
 * @param groupId
 * @returns {boolean}
 */
function loadDates($datepicker, groupId) {
    var isEmptyGroupId = isEmpty(groupId);

    if (isEmptyGroupId) {
        return false;
    }

    $.ajax({
        url: document.location.protocol + "//" + document.location.host + '/suspension/load-dates/',
        data: {
            groupId : groupId
        },
        method: 'GET',
        success: function (dates) {
            if (dates.length > 0) {
                initDatepciker($datepicker, dates);
                $("#dateSuspensionBlock").removeAttr("hidden");
            } else {
                alert("У данной группы нет перевесок!!!");
            }

        },
        error: function (er) {
        },
        complete: function(){
        }
    });
}

function isEmpty(field)
{
    if (field == undefined) {
        return true;
    } else {
        if  (field.length > 0) {
            return false;
        } else {
            return true;
        }
    }
}

function drawChart(weights, dates) {
    var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var randomScalingFactor = function() {
        return Math.ceil(Math.random() * 10.0) * Math.pow(10, Math.ceil(Math.random() * 5));
    };
    var randomColorFactor = function() {
        return Math.round(Math.random() * 255);
    };
    var randomColor = function(opacity) {
        return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',' + (opacity || '.3') + ')';
    };
    var config = {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: "My Second dataset",
                data: weights,
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'Chart.js Line Chart - Logarithmic'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'x axis'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'y axis'
                    }
                }]
            }
        }
    };
    $.each(config.data.datasets, function(i, dataset) {
        dataset.borderColor = randomColor(0.4);
        dataset.backgroundColor = randomColor(0.5);
        dataset.pointBorderColor = randomColor(0.7);
        dataset.pointBackgroundColor = randomColor(0.5);
        dataset.pointBorderWidth = 1;
    });

    var ctx = document.getElementById("canvas").getContext("2d");
    window.myLine = new Chart(ctx, config);
}

$(function() {

});