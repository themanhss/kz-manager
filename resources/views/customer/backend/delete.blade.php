@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            Delete Customer
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/customers">Manager Customer</a></li>
            <li class="active">Delete Customer</li>
        </ol>
    </section>

    <div class="create-user">
        <form class="form-horizontal">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 padding-top-none control-label">Name</label>
                <div class="col-sm-10">
                    <span>{{$customer->first_name.' '.$customer->last_name}}</span>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 padding-top-none control-label">Email</label>
                <div class="col-sm-10">
                    <span>{{$customer->email}}</span>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 padding-top-none control-label">Phone</label>
                <div class="col-sm-10">
                    <span>{{$customer->mobile_phone}}</span>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 padding-top-none control-label">Created Date</label>
                <div class="col-sm-10">
                    <span>{{$customer->created_at}}</span>
                </div>
            </div>

        </form>

        <form id="assetForm" class="form-horizontal" action="/admin/customers/delete/{{$customer->id}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6">
                <h3>Do you want to delete <b>{{$customer->first_name.' '.$customer->last_name}}</b> ?</h3>
                <a href="/admin/customers" type="button" class="btn w-100 btn-primary">No</a>
                <button type="submit" class="btn w-100 btn-success">Yes</button>
            </div>
        </form>
    </div>

@stop
