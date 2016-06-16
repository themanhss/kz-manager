$(function () {

    eventClickCheckbox();
    sentSMS();

    $("[data-mask]").inputmask();
    $(document).ready(function () {
        var campaign_id = $('#campaign_id').val();
        $('#reservationtime').daterangepicker(
            {
                // locale: {
                //     format: 'DD-MM-YYYY'
                // },
                // startDate: '01-01-1970',
                // endDate: '01-02-2001'
            },
            function(start, end, label) {
                // alert("A new date range was chosen: " + start.format('YYYY-MM-DD hh:mm:ss') + ' to ' + end.format('YYYY-MM-DD hh:mm:ss'));
                var state_selected = $('#state').val();
                getDataFilter(state_selected, start.format('YYYY-MM-DD hh:mm:ss'), end.format('YYYY-MM-DD hh:mm:ss'), campaign_id);
            });

        $( "#state" ).change(function(e) {
            var state_selected = $('#state').val();
            getDataFilter(state_selected,null,null, campaign_id);
        });

    });

});

var table = $('#customerData').DataTable({
    'columnDefs': [{
        'targets': 0,
        'searchable': false,
        'orderable': false,
        'className': 'dt-body-center'
    }]
});



function sentSMS() {
    $('#sentSMS').click(function (e) {
        // Iterate over all checkboxes in the table
        table.$('input[type="checkbox"]').each(function(){
            // If checkbox doesn't exist in DOM
            if(!$.contains(document, this)){
                // If checkbox is checked
                if(this.checked){
                   console.log(this);
                }
            }else{
                console.log(this);
            }
        });

    });
}

function eventClickCheckbox() {
    // Handle click on "Select all" control
    $(document).on('click','#example-select-all',function () {
        // Get all rows with search applied
        var rows = table.rows({ 'search': 'applied' }).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    // Handle click on checkbox to set state of "Select all" control
    $('#customerData tbody').on('change', 'input[type="checkbox"]', function(){
        // If checkbox is not checked
        if(!this.checked){
            var el = $('#example-select-all').get(0);
            // If "Select all" control is checked and has 'indeterminate' property
            if(el && el.checked && ('indeterminate' in el)){
                // Set visual state of "Select all" control
                // as 'indeterminate'
                el.indeterminate = true;
            }
        }
    });
}

function getDataFilter(state_selected, start_date, end_date, campaign_id) {

    var with_campaign = '';
    var columns = [
        { "data": "first_name" },
        { "data": "last_name" },
        { "data": "email" },
        { "data": "mobile_phone" },
        { "data": "state" },
        { "data": "suburb" },
        { "data": "postcode" },
        { "data": "created_at" }
    ];

    if(campaign_id !== undefined)
        with_campaign = '&campaign_id='+campaign_id;
    else
        columns.unshift({"data": "id"});

    table = $('#customerData').DataTable( {
        'paging': false,
        'searching': false,
        "destroy": true,
        "ajax": '/admin/get-customer?state='+state_selected+'&start_date='+start_date+'&end_date=' +end_date + with_campaign,
        "columns": columns,
        'columnDefs': [{
            'targets': 0,
            'searchable':false,
            'orderable':false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta){
                if(campaign_id !== undefined)
                    return data;

                return '<input type="checkbox" name="id[]" value="'+ $('<div/>').text(data).html() + '">';
            }
        }],
        "initComplete": function(settings) {
            if(campaign_id !== undefined)
                return;
            $('#example-select-all').prop('checked',true);
            var rows = table.rows({ 'search': 'applied' }).nodes();
            // Check/uncheck checkboxes for all rows in the table
            $('input[type="checkbox"]', rows).prop('checked', true);
        }
    } );

}


