@extends("layout.backend.master")

@section("custom-script")
    <script>
        $(document).ready(function () {
            $('#promotionTable').dataTable({
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
            Promotions
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a type="button" href="/admin/promotions/create" class="btn btn-primary"><i class="fa fa-plus-circle fa-1x
            "></i>&nbsp;&nbsp;Add a new Promotion</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active">Manager Promotion</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box">
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="promotionTable" class="table table-bordered table-striped dataTable"
                                       role="grid"
                                       aria-describedby="example1_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Promotion
                                        </th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Promotion Key
                                        </th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Start Date
                                        </th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">End Date
                                        </th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Status
                                        </th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Edit
                                        </th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Delete
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($promotions))
                                        @foreach($promotions as $key=>$promotion)
                                            <tr role="row" class="odd">
                                                <td><a class="underline"
                                                       href="/admin/promotions/profile/{{$promotion->promotion_id}}">{{$promotion->name}}</a>
                                                </td>
                                                <td>{{$promotion->promotion_key ? $promotion->promotion_key : '' }}</td>
                                                <td>{{$promotion->start_date?date('d/m/Y', strtotime($promotion->start_date)):'' }}</td>
                                                <td>{{$promotion->end_date?date('d/m/Y', strtotime($promotion->end_date)):''}}</td>
                                                <td>{{ $promotion->status}}</td>
                                                <td><a class="underline"
                                                       href="/admin/promotions/edit/{{$promotion->promotion_id}}">Edit</a>
                                                </td>
                                                <td><a class="underline"
                                                       href="/admin/promotions/delete/{{$promotion->promotion_id}}">Delete</a>
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
