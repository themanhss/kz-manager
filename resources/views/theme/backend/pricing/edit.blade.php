@extends("layout.backend.master")

@section("custom-script")
    <link href="{{ asset('backend/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('backend/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js')}}" type="text/javascript"></script>
    <script src="{{ asset('backend/js/theme/pricing.js')}}" type="text/javascript"></script>
@endsection

@section("content")
<section class="content-header">
    <h1>
        Promotion Pricing Options
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class=""><a href="/admin/promotions">Promotions</a></li>
        <li class="active">Pricing Page</li>
    </ol>
</section>
<div class="create-user">
    @if(!empty($theme))
        <input type="hidden" id="themeID" value="{{$theme->theme_id}}">
        <div class="col-md-12 form-horizontal ">
        <div class="col-md-6">
            {{--<h3>Promotion: <b class="uppercase">{{$promotion->name}}</b></h3>--}}
            <h3>Theme: <b class="uppercase">{{$theme->name}}</b></h3>
        </div>
        <div class="col-md-6">
            {{--<h3>Theme: <b class="uppercase">{{$theme->name}}</b></h3>--}}
        </div>
        <div class="clearfix"></div>
        {{--$theme_fields--}}
        <form id="PageForm" class="form-horizontal"
              action="/admin/themes/{{$theme->theme_id}}/pricing" method="post"
              enctype="multipart/form-data">
            <input type="hidden" id="formToken" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="removeID" value="">
            <div class="box-pricing-field mt40">
                <div class="form-group">
                    <label class="col-md-3" for="assetType"><b>Option name</b></label>
                    <div class="col-md-6">
                        <label class="control-label" for="assetType"><b>Option values</b></label>
                    </div>
                </div>
                <div id="contentPricing">
                    @if(count($old_variants) > 0)
                        <input type="hidden" id="isEdit" value="1">
                        @foreach($old_variants as $key=>$variant)
                            <div class="form-group">
                                <input type="hidden" class="variant-id" value="{{$variant->variant_id}}">
                                <div class="col-md-3 field-label">
                                    <input type="text" class="form-control" value="{{$variant->label}}">
                                    <span class="text-danger"></span>
                                </div>

                                <div class="col-md-6 field-tags">
                                    <input type="text" class="form-control main-tags" value="{{$variant->options}}"  data-role="tagsinput">
                                    <span class="text-danger"></span>
                                </div>
                                <div class="col-md-1">
                                    <div class="remove-field">
                                        <i class="fa fa-trash pointer fa-2x remove-ico" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <input type="hidden" id="isEdit" value="0">
                        <div class="form-group">
                            <div class="col-md-3 field-label">
                                <input type="text" class="form-control" value="">
                                <span class="text-danger"></span>
                            </div>

                            <div class="col-md-6 field-tags">
                                <input type="text" class="form-control main-tags" value=""  data-role="tagsinput">
                                <span class="text-danger"></span>
                            </div>
                            <div class="col-md-1">
                                <div class="remove-field">
                                    <i class="fa fa-trash pointer fa-2x remove-ico" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <div class="col-md-3">
                        <span class="btn btn-primary" id="addMore"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Add more</span>
                    </div>
                </div>


                <!-- Form actions -->
                <div class="form-group mt40">
                    <div class="col-md-12 text-center">
                        <a href="/admin/themes" class="btn btn-primary btn-create btn-sm">Cancel</a>
                        <span class="btn btn-primary btn-create btn-save btn-sm" id="savePricing">Save</span>
                    </div>
                </div>
            </div>

        </form>
        <script id="hidden-template" type="text/x-custom-template">
            <div class="form-group">
                <input type="hidden" class="variant-id" value="0">
                <div class="col-md-3 field-label">
                    <input type="text" class="form-control" value="">
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-6 field-tags">
                    <input type="text" class="form-control main-tags" value=""  data-role="tagsinput">
                    <span class="text-danger"></span>
                </div>
                <div class="col-md-1">
                    <div class="remove-field">
                        <i class="fa fa-trash pointer fa-2x remove-ico" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </script>


    </div>
    @endif
</div>
@stop
