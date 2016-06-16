@extends("layout.backend.master")

@section("custom-script")
    <script src="{{ asset('backend/js/promotions/pricing.js')}}" type="text/javascript"></script>
@endsection

@section("content")
    <section class="content-header">
        <h1>
            Product Pricing Options
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/promotions">Promotions</a></li>
            <li class="active">Product pricing</li>
        </ol>
    </section>
    <div class="create-user">
        <div class="col-md-12 form-horizontal ">
            <div class="col-md-4">
                <h3>Product : {{$product->name}}</h3>
            </div>
            <div class="col-md-4">
                <h3>Theme : {{$theme->name}}</h3>
            </div>
            <div class="clearfix"></div>
            <form id="pricingForm" class="mt40 pricing-form col-md-8 form-horizontal"
                  action="/admin/promotions/{{$promotion->promotion_id}}/product/{{$product->product_id}}/pricing" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!empty($variants))
                        @for($i=0; $i<count($variants); $i++)
                                <div class="form-group">
                                    <div class="col-md-6">
                                        <?php
                                        $tags = explode(",", $variants[$i]['options_config']);
                                        for ($j=0; $j< count($tags); $j++){ ?>
                                        <span class="tag color-{{$j}}">{{$tags[$j]}}</span>
                                        <?php
                                        if($j == count($tags)-1){

                                        }else{
                                            echo '<i class="device">&ndash;</i>';
                                        }
                                        ?>

                                        <?php } ?>

                                    </div>
                                    {{--end.col-md-6--}}
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input type="text" name="prices[]" value="{{$variants[$i]['price'] > 0 ? $variants[$i]['price'] : ''}}" class="form-control input-prices">
                                                <input type="hidden" name="id[]" value="{{$variants[$i]['product_variant_id'] > 0 ? $variants[$i]['product_variant_id'] : 0}}" class="form-control">
                                        </div>
                                        <span class="text-danger"></span>
                                    </div>
                                    {{--end.col-md-3--}}
                                </div>
                        @endfor
                    @endif
                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <a href="/admin/promotions/profile/{{$promotion->promotion_id}}" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <span class="btn btn-primary btn-create btn-save btn-sm" id="submitPrice">Save</span>
                            {{--<button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Save</button>--}}
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@stop