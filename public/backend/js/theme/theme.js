$(function () {

    sortTable();
    changeText();
    deleteTheme();

    $('#themeTable').dataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bSort": true,
        "bInfo": true,
        "bAutoWidth": false
    });

    // Add new field name
    $('#addNewField').click(function () {

        var field_name = $('#field_name').val();
        var field_help_image = $('#field_help_image').val();
        var field_type = $('#field_type').val();
        var CSRF_TOKEN = $('#tocken').val();

        if(field_name == ''){
            $('#error_field_name').html('This field is required!');
        }else{
            $('#error_field_name').html('');
        }

        if(field_help_image == ''){
            $('#error_field_help_image').html('This field is required!');
        }else{
            $('#error_field_help_image').html('');
        }

        if(field_name == '' || field_help_image == '') {
            return false;
        }

        var tab_selected = $('.select-tab.active input').val();


        $.ajax({
            url : baseUrl()+"/admin/themes/uploadImage",
            headers: { "X-CSRF-TOKEN": CSRF_TOKEN },
            method: 'post',
            dataType: 'json',
            data: new FormData($("#fieldHelpImage")[0]),
            processData: false,
            contentType: false,
            cache : false,
            success : function(data){
                if(data.status == 1){

                    addNewField(field_name,data.image_name,field_type, tab_selected);
                    $('#field_name').val('');
                    $('#field_help_image').val('');
                    $('#field_type').val('WYSIWYG');
                }else{
                    console.log('Some thing wrong in add new Field!');
                }

            }
        },"html");

    });
    
    // Add new Theme
    $('#saveTheme').click(function () {

        var data_table = GetAllRows();
        var theme_name = $('#name').val();
        var CSRF_TOKEN = $('#tocken').val();
        var is_edit = $('#isEdit').val();

        if(theme_name == ''){
            $('#error_name_temp').html("This field is required!");
            return;
        }else{
            $('#error_name_temp').html('');
        }

        console.log('data_table '+data_table);
        $.ajax({
            url : baseUrl()+"/admin/themes/create",
            headers: { "X-CSRF-TOKEN": CSRF_TOKEN },
            method: 'post',
            dataType: 'json',
            data: {
                name: theme_name,
                theme_field: data_table,
                is_edit: is_edit,
                theme_id: $('#themeId').val()
            },
            cache : false,
            success : function(data){
                if(data.status == 1){
                    // addNewField(field_name,data.image_name,field_type);
                    window.location.href = baseUrl()+"/admin/themes";
                }else{
                    console.log('Some thing wrong in add new Field!');
                }

            }
        },"html");

    });

});

function addNewField(field_name, field_help_image, field_type, tab_selected) {
    var template = '<tr role="row" class="odd row-field">'+
                        '<td>'+field_name+'</td>'+
                        '<td>'+field_help_image+'</td>'+
                        '<td>'+field_type+'</td>'+
                        '<td class="index"></td>'+
                        '<td class="tab_selected hidden">'+tab_selected+'</td>'+
                    '</tr>';

    if(tab_selected == 'promotion'){
        $('#themeFieldPromotion').append(template);
        setTimeout(function(){
            $('td.index', $('#themeFieldPromotion')).each(function(i) {
                $(this).html(i + 1);
            });
        });
    }else{
        $('#themeFieldProduct').append(template);
        setTimeout(function(){
            $('td.index', $('#themeFieldProduct')).each(function(i) {
                $(this).html(i + 1);
            });
        });
    }



}

function sortTable() {

    var fixHelperModified = function(e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function(index) {
                $(this).width($originals.eq(index).width())
            });
            return $helper;
        },
        updateIndex = function(e, ui) {
            $('td.index', ui.item.parent()).each(function(i) {
                $(this).html(i + 1);
            });
        };

    $("#themeFieldDetail tbody").sortable({
        helper: fixHelperModified,
        stop: updateIndex,
    }).disableSelection();
}

function  changeText() {
    $('#name_temp').bind("keyup", function() {
        var val = $(this).val();
        $('#name').val(val);
    });
}

// Read all rows and return an array of objects
function GetAllRows()
{
    var myObjects = [];

    $('#themeFieldDetail tbody tr.row-field').each(function (index, value)
    {
        var row = GetRow(index);
        myObjects.push(row);
    });

    return myObjects;
}

// Read the row into an object
function GetRow(rowNum)
{
    var row = $('#themeFieldDetail tbody tr.row-field').eq(rowNum);

    var myObject = {};

    myObject.field_name = row.find('td:eq(0)').text();
    myObject.field_help_image = row.find('td:eq(1)').text();
    myObject.field_type = row.find('td:eq(2)').text();
    myObject.order = row.find('td:eq(3)').text();
    myObject.theme_field_promo_id = row.find('td.theme_field_promo_id').text();
    myObject.tab_selected = row.find('td.tab_selected').text();

    return myObject;
}

function deleteTheme() {

    $('#deleteTheme').on('show.bs.modal', function (event) {
        var element = $(event.relatedTarget) // Button that triggered the modal
        var themeName = element.data('themename');
        var theme_id = element.data('themeid');

        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('#themeName').text(themeName);
        modal.find('#themeId').val(theme_id);
    });

    $("#deleteTheme").click(function(e){

        var theme_id = $("#themeId").val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url : baseUrl()+"/admin/themes/delete",
            method: 'post',
            dataType: 'json',
            data: {_token: CSRF_TOKEN,'theme_id':theme_id },
            success : function(data){
                if(data.status == 1){
                    $('#deleteTheme').modal('hide');
                    $('#themeRow'+data.theme_id).fadeOut( "slow", function() {
                        // Animation complete.
                    });
                }else{
                    console.log('Some thing wrong in delete Product!');
                }

            }
        },"html");
    });
}