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
            Admin Users &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <a type="button" href="/admin/users/create" class="btn btn-primary"><i class="fa fa-user"></i>&nbsp;&nbsp;Add a new admin user</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class="active">Manage User</li>
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
                                        {{--<th class="sorting_asc" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-sort="ascending"
                                            aria-label="ID: activate to sort column descending">ID
                                        </th>--}}
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Name: activate to sort column
                                            ascending">First Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Name: activate to sort column ascending">Last
                                            Name
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Email: activate to sort column ascending">Email
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                            colspan="1" aria-label="Activated: activate to sort column ascending">
                                            Activated
                                        </th>
                                        @if(Auth::user()->isSuperAdmin == 1)
                                            <th class="sorting" tabindex="0" aria-controls="example1" rowspan="1"
                                                colspan="1" aria-label="Activated: activate to sort column ascending">
                                                Is Super Admin
                                            </th>
                                        @endif
                                        {{--<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Blocked: activate to sort column ascending">Blocked</th>--}}
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(!empty($users))
                                        @foreach($users as $key=>$user)
                                            <tr role="row" class="odd">
                                                {{--<td class="sorting_1">{{$user->id}}</td>--}}
                                                <td>{{$user->firstName}}</td>
                                                <td>{{$user->lastName}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>
                                                    <span class="label {{($user->isActive == 1)?'label-success':'label-warning'}}">{{($user->isActive == 1)?'Activated':'Pending'}}</span>
                                                </td>
                                                @if(Auth::user()->isSuperAdmin == 1)
                                                    @if($user->isSuperAdmin == 1)
                                                        <td>
                                                            <span class="label label-success">Super Admin</span>
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif

                                                @endif
                                                {{--                                        <td><span class="label {{($user->blocked == 0)?'label-primary':'label-danger'}}">{{($user->blocked == 0)?'No':'Yes'}}</span></td>--}}
                                                <td><a class="underline" href="{{url('admin/users/edit',['id'=>$user->id])}}">Edit</a></td>
                                                <td>
                                                    @if($user->isSuperAdmin == 0)
                                                        <a class="underline" href="{{url('admin/users/delete',['id'=>$user->id])}}">Delete</a>
                                                    @else
                                                        <a href="#" class="underline">Delete</a>
                                                    @endif
                                                </td>
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
