@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            Create User
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/users">Manager User</a></li>
            <li class="active">Create User</li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-9 col-md-offset-1">
            <form id="assetForm" class="form-horizontal" action="/admin/users/create" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">First Name</label>

                        <div class="col-md-6">
                            <input id="firstName" name="firstName" type="text" class="form-control"
                                   value="{{ old('firstName') }}">
                            <span class="text-danger">{{ $errors->first('firstName') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Last Name</label>

                        <div class="col-md-6">
                            <input id="lastName" name="lastName" type="text" class="form-control"
                                   value="{{ old('lastName') }}">
                            <span class="text-danger">{{ $errors->first('lastName') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Avatar</label>

                        <div class="col-md-4">
                            <input id="avatar" name="avatar" type="file" class="form-control" value="">
                            <span class="text-danger">{{ $errors->first('avatar') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Email</label>

                        <div class="col-md-6">
                            <input id="email" name="email" type="text" class="form-control" value="{{ old('email') }}">
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Status</label>

                        <div class="col-md-3">
                            <select class="form-control" id="isActive" name="isActive">
                                <option value="1">Active</option>
                                <option value="0">Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Password</label>

                        <div class="col-md-6">
                            <input id="newPassword" name="newPassword" type="password" class="form-control"
                                   value="{{ old('newPassword') }}">
                            <span class="text-danger">{{ $errors->first('newPassword') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Re-type password</label>

                        <div class="col-md-6">
                            <input id="confirmPass" name="confirmPass" type="password" class="form-control"
                                   value="{{ old('confirmPass') }}">
                            <span class="text-danger">{{ $errors->first('confirmPass') }}</span>
                        </div>
                    </div>
                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/users" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Save</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@stop
