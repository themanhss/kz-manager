@extends("layout.backend.master")

@section("custom-script")

@endsection

@section("content")
    <section class="content-header">
        <h1>
            Create Product
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/promotions">Promotions</a></li>
            <li class="active">Create Product</li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-12 form-horizontal ">
            <form id="assetForm" class="form-horizontal"
                  action="/admin/promotions/{{$promotion->promotion_id}}/product/create" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>

                    <div class="form-group">
                        <label class="col-md-1 control-label" for="assetType">Product</label>

                        <div class="col-md-9">
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}">
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-1 control-label" for="assetType">Product Key</label>

                        <div class="col-md-2">
                            <input id="name" name="product_key" type="text" class="form-control" value="{{ old('product_key') }}">
                            <span class="text-danger">{{ $errors->first('product_key') }}</span>
                        </div>
                    </div>


                    <div class="form-group">
                        {{--<label class="col-md-1 control-label" for="assetType">Price</label>

                        <div class="col-md-6">
                            <input id="price" name="price" type="text" class="form-control" value="{{ old('price') }}">
                            <span class="text-danger">{{ $errors->first('price') }}</span>
                        </div>--}}

                        <label class="col-md-1 control-label" for="assetType">Status</label>

                        <div class="col-md-2">
                            <select class="form-control" id="status" name="status">
                                <option value="1">ACTIVE</option>
                                <option value="0">PENDING</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-1 control-label" for="assetType">Description</label>

                        <div class="col-md-9">
                            <textarea id="description" rows="10" name="description" type="text" class="form-control"
                                   value="{{ old('description') }}">{{ old('description') }}</textarea>
                            <span class="text-danger">{{ $errors->first('description') }}</span>
                        </div>



                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/promotions/profile/{{$promotion->promotion_id}}" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Save</button>
                        </div>
                    </div>

                </fieldset>
            </form>
        </div>
    </div>
@stop
