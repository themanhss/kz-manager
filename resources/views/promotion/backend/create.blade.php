@extends("layout.backend.master")

@section("custom-script")
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.date.extensions.js')}}" type="text/javascript"></script>
    <script src="{{ asset('backend/js/promotions/main.js')}}" type="text/javascript"></script>
@endsection

@section("content")
    <section class="content-header">
        <h1>
            CREATE PROMOTION
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/promotions">Promotions</a></li>
            <li class="active"><a href="#">Create Promotion</a></li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-9 col-md-offset-1">
            <form id="promotionForm" class="form-horizontal" action="/admin/promotions/create" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Promo Name</label>
                        <div class="col-md-6">
                            <input id="name" name="name" type="text" class="form-control"
                                   value="{{ old('name') }}">
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Starting Date</label>

                        <div class="col-md-6">
                            <div class="input-group date">
                                <input type="text" name="start_date" value="{{old('start_date')}}" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div><!-- /.input group -->
                            <span class="text-danger">{{ $errors->first('start_date') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">End Date</label>

                        <div class="col-md-6">
                            <div class="input-group date">
                                <input type="text" name="end_date" value="{{old('end_date')}}" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div><!-- /.input group -->
                            <span class="text-danger">{{ $errors->first('end_date') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Promotion Key</label>

                        <div class="col-md-6">
                            <input type="text" name="promotion_key" value="{{old('promotion_key')}}"
                                   class="form-control"></span>
                            <span class="text-danger">{{ $errors->first('promotion_key') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Status</label>

                        <div class="col-md-3">
                            <select class="form-control" id="status" name="status">
                                <option value="Finished">Finished</option>
                                <option value="Ready to launch">Ready to launch</option>
                                <option value="Draft">Draft</option>
                                <option value="Active">Active</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Theme</label>

                        <div class="col-md-3">
                            <select class="form-control" id="theme_id" name="theme_id">
                                <option value='0'>Select Theme</option>
                                @if(!empty($themes))
                                    @foreach($themes as $key=>$theme)
                                        <option value="{{$theme->theme_id}}">{{$theme->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Description</label>

                        <div class="col-md-6">
                            <textarea id="description" rows="10" name="description" type="text" class="form-control" value="{{old('description') }}">{{old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/promotions" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Save</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@stop
