@extends("layout.backend.master")

@section("custom-script")
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.date.extensions.js')}}" type="text/javascript"></script>
    <script src="{{ asset('backend/js/promotions/main.js')}}" type="text/javascript"></script>
@endsection

@section("content")
    <section class="content-header">
        <h1>
            Edit Lead
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/promotions">Promotions</a></li>
            <li class="active">Edit Lead</li>
        </ol>
    </section>
    <div class="create-user">

        <div class="col-md-12 form-horizontal ">
            <form id="LeadForm" class="form-horizontal"
                  action="/admin/promotions/{{$promotion->promotion_id}}/lead/edit/{{$lead->lead_id}}" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>

                    <div class="col-md-8 left-box">
                        <div class="row">
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Customer</label>

                                <div class="col-md-8">
                                    <input id="nameCustomer" name="nameCustomer" type="text" class="form-control customer-name" value="{{$lead->customer->first_name.' '.$lead->customer->last_name}}">
                                    <input id="name" name="name" type="hidden" class="form-control" value="{{ $lead->client_id }}">
                                    {{--<span class="text-danger">{{ $errors->first('name') }}</span>--}}
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Lead Source</label>
                                <div class="col-md-8">
                                    <select class="form-control" id="source_type" name="source_type">
                                        <option value="Phone" {{$lead->source_type=='Phone'? 'selected': ''}}>Phone Call</option>
                                        <option value="Live Chat" {{$lead->source_type=='Live Chat'? 'selected': ''}}>Live Chat</option>
                                        <option value="Website Enquiry" {{$lead->source_type=='Website Enquiry'? 'selected': ''}}>Enquiry Form</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Email</label>

                                <div class="col-md-8">
                                    <input id="email" name="email" type="text" class="form-control" value="{{ $lead->email }}">
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Phone</label>

                                <div class="col-md-8">
                                    <input id="phone" name="phone" type="text" class="form-control" value="{{ $lead->phone }}">
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label" for="assetType">Description</label>

                                <div class="col-md-8">
                                    <textarea id="message" name="message" type="text" class="form-control" value="{{ $lead->message }}">{{ $lead->message }}</textarea>
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
                                        {{--<div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="date" value="{{$lead->date}}" class="form-control"
                                               data-inputmask="'alias':'dd-mm-yyyy'" data-mask/>--}}
                                        <input type="text" name="date" value="{{$lead->date?date('d/m/Y', strtotime($lead->date)):''}}" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
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
                                                    @if(!empty($lead->product_ids))
                                                        <input type='checkbox' name='product[]'  {{in_array($product->product_id,json_decode($lead->product_ids))? 'checked': ''}} value='{{$product->product_id}}' />
                                                    @else
                                                        <input type='checkbox' name='product[]' value='{{$product->product_id}}' />
                                                    @endif

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
    </div>
@stop
