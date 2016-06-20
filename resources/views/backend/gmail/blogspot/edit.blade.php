@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            Edit BlogSpot
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/users">Manager User</a></li>
            <li class="active">Create User</li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-9 col-md-offset-1">
            <form id="assetForm" class="form-horizontal" action="/admin/gmails/{{$gmail_id}}/blogspot/create" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Url</label>

                        <div class="col-md-6">
                            <input id="url" name="url" type="text" class="form-control"
                                   value="{{ old('url') }}">
                            <span class="text-danger">{{ $errors->first('url') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="blog_id">Blog ID</label>

                        <div class="col-md-6">
                            <input id="blog_id" name="blog_id" type="text" class="form-control"
                                   value="{{ old('blog_id') }}">
                            <span class="text-danger">{{ $errors->first('blog_id') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Description</label>

                        <div class="col-md-6">
                            <textarea id="description" name="description" value="{{ old('description') }}" class="form-control">{{ old('description') }}</textarea>
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/gmails/{{$gmail_id}}/blogspots" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Save</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@stop
