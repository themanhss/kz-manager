$(function () {
    /*
     * BAR CHART
     * ---------
     */

   /* var bar_data = {
        data: [["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9]],
        color: "#3c8dbc"
    };
    $.plot("#bar-chart", [bar_data], {
        grid: {
            borderWidth: 1,
            borderColor: "#f3f3f3",
            tickColor: "#f3f3f3"
        },
        series: {
            bars: {
                show: true,
                barWidth: 0.5,
                align: "center"
            }
        },
        xaxis: {
            mode: "categories",
            tickLength: 0
        }
    });*/

    /* END BAR CHART */

    showChart();
});

/*
*  Function to show chart in the Dashboard
* */
function showChart() {
    // Get Token prepare to post
    var CSRF_TOKEN = $('#tocken').val();

    // Init data to generate to bar chart
    //[["January", 10], ["February", 8], ["March", 4], ["April", 13], ["May", 17], ["June", 9]];
    var data_export = [];

    // Post ajax to get data about chart
    $.ajax({
        url : baseUrl()+"/admin/promotions/chart",
        headers: { "X-CSRF-TOKEN": CSRF_TOKEN },
        method: 'post',
        dataType: 'json',
        data: {},
        success : function(datas){
            datas =  datas.promotions;

            for(var i = 0; i< datas.length; i++){


                data_export[i] = [];
                data_export[i][0] = datas[i].name.toString();
                data_export[i][1] = parseInt(datas[i].total_leads);

                console.log(data_export[i]);
            }

            var bar_data = {
                data: data_export,
                color: "#3c8dbc"
            };
            $.plot("#bar-chart", [bar_data], {
                grid: {
                    borderWidth: 1,
                    borderColor: "#f3f3f3",
                    tickColor: "#f3f3f3"
                },
                series: {
                    bars: {
                        show: true,
                        barWidth: 0.5,
                        align: "center"
                    }
                },
                xaxis: {
                    mode: "categories",
                    tickLength: 0
                }
            });
        }
    },"html");

}