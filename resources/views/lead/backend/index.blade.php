@extends("layout.backend.master")

@section("custom-script")
    <script>
        $(document).ready(function () {
            $('#leadsTable').dataTable({
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
            Promotion Leads &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a type="button" href="#" class="btn btn-primary"><i class="fa fa-user"></i>&nbsp;&nbsp;Add New Lead</a>
        </h1>

        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active">Manage Lead</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box">
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="leadsTable" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="example1_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" >Promotion
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Client
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Communication Type
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" >
                                            Email Address
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" >
                                            Phone Number
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" >
                                            Date
                                        </th>
                                        <th>View Detail</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($leads))
                                        @foreach($leads as $key=>$lead)
                                            <tr role="row" class="odd">
                                                <td>
                                                    {{$lead->promotion?$lead->promotion->name: ''}}
                                                </td>
                                                <td>{{$lead->customer->first_name.' '.$lead->customer->last_name}}</td>
                                                <td>{{$lead->source_type}}</td>
                                                <td>{{$lead->email}}</td>
                                                <td>{{$lead->phone}}</td>
                                                <td>{{$lead->date?date('d/m/Y', strtotime($lead->date)):'' }}</td>
                                                <td><a href="{{ route('promotion.profile', ['id' => $lead->promotion->promotion_id]) }}" class="underline">VIEW</a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot class="hidden">
                                    <tr>
                                        <th rowspan="1" colspan="1">ID</th>
                                        <th rowspan="1" colspan="1">Name</th>
                                        <th rowspan="1" colspan="1">Email</th>
                                        <th rowspan="1" colspan="1">Activated</th>
                                        <th rowspan="1" colspan="1">Blocked</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                    </tfoot>
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
