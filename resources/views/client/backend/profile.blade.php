@extends("layout.backend.master")

@section("content")
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Admin <small>User Management</small>
            </h1>
            <ol class="breadcrumb">
                <li class="active">
                    <i class="fa fa-dashboard"></i> User Profile
                </li>
            </ol>
        </div>
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info alert-dismissable">
                <div class="quote">{{ Inspiring::quote() }}</div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    
@stop
