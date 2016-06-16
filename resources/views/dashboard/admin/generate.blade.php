@extends("layout.backend.master")

@section("content")
<!-- header-->
<section class="content-header">
    <h1>
        Admin
        <small>Generator</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class="active">Generator</li>
    </ol>
</section>
<!-- content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->            
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Create Module</h3>
                </div><!-- /.box-header -->
                <div class='box-body pad create-by-ui'>
                    <!-- form start -->
                    <form role="form" method="post" action="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group">
                            <label>Module Name</label>
                            <input type="text" class="form-control" id="module-name" name="module-name" placeholder="Enter module name" value="">
                        </div>
                        <div class="clearfix"></div>
                        <button type="button" class="btn btn-info" id="add-field">Add field</button>                        
                        <div class="clearfix"></div>
                        <div id="field-list">
                             <table id="dataTableNoPagination" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: activate to sort column descending">ID</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending">Field name</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Description: activate to sort column ascending">Field type</th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Created date: activate to sort column ascending">Required?</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody id="body-table">                                                                        
                                                                      
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">ID</th>
                                        <th rowspan="1" colspan="1">Field name</th>
                                        <th rowspan="1" colspan="1">Field type</th>                                        
                                        <th rowspan="1" colspan="1">Required?</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="default-field">
                <div class="modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                <input type="hidden" class="form-control" id="indexOfField" placeholder="Enter module name">
                                <h4 class="modal-title">Create Field</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Field Name</label>
                                    <input type="text" class="form-control" id="field-name" placeholder="Enter field name">
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label>Field Type</label>
                                    <select class="form-control" id="field-type"> 
                                        <option value="">Please select</option>
                                        <option value="int">Integer</option>
                                        <option value="tinyint">Tiny Integer</option>
                                        <option value="blob">Blob</option>
                                        <option value="float">Float</option>
                                        <option value="real">Real</option>
                                        <option value="varchar">Varchar</option>
                                        <option value="text">Text</option>
                                        <option value="mediumtext">Medium Text</option>
                                        <option value="longtext">Long Text</option>
                                        <option value="date">Date</option>
                                        <option value="datetime">Date Time</option>                                        
                                        <option value="timestamp">Timestamp</option>
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group hide">
                                    <label>Field Length</label>
                                    <input type="text" class="form-control" id="field-length" placeholder="Enter field length">
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group hide">
                                    <label>Primary Key</label>
                                    <select class="form-control" id="field-primary">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>                                        
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group hide">
                                    <label>Auto Increment</label>
                                    <select class="form-control" id="field-increment">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>                                        
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label>Field Required</label>
                                    <select class="form-control" id="field-required">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>                                        
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label>Allow Null</label>
                                    <select class="form-control" id="field-required">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>                                        
                                    </select>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label>Default Value</label>
                                    <select class="form-control" id="field-required">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>                                        
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                                <button id="save-field-btn" type="button" class="btn btn-primary">Add</button>
                            </div> 
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
            </div>
        </div><!-- /.row -->
</section>
@stop
