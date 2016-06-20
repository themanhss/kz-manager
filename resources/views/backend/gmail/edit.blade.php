@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            Edit Gmail Info
        </h1>
    </section>
    <div class="create-user">
        <div class="col-md-9 col-md-offset-1">
            <form id="assetForm" class="form-horizontal" action="/admin/gmails/{{$gmail->id}}/edit" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Gmail</label>

                        <div class="col-md-6">
                            <input id="gmail" name="gmail" type="text" class="form-control"
                                   value="{{$gmail->gmail}}">
                            <span class="text-danger">{{ $errors->first('gmail') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Phone</label>

                        <div class="col-md-6">
                            <input id="phone" name="phone" type="text" class="form-control"
                                   value="{{ $gmail->phone }}">
                            <span class="text-danger">{{ $errors->first('phone') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Client Key</label>

                        <div class="col-md-4">
                            <input id="client_key" name="client_key" type="file" class="form-control" value="{{$gmail->client_key}}">
                            <span class="text-danger">{{ $errors->first('client_key') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Email Back Up</label>

                        <div class="col-md-6">
                            <input id="email_backup" name="email_backup" type="text" class="form-control" value="{{$gmail->email_backup}}">
                            <span class="text-danger">{{ $errors->first('email_backup') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Description</label>

                        <div class="col-md-6">
                            <textarea id="description" name="description" value="{{ $gmail->description }}" class="form-control">{{ $gmail->description }}</textarea>
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
