@extends("layout.backend.master")

@section("custom-script")
    <script src="{{ asset('backend/js/theme/theme.js')}}" type="text/javascript"></script>
@endsection

@section("content")
    <section class="content-header">
        <h1>
            Create Theme
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/themes">Manager Themes</a></li>
            <li class="active">Create Theme</li>
        </ol>
    </section>
    <div class="create-user">

        <div class="col-md-12 ">
            <fieldset class="mb20">
                <div class="form-group">
                    <label class="col-md-2 control-label" for="assetType">Name</label>

                    <div class="col-md-4">
                        <input id="name_temp" name="name" type="text" class="form-control"
                               value="{{ old('name') }}">
                        <span class="text-danger" id="error_name_temp">{{ $errors->first('name_temp') }}</span>
                    </div>
                    <input type="hidden" value="0" id="isEdit">

                </div>
            </fieldset>
            <form id="fieldHelpImage" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="tocken" value="{{ csrf_token() }}">
                <div class="box-add">
                <div class="form-group">
                    <label class="col-md-1 control-label" for="assetType">Field Name</label>
                    <div class="col-md-3">
                        <input id="field_name" name="field_name" type="text" class="form-control"
                               value="{{ old('field_name') }}">
                        <span class="text-danger" id="error_field_name">{{ $errors->first('field_name') }}</span>
                    </div>

                    <label class="col-md-1 control-label" for="assetType">Image</label>
                    <div class="col-md-3">
                        <input id="field_help_image" name="field_help_image" type="file" class="form-control"
                               value="{{ old('field_help_image') }}">
                        <span class="text-danger" id="error_field_help_image">{{ $errors->first('field_help_image') }}</span>
                    </div>
                    <div class="col-md-1">
                        <a id="addNewField" class="btn btn-primary">Add</a>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-1 control-label" for="assetType">Field Type</label>
                    <div class="col-md-3">
                        <select name="field_type" id="field_type" class="form-control">
                            <option value="WYSIWYG">WYSIWYG</option>
                            <option value="Text Field">Text Field</option>
                            <option value="Date Field">Date Field</option>
                            <option value="File Upload">File Upload</option>
                        </select>
                    </div>
                </div>
            </div>
            </form>
            <form id="assetForm" class="form-horizontal" action="/admin/theme/create" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="_token" id="tocken" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group hidden">
                        <label class="col-md-2 control-label" for="assetType">Name</label>

                        <div class="col-md-4">
                            <input id="name" name="name" type="text" class="form-control"
                                   value="{{ old('name') }}">
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>



                    <div class="box">
                        <div class="box-body">
                            <div id="themeFieldDetail" class="dataTables_wrapper form-inline dt-bootstrap">

                                <!-- Custom Tabs -->
                                <div class="nav-tabs-custom">
                                    <ul class="nav nav-tabs">
                                        <li class="select-tab active">
                                            <a href="#tab_1" data-toggle="tab">PROMOTION</a>
                                            <input type="hidden" class="flag" value="promotion">
                                        </li>
                                        <li class="select-tab">
                                            <a href="#tab_2" data-toggle="tab">PRODUCT</a>
                                            <input type="hidden" class="flag" value="product">
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab_1">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="dataTablePromotion" class="table table-bordered table-striped dataTable" role="grid"
                                                           aria-describedby="example1_info">
                                                        <thead>
                                                        <tr role="row">
                                                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1">Field</th>
                                                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1">Image</th>
                                                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1">Field Type</th>
                                                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1">Order</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="themeFieldPromotion">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div><!-- /.tab-pane -->
                                        <div class="tab-pane" id="tab_2">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="dataTableProduct" class="table table-bordered table-striped dataTable" role="grid"
                                                           aria-describedby="example1_info">
                                                        <thead>
                                                        <tr role="row">
                                                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1">Field</th>
                                                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1">Image</th>
                                                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1">Field Type</th>
                                                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                                                colspan="1">Order</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="themeFieldProduct">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div><!-- /.tab-pane -->
                                    </div><!-- /.tab-content -->
                                </div><!-- nav-tabs-custom -->

                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/themes" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <a id="saveTheme" class="btn btn-primary btn-create btn-save btn-sm">Save</a>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@stop
