@extends("layout.backend.master")

@section("custom-script")
    <script>
        $(document).ready(function () {
            $('#customerTableData').dataTable({
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
            Customers
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a type="button" href="/admin/customers/create" class="btn btn-primary"><i class="fa fa-plus-circle fa-1x
            "></i>&nbsp;&nbsp;Add a new Customer</a>&nbsp;&nbsp;
            <a type="button" href="/admin/customers/import" class="btn btn-primary"><i class="fa fa-cloud-upload fa-1x
            "></i> &nbsp;&nbsp;Import Customer</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active">Manager Customer</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box">
                <div class="box-body">
                    <div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="customerTableData" class="table table-bordered table-striped dataTable" role="grid"
                                       aria-describedby="example1_info">
                                    <thead>
                                    <tr role="row">
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">First Name</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Last Name</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Email</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Phone</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">State</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Suburb</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Postcode</th>
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Creation Date</th>
                                        {{--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">Action</th>--}}
                                        <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1">View</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($customers))
                                        @foreach($customers as $key=>$customer)
                                            <tr role="row" class="odd">
                                                <td>{{ str_limit($customer->first_name, $limit = 45, $end = '...') }}</td>
                                                <td>{{ str_limit($customer->last_name, $limit = 45, $end = '...') }}</td>
                                                <td>{{$customer->email}}</td>
                                                <td>{{$customer->mobile_phone}}</td>
                                                <td>{{$customer->state}}</td>
                                                <td>{{$customer->suburb}}</td>
                                                <td>{{ str_limit($customer->postcode, $limit = 45, $end = '...') }}</td>
                                                <td>{{$customer->created_at?date('d/m/Y', strtotime($customer->created_at)):'' }}</td>
                                                <td>
                                                    <a href="{{url('admin/customers/profile',['id'=>$customer->id])}}" class="underline">View</a>
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
