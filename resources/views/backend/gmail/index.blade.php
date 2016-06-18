@extends("layout.backend.master")

@section("custom-script")
    <script>
        $(document).ready(function () {
            $('#userTable').dataTable({
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bAutoWidth": false
            });
        });
    </script>
    @endsection

    @section("content")
            <!-- header-->
    <section class="content-header">
        <h1>
            Gmails &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <a type="button" href="/admin/gmails/create" class="btn btn-primary"><i class="fa fa-user"></i>&nbsp;&nbsp;Add more</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active">Manage Gmails</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box">
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="userTable" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="example1_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Name: activate to sort column
                                            ascending">Gmail
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Name: activate to sort column ascending">
                                            Created Date
                                        </th>
                                        <th>Edit</th>
                                        <th>Remove</th>
                                        <th>Run</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($gmails))
                                        @foreach($gmails as $key=>$gmail)
                                            <tr role="row" class="odd">
                                                <td>
                                                    <a href="gmails/{{$gmail->id}}/blogspots">
                                                        {{$gmail->gmail}}
                                                    </a>
                                                </td>
                                                <td>{{$gmail->start_at}}</td>
                                                <td>
                                                    <a class="" href="{{url('admin/users/edit',['id'=>$gmail->id])}}">
                                                        <button type="button" class="btn btn-primary">Edit</button>
                                                    </a>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger">Remove</button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning">Run</button>
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
            </div>
        </div>
        <!-- /.row -->
    </section>
@stop
