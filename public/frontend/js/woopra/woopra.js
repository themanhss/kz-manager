$(function () {

    $(document).on('click','#myRun',function () {
        getAllAction();
    });
    
});

function getAllAction() {

    var appID = 'NVV5BGNMUFCP7NSXI753O7Z3IF6Z2ROL';
    var secretKey = 'sIIJPbIg0FDlOJNCsYWM9tV0Hlwqa8LBcpnT5t3HD0OLRIuznRDZuW83DX3HCFmo';

    $.ajax({
        type: 'POST',
        url: 'https://www.woopra.com/rest/search',
        crossDomain: true,
        dataType: "json",
        jsonp: false,
        cache: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', 'Basic '+btoa(appID+':'+secretKey));
        },
        data: {
            request: JSON.stringify({
                website:'kiza.vn',
                limit:'1000',
                report_id:'-1',
                offset:'0',
                search:'',
                segments:[],
                start_day:'2016-04-26',
                end_day:'2016-04-26',
                date_format:'yyyy-MM-dd',
            })
        },
        success: function (response) {
            // console.log(response.visitors);
            var items = response.visitors;
            // console.log(items);
            saveDB(items);
        }
    });
}

/*
*  Save Data to IAG DB
* */
function saveDB(items) {
    var token = $('#myToken').val();
    $.ajax({
        url: '/woopra/get-all',
        type : 'POST',
        dataType: "JSON",
        // processData: false,
        headers: { "X-CSRF-TOKEN": token },
        data: {
            'items' : items
        },
        success: function(data) {
            console.log('data '+data);
        },
        error: function(data) {

        }
    });
}