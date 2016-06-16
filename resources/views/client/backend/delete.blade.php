@extends("layout.backend.master")

@section("content")

    <div class="create-user">
        <h3>DELETE ADMIN USER</h3>

        <form class="form-horizontal">
            <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 padding-top-none control-label">Name</label>
                <div class="col-sm-10">
                    <span>{{$user->firstName.' '.$user->lastName}}</span>
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 padding-top-none control-label">Email</label>
                <div class="col-sm-10">
                    <span>{{$user->email}}</span>
                </div>
            </div>

            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 padding-top-none control-label">Avatar</label>
                <div class="col-sm-10">
                    <a href="#" class="thumbnail" style="width: 200px">
                        <img src="{{$user->avatar? '/uploads/avatar/'.$user->avatar : 'http://placehold.it/200x200'}}" alt="">
                    </a>
                </div>
            </div>

        </form>

        <form id="assetForm" class="form-horizontal" action="/admin/users/delete/{{$user->id}}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="col-md-6">
                <h3>Do you want to delete <b>{{$user->firstName.' '.$user->lastName}}</b> ?</h3>
                <a href="/admin/users" type="button" class="btn btn-primary">No</a>
                <button type="submit" class="btn btn-success">Yes</button>
            </div>
        </form>
    </div>

@stop
