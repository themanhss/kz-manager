@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            Edit Customer
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/customers">Manager Customer</a></li>
            <li class="active">Edit Customer</li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-9 col-md-offset-1">
            <form id="assetForm" class="form-horizontal" action="/admin/customers/edit/{{$customer->id}}" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">First Name</label>

                        <div class="col-md-6">
                            <input id="first_name" name="first_name" type="text" class="form-control"
                                   value="{{ $customer->first_name }}">
                            <span class="text-danger">{{ $errors->first('first_name') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Last Name</label>

                        <div class="col-md-6">
                            <input id="lastName" name="last_name" type="text" class="form-control"
                                   value="{{ $customer->last_name }}">
                            <span class="text-danger">{{ $errors->first('last_name') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Email</label>

                        <div class="col-md-6">
                            <input id="email" name="email" type="text" class="form-control" value="{{ $customer->email }}">
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Phone</label>

                        <div class="col-md-6">
                            <input id="mobile_phone" name="mobile_phone" type="text" class="form-control" value="{{ $customer->mobile_phone }}">
                            <span class="text-danger">{{ $errors->first('mobile_phone') }}</span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">State</label>

                        <div class="col-md-5">
                            <select class="form-control" id="state" name="state">
                                <option value="AUSTRALIAN CAPITAL TERRITORY" {{ $customer->state == "AUSTRALIAN CAPITAL TERRITORY" ? 'selected' : '' }}>AUSTRALIAN CAPITAL TERRITORY</option>
                                <option value="NEW SOUTH WALES" {{ $customer->state == "NEW SOUTH WALES" ? 'selected' : '' }}>NEW SOUTH WALES</option>
                                <option value="NORTHEN TERRITORY" {{ $customer->state == "NORTHERN TERRITORY" ? 'selected' : '' }}>NORTHERN TERRITORY</option>
                                <option value="QUEENSLAND" {{ $customer->state == "QUEENSLAND" ? 'selected' : '' }}>QUEENSLAND</option>
                                <option value="TASMANIA" {{ $customer->state == "TASMANIA" ? 'selected' : '' }}>TASMANIA</option>
                                <option value="VICTORYA" {{ $customer->state == "VICTORIA" ? 'selected' : '' }}>VICTORIA</option>
                                <option value="WESTERN AUSTRALIA" {{ $customer->state == "WESTERN AUSTRALIA" ? 'selected' : '' }}>WESTERN AUSTRALIA</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Suburb</label>

                        <div class="col-md-6">
                            <input id="suburb" name="suburb" type="text" class="form-control"
                                   value="{{ $customer->suburb }}">
                            <span class="text-danger">{{ $errors->first('suburb') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Postcode</label>

                        <div class="col-md-6">
                            <input id="postcode" name="postcode" type="text" class="form-control"
                                   value="{{ $customer->postcode }}">
                            <span class="text-danger">{{ $errors->first('postcode') }}</span>
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/customers/profile/{{$customer->id}}" class="btn btn-primary btn-create
                            btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Save</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@stop
