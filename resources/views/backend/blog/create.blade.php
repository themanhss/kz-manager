@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            Add New Blog
        </h1>
    </section>
    <div class="create-user">
        <div class="col-md-9 col-md-offset-1">
            <form id="blogsForm" class="form-horizontal" action="/admin/blogs/create" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Domain</label>

                        <div class="col-md-6">
                            <input id="domain" name="domain" type="text" class="form-control"  value="{{ old('domain') }}">
                            <span class="text-danger">{{ $errors->first('domain') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Host</label>

                        <div class="col-md-6">
                            <input id="host" name="host" type="text" class="form-control"
                                   value="{{ old('host') }}">
                            <span class="text-danger">{{ $errors->first('host') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Database</label>

                        <div class="col-md-6">
                            <input id="database" name="database" type="text" class="form-control"
                                   value="{{ old('database') }}">
                            <span class="text-danger">{{ $errors->first('database') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">User Name</label>

                        <div class="col-md-6">
                            <input id="username" name="username" type="text" class="form-control"
                                   value="{{ old('username') }}">
                            <span class="text-danger">{{ $errors->first('username') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Password</label>

                        <div class="col-md-6">
                            <input id="password" name="password" type="text" class="form-control"
                                   value="{{ old('password') }}">
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/blogs" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Create</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@stop
