@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            Add New Block
        </h1>
    </section>
    <div class="create-user">
        <div class="col-md-9 col-md-offset-1">
            <form id="blogsForm" class="form-horizontal" action="/admin/blocks/create" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Name</label>

                        <div class="col-md-6">
                            <input id="name" name="name" type="text" class="form-control"  value="{{ old('name') }}">
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Status</label>

                        <div class="col-md-3">
                            <select class="form-control" id="status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Pending</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">URL</label>

                        <div class="col-md-6">
                            <input id="url" name="url" type="text" class="form-control"
                                   value="{{ old('url') }}">
                            <span class="text-danger">{{ $errors->first('url') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Block Item News <i>(Element)</i></label>

                        <div class="col-md-6">
                            <input id="list_li" name="list_li" type="text" class="form-control"
                                   value="{{ old('list_li') }}">
                            <span class="text-danger">{{ $errors->first('list_li') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Link To Detail <i>(ahreft)</i></label>

                        <div class="col-md-6">
                            <input id="detail_a" name="detail_a" type="text" class="form-control"
                                   value="{{ old('detail_a') }}">
                            <span class="text-danger">{{ $errors->first('detail_a') }}</span>
                        </div>
                    </div>
                    <div class="detail-link">
                        <h3>Detail Link</h3>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Post Title <i>(element)</i></label>

                        <div class="col-md-6">
                            <input id="title" name="title" type="text" class="form-control"
                                   value="{{ old('title') }}">
                            <span class="text-danger">{{ $errors->first('title') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Post Content <i>(element)</i></label>

                        <div class="col-md-6">
                            <input id="content" name="content" type="text" class="form-control"
                                   value="{{ old('content') }}">
                            <span class="text-danger">{{ $errors->first('content') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label">Post Content Remove <i>(eg: .social,.tag)</i></label>

                        <div class="col-md-6">
                            <input id="delete_item" name="delete_item" type="text" class="form-control"
                                   value="{{ old('delete_item') }}">
                            <span class="text-danger">{{ $errors->first('delete_item') }}</span>
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/blocks" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Create</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@stop
