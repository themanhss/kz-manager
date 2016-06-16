@extends("layout.backend.master")

@section("custom-script")
    <script src="{{ asset('backend/js/theme/theme.js')}}" type="text/javascript"></script>
@endsection


@section("content")
    <!-- header-->
    <section class="content-header">
        <h1>
            Themes
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a type="button" href="/admin/themes/create" class="btn btn-primary"><i class="fa fa-plus-circle fa-1x
            "></i>&nbsp;&nbsp;Add a new Themes</a>&nbsp;&nbsp;
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active">Manager Themes</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box">
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="themeTable" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="example1_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Name</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Creation Date</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Pricing</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Edit</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($themes))
                                        @foreach($themes as $key=>$theme)
                                            <tr role="row" class="odd" id="themeRow{{$theme->theme_id}}">
                                                <td>{{$theme->name}}</td>
                                                <td>{{$theme->created_at?date('d/m/Y', strtotime($theme->created_at)):'' }}</td>
                                                <td>
                                                    <a href="themes/{{$theme->theme_id}}/pricing" class="underline uppercase">Edit Pricing</a>
                                                </td>
                                                <td>
                                                    <a href="{{url('admin/themes/edit',['id'=>$theme->theme_id])}}" class="underline uppercase">Edit</a>
                                                </td>
                                                <td>
                                                    <a href="#" data-themename="{{$theme->name}}" data-themeid="{{$theme->theme_id}}" data-toggle="modal" data-target="#deleteTheme" class="underline uppercase">Delete</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-5">
                                <!-- The div for table info-->
                                <!--<div class="dataTables_info" id="example1_info" role="status" aria-live="polite">Showing 1 to 10 of 57 entries</div>-->
                            </div>
                            <div class="col-sm-7">
                                <!-- The div for pagination-->
                                <div class="dataTables_paginate paging_simple_numbers" id="example1_paginate"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <!-- Confirm delete promotion theme -->
                <div class="modal fade" id="deleteTheme" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">IAG</h4>
                            </div>
                            <div class="modal-body">
                                Do you want to delete <b>"<span id="themeName"></span>"</b> ?
                            </div>
                            <div class="modal-footer">
                                <meta name="csrf-token" content="{{ csrf_token() }}" />
                                <input type="hidden" name="theme_id" value="" id="themeId"/>
                                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                                <button type="button" id="deleteTheme" class="btn btn-primary">Yes</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- /.row -->
    </section>
@stop
