$(function () {
    var numberOfField = 0;
    $('#dataTable').dataTable({
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bSort": false,
        "bInfo": true,
        "bAutoWidth": true
    });
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    //CKEDITOR.replace('editor-one');
    //bootstrap WYSIHTML5 - text editor
    $(".textarea").wysihtml5();
    //Ajax delete item    
    DeleteItem = function (id) {
        var baseUrl = document.location.href;
        baseUrl = baseUrl.replace('#', '');
        $.ajax({
            url: baseUrl + '/delete/' + id,
            dataType: 'json',
            beforeSend: function () {
                if (!confirm("Are you want to delete this item?")) {
                    return false;
                }
            },
            success: function (result) {
                if (result.status == true) {
                    window.location.reload();
                }
            }
        });
    };
    //Add new field in generate module
    $('.default-field .modal').modal('hide');
    $("#add-field").click(function () {
        $('.default-field .modal').modal('show');
    });
    //Table has no pagination
    $('#dataTableNoPagination').dataTable({
        "bPaginate": false,
        "bLengthChange": false,
        "bFilter": false,
        "bSort": true,
        "bInfo": false,
        "bAutoWidth": true
    });
    //Save field info
    $("#save-field-btn").click(function () {
        numberOfField++;
        var fieldName = $("#field-name").val();
        var fieldType = $("#field-type").val();
        var fieldRequired = $("#field-required").val();
        var fieldLength = $("#field-length").val();
        var fieldData = fieldName + '@' + fieldType + '@' + fieldLength + '@' + fieldRequired;
        var htmlString = '<tr>';
        htmlString += '<td>' + numberOfField + '</td>';
        htmlString += '<td>' + fieldName + '</td>';
        htmlString += '<td>' + fieldType + '</td>';
        htmlString += '<td>' + fieldRequired + '</td>';
        htmlString += '<td><button type="button" class="btn btn-block btn-danger remove-field">Remove</button></td>';
        htmlString += '<input type="hidden" name="fieldData[]" value="' + fieldData + '"/><input type="hidden" name="fieldName[]" value="' + fieldName + '"/></tr>';
        $(".dataTables_empty").remove();
        $("#body-table").append(htmlString);
        $(".remove-field").click(function () {
            $(this).parent().parent().remove();
            numberOfField--;
        });
    });
    //Check field type
    $("#field-type").change(function () {
        var fieldType = $("#field-type").val();
        switch (fieldType) {
            case "int":
                $('#field-length').parent().removeClass('hide');
                $('#field-primary').parent().removeClass('hide');
                break;
            case "float" || "varchar" || "real":
                $('#field-length').parent().removeClass('hide');                
                break;
            default:
                $('#field-length').parent().removeClass('hide');
                $('#field-primary').parent().removeClass('hide');
                $('#field-increment').parent().addClass('hide');
                break;
        }
        $('#field-primary').change(function () {
            if ($(this).val() == '1') {
                $('#field-increment').parent().removeClass('hide');
            }else{
                $('#field-increment').parent().addClass('hide');
            }
        });
    });
    
    /*
     * Check has relationship option
     */
    $('.relation-module').hide();
    $('.relation-field').hide();
    
    $('#relation-option').change(function(){
        var relationValue = $(this).val();
        
        if(relationValue == 1){
            $('.relation-module').show();
            $('.relation-field').show();
        }else{
            $('.relation-module').hide();
            $('.relation-field').hide();
        }
    });
});
<!-- Define base url -->
function baseUrl(){
    pathArray = location.href.split( '/' );
    protocol = pathArray[0];
    host = pathArray[2];
    url = protocol + '//' + host;
    return url;
}