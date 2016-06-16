@extends("layout.backend.master")

@section("custom-script")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.js')}}" type="text/javascript"></script>
    <script src="{{asset('backend/plugins/input-mask/jquery.inputmask.date.extensions.js')}}" type="text/javascript"></script>
    <script src="{{ asset('backend/js/promotions/page.js')}}" type="text/javascript"></script>
@endsection

@section("content")
<section class="content-header">
    <h1>
        Edit Product Page Content
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
        <li class=""><a href="/admin/promotions">Promotions</a></li>
        <li class=""><a href="#">Product</a></li>
        <li class="active">Edit Page</li>
    </ol>
</section>
<div class="create-user">
    @if(empty($theme))
        <h2>Please select Theme before edit Product Page Content !</h2>
<div class="edit">
    <a type="button" href="/admin/promotions/edit/{{$promotion->promotion_id}}" class="btn btn-primary"><i class="fa fa-pencil
        fa-1x"></i>&nbsp;
        &nbsp;Update Promotion
    </a>
</div>
    @endif
    @if(!empty($theme))
        <div class="col-md-12 form-horizontal ">
        <div class="col-md-6">
            <h3>Product: <b class="uppercase">{{$product->name}}</b></h3>
        </div>
        <div class="col-md-6">
            <h3>Theme: <b class="uppercase">{{$theme->name}}</b></h3>
        </div>
        <div class="clearfix"></div>
        {{--$theme_fields--}}
        <form id="PageForm" class="form-horizontal"
              action="/admin/promotions/{{$promotion->promotion_id}}/product/page/{{$product->product_id}}" method="post"
              enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="box-theme-field">
                @if(!empty($theme_fields))
                    <?php $i = 1; ?>
                    @foreach($theme_fields as $key=> $theme_field)
                        <div class="form-group row">
                            <label class="col-md-3 control-label" for="assetType">{{$theme_field->field_name}}</label>
                            <div class="col-md-6">
                                <?php
                                    $val = '';
                                ?>
                                @if(!empty($fields_json))
                                        @if(array_key_exists($i,$fields_json))
                                            <?php
                                            $val = $fields_json[$i]['data'];
                                            ?>
                                        @endif
                                @endif

                                @if($theme_field->field_type == 'WYSIWYG')
                                    <textarea id="wysiwyg-{{$i}}" class="wysiwyg-classic" name="wysiwyg-{{$i}}" rows="10" cols="80" value="{{$val}}">{{$val}}</textarea>
                                @endif

                                @if($theme_field->field_type == 'Text Field')
                                    <input id="textField-{{$i}}" name="textField-{{$i}}" value="{{$val}}" type="text" class="form-control" >
                                @endif

                                @if($theme_field->field_type == 'File Upload')
                                    <input type="file" name="fileUpload-{{$i}}" value="{{$val}}" id="fileUpload-{{$i}}">
                                @endif

                                @if($theme_field->field_type == 'Date Field')
                                    <div class="input-group date">
                                        <input type="text" id="dateField-{{$i}}" name="dateField-{{$i}}" value="{{$val}}" class="form-control"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div><!-- /.input group -->
                                @endif

                            </div>
                        </div>
                        <?php $i = $i + 1; ?>
                    @endforeach
                @endif

                <!-- Form actions -->
                <div class="form-group">
                    <div class="col-md-12 text-center">
                        <a href="/admin/promotions/profile/{{$promotion->promotion_id}}" class="btn btn-primary btn-create btn-sm">Cancel</a>
                        <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Save</button>
                    </div>
                </div>
            </div>

        </form>


    </div>
    @endif
</div>
@stop
