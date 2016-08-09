@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            Add New Gmail
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/users">Manager User</a></li>
            <li class="active">Create User</li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-9 col-md-offset-1">
            <form id="assetForm" class="form-horizontal" action="/admin/gmails/create" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Gmail</label>

                        <div class="col-md-6">
                            <input id="gmail" name="gmail" type="text" class="form-control"
                                   value="{{ old('gmail') }}">
                            <span class="text-danger">{{ $errors->first('gmail') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">PW</label>

                        <div class="col-md-6">
                            <input id="pw" name="pw" type="text" class="form-control"
                                   value="{{ old('pw') }}">
                            <span class="text-danger">{{ $errors->first('pw') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Phone</label>

                        <div class="col-md-6">
                            <input id="phone" name="phone" type="text" class="form-control"
                                   value="{{ old('phone') }}">
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Kiểu Hỗ Trợ</label>

                        <div class="col-md-6">
                            <select class="form-control" id="type" name="type">
                                <option value="1">Hỗ trợ site chính</option>
                                <option value="0">Hỗ trợ site vệ tinh</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Client Key</label>

                        <div class="col-md-4">
                            <input id="client_key" name="client_key" type="file" class="form-control" value="">
                            <span class="text-danger">{{ $errors->first('client_key') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Email Back Up</label>

                        <div class="col-md-6">
                            <input id="email_backup" name="email_backup" type="text" class="form-control" value="{{ old('email_backup') }}">
                            <span class="text-danger">{{ $errors->first('email_backup') }}</span>
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
                            <a href="/admin/gmails" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Save</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@stop
