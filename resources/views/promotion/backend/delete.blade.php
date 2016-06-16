@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            DELETE PROMOTION
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/promotions">Promotions</a></li>
            <li class="active"><a href="#">Delete Promotion</a></li>
        </ol>
    </section>
    <div class="create-user">
        <form class="form-horizontal hidden">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 padding-top-none control-label">Name</label>
                <div class="col-sm-10">
                    <span>{{$promotion->name}}</span>
                </div>
            </div>
        </form>

        <form id="assetForm" class="form-horizontal" action="/admin/promotions/delete/{{$promotion->promotion_id}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6">
                <h3>Do you want to delete <b>{{$promotion->name}}</b> ?</h3>
                <a href="/admin/promotions" type="button" class="btn w-100 btn-primary">No</a>
                <button type="submit" class="btn w-100 btn-success">Yes</button>
            </div>
        </form>
    </div>

@stop
