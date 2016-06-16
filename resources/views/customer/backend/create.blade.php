@extends("layout.backend.master")

@section("content")
    <section class="content-header">
        <h1>
            Create Customer
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/customers">Manager Customer</a></li>
            <li class="active">Create Customer</li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-9 col-md-offset-1">
            <form id="assetForm" class="form-horizontal" action="/admin/customers/create" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">First Name</label>

                        <div class="col-md-6">
                            <input id="first_name" name="first_name" type="text" class="form-control"
                                   value="{{ old('first_name') }}">
                            <span class="text-danger">{{ $errors->first('first_name') }}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">Last Name</label>

                        <div class="col-md-6">
                            <input id="lastName" name="last_name" type="text" class="form-control"
                                   value="{{ old('last_name') }}">
                            <span class="text-danger">{{ $errors->first('last_name') }}</span>
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
                        <label class="col-md-3 control-label" for="assetType">Phone</label>

                        <div class="col-md-6">
                            <input id="mobile_phone" name="mobile_phone" type="text" class="form-control"
                                   value="{{ old('mobile_phone') }}">
                            <span class="text-danger">{{ $errors->first('mobile_phone') }}</span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-3 control-label" for="uploadDate">State</label>

                        <div class="col-md-6">
                            <select class="form-control" id="state" name="state">
                                <option value="AUSTRALIAN CAPITAL TERRITORY">AUSTRALIAN CAPITAL TERRITORY</option>
                                <option value="NEW SOUTH WALES">NEW SOUTH WALES</option>
                                <option value="NORTHERN TERRITORY">NORTHERN TERRITORY</option>
                                <option value="QUEENSLAND">QUEENSLAND</option>
                                <option value="TASMANIA">TASMANIA</option>
                                <option value="VICTORIA">VICTORIA</option>
                                <option value="WESTERN AUSTRALIA">WESTERN AUSTRALIA</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Suburb</label>

                        <div class="col-md-6">
                            <input id="suburb" name="suburb" type="text" class="form-control"
                                   value="{{ old('suburb') }}">
                            <span class="text-danger">{{ $errors->first('suburb') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Postcode</label>

                        <div class="col-md-6">
                            <input id="postcode" name="postcode" type="text" class="form-control"
                                   value="{{ old('postcode') }}">
                            <span class="text-danger">{{ $errors->first('postcode') }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Mailchimp Group</label>

                        <div class="col-md-6">
                            <select name="group_id" class="form-control">
                                @foreach($groups['data'] as $group)
                                    <option value="{{ $group['id'] }}">{{ $group['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">Single opt-in</label>

                        <div class="col-md-6">
                            <input type="checkbox" name="opt_in" value="1">
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/customers" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Save</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

@stop
