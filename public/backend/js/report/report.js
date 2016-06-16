$(document).ready(function () {
    $('#enquiriesTable, #campaignTable, #promotionsTable').dataTable({
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": false
    });

    $('#customersTable').dataTable({
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        "order": [[ 3, "desc" ]],
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": false
    });
});


var CSRF_TOKEN = $('#tocken').val();

$.ajax({
    url : baseUrl()+"/admin/reports/pie-chart",
    headers: { "X-CSRF-TOKEN": CSRF_TOKEN },
    method: 'post',
    dataType: 'json',
    data: {},
    success : function(datas){
        datas =  datas.data;
//                datas =  JSON.stringify(datas.data);

//                console.log(datas);
        var donutData = [];
        for(var i =0; i< datas.length; i++){
            var temp = {
                label: datas[i].name,
                data: datas[i].total_leads,
                color: '#'+(0x1000000+(Math.random())*0xffffff).toString(16).substr(1,6)
            }

            donutData.push(temp);

        }

        setTimeout(function () {
            $.plot("#donut-chart", donutData, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        innerRadius: 0.5,
                        label: {
                            show: true,
                            radius: 2 / 3,
                            formatter: labelFormatter,
                            threshold: 0.1
                        }

                    }
                },
                legend: {
                    show: false
                }
            });
        },0);


    }
},"html");



/*
 * Custom Label formatter
 * ----------------------
 */
function labelFormatter(label, series) {
    return "<div style='font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;'>"
        + label
        + "<br/>"
        + Math.round(series.percent) + "%</div>";
}

