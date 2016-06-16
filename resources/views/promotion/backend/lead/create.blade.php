@extends("layout.backend.master")

@section("custom-script")
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.date.extensions.js')}}" type="text/javascript"></script>
    <script src="{{ asset('backend/js/promotions/main.js')}}" type="text/javascript"></script>
@endsection

@section("content")
    <section class="content-header">
        <h1>
            Create Lead
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/promotions">Promotions</a></li>
            <li class="active">Create Lead</li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-12 form-horizontal ">
            <form id="LeadForm" class="form-horizontal"
                  action="/admin/promotions/{{$promotion->promotion_id}}/lead/create" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>

                    <div class="col-md-8 left-box">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Customer</label>

                                <div class="col-md-7">
                                    <input id="nameCustomer" name="nameCustomer" type="text" class="form-control customer-name" value="{{ old('nameCustomer') }}">
                                    <input id="name" name="name" type="hidden" class="form-control" value="{{ old('name') }}">
                                </div>
                                <div class="col-md-1">
                                    <a href="#" data-toggle="modal" data-target="#createCustomer" >
                                        <i class="fa fa-user fa-2x ic-add-user"></i>
                                    </a>

                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Lead Source</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="source_type" name="source_type">
                                        <option value="Phone">Phone Call</option>
                                        <option value="Live Chat">Live Chat</option>
                                        <option value="Website Enquiry">Enquiry Form</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Email</label>

                                <div class="col-md-8">
                                    <input id="email" name="email" type="text" class="form-control" value="{{ old('email') }}">
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Phone</label>

                                <div class="col-md-8">
                                    <input id="phone" name="phone" type="text" class="form-control" value="{{ old('phone') }}">
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Description</label>

                                <div class="col-md-8">
                                    <textarea id="message" name="message" type="text" class="form-control" value="{{ old('message') }}">{{ old('message') }}</textarea>
                                    <span class="text-danger">{{ $errors->first('message') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--end.left-box--}}

                    <div class="col-md-4 right-box">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Date</label>
                                <div class="col-md-8">
                                    <div class="input-group date">
                                        <input type="text" name="date" value="{{old('date')?old('date'):date("d/m/Y")}}" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div><!-- /.input group -->
                                    <span class="text-danger">{{ $errors->first('date') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Interested in:</label>
                                <div class="col-md-8">

                                        @if(!empty($products))
                                            @foreach($products as $product)
                                            <div class="checkbox">
                                                <label>
                                                    <input type='checkbox' name='product[]' value='{{$product->product_id}}' />
                                                    {{$product->name}}
                                                </label>
                                            </div>
                                            @endforeach
                                        @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    {{--end.right-box--}}


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

        <!-- Add New Customer -->
        <div class="modal fade" id="createCustomer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="">Create New Customer</h4>
                    </div>
                    <div class="modal-body-customer">
                        <div class="col-md-12">
                            <form id="assetForm" class="form-horizontal" action="" method="post">
                                <input type="hidden" id="tokenID" name="_token" value="{{ csrf_token() }}">
                                <fieldset>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="assetType">First Name</label>

                                        <div class="col-md-7">
                                            <input id="first_name" name="first_name" type="text" class="form-control"
                                                   value="{{ old('first_name') }}">
                                            <span class="text-danger" id="error_first_name">{{ $errors->first('first_name') }}</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="uploadDate">Last Name</label>

                                        <div class="col-md-7">
                                            <input id="last_name" name="last_name" type="text" class="form-control"
                                                   value="{{ old('last_name') }}">
                                            <span class="text-danger" id="error_last_name">{{ $errors->first('last_name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="assetType">Email</label>

                                        <div class="col-md-7">
                                            <input id="email_box" name="email" type="text" class="form-control" value="{{ old('email') }}">
                                            <span class="text-danger" id="error_email">{{ $errors->first('email') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="assetType">Phone</label>

                                        <div class="col-md-7">
                                            <input id="mobile_phone" name="mobile_phone" type="text" class="form-control" value="{{ old('mobile_phone') }}">
                                            <span class="text-danger" id="error_mobile_phone">{{ $errors->first('mobile_phone') }}</span>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="uploadDate">State</label>

                                        <div class="col-md-5">
                                            <select class="form-control" id="state" name="state">
                                                <option value="AUSTRALIAN CAPITAL TERRITORY">AUSTRALIAN CAPITAL TERRITORY</option>
                                                <option value="NEW SOUTH WALES">NEW SOUTH WALES</option>
                                                <option value="NORTHEN TERRITORY">NORTHEN TERRITORY</option>
                                                <option value="QUEENSLAND">QUEENSLAND</option>
                                                <option value="TASMANIA">TASMANIA</option>
                                                <option value="VICTORYA">VICTORYA</option>
                                                <option value="WESTERN AUSTRALIA">WESTERN AUSTRALIA</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="assetType">Suburb</label>

                                        <div class="col-md-7">
                                            <input id="suburb" name="suburb" type="text" class="form-control"
                                                   value="{{ old('suburb') }}">
                                            <span class="text-danger" id="error_suburb">{{ $errors->first('suburb') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label" for="assetType">Postcode</label>

                                        <div class="col-md-7">
                                            <input id="postcode" name="postcode" type="text" class="form-control"
                                                   value="{{ old('postcode') }}">
                                            <span class="text-danger" id="error_postcode">{{ $errors->first('postcode') }}</span>
                                        </div>
                                    </div>

                                    <!-- Form actions -->
                                    <div class="form-group">
                                        <div class="col-md-12 text-center">
                                            <a href="#" data-dismiss="modal" class="btn btn-primary btn-create btn-sm">Cancel</a>
                                            <a href="#" id="createNewCustomer" class="btn btn-primary btn-create btn-save btn-sm">Save</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{--<input type="hidden" name="leadId" value="" id="leadId"/>
                        <input type="hidden" id="promotionId" value="{{$promotion->promotion_id}}"/>
                        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                        <button type="button" id="deleteFormLead" class="btn btn-primary">Yes</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
