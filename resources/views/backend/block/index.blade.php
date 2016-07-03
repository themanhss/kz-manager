@extends("layout.backend.master")

@section("custom-script")
    <script>
        $(document).ready(function () {
            $('#userTable').dataTable({
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "bPaginate": true,
                "pageLength": 25,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": false,
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
            Block &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <a type="button" href="/admin/blocks/create" class="btn btn-primary"><i class="fa fa-user"></i>&nbsp;&nbsp;Add more Block</a>
        </h1>
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
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        <th>Run</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($blocks))
                                        @foreach($blocks as $key=>$block)
                                            <tr role="row" class="odd">
                                                <td>
                                                    <a href="blocks/{{$block->id}}/detail">
                                                        {{$block->name}}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($block->status == 1)
                                                        <button type="button" class="btn btn-block btn-success btn-sm">Active</button>
                                                    @else
                                                        <button type="button" class="btn btn-block btn-default btn-sm">Pending</button>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="/admin/blocks/{{$block->id}}/edit">
                                                        <button type="button" class="btn btn-block btn-sm btn-info">Edit</button>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="/admin/blocks/{{$block->id}}/delete">
                                                        <button type="button" class="btn btn-block btn-sm btn-danger">Delete</button>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a target="_blank" href="/admin/blocks/{{$block->id}}/run">
                                                        <button type="button" class="btn btn-block btn-sm btn-primary">Run</button>
                                                    </a>
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
