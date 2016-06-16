@extends("layout.backend.master")

@section("content")

    <div class="create-user">
        <h3>IMPORT CUSTOMER</h3>

        <div class="col-md-9 col-md-offset-1">
            <form id="assetForm" class="form-horizontal" action="/admin/customers/import" method="post"
                  enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="assetType">File to import</label>

                        <div class="col-md-4">
                            <input id="import_customer" name="import_customer" type="file" class="form-control"
                                   value="{{old('import_customer')}}" placeholder="Import .csv file...">
                            <span class="text-danger">{{ $errors->first('import_customer') }}</span>
                        </div>
                    </div>

                    <!-- Form actions -->
                    <div class="form-group">
                        <div class="col-md-9 text-center">
                            <a href="/admin/customers" class="btn btn-primary btn-create btn-sm">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-create btn-save btn-sm">Import</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="clearfix"></div>
        @if($customers)
            <h3 class="uppercase">Imported customer results</h3>
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-body">
                        <table id="dataTable" class="table table-bordered table-striped dataTable" role="grid"
                               aria-describedby="example1_info">
                            <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="example1">First Name</th>
                                <th class="" tabindex="0" aria-controls="example1">Last Name</th>
                                <th class="" tabindex="0" aria-controls="example1">Email</th>
                                <th class="" tabindex="0" aria-controls="example1">Phone</th>
                                <th class="" tabindex="0" aria-controls="example1">State</th>
                                <th class="" tabindex="0" aria-controls="example1">Suburb</th>
                                <th class="" tabindex="0" aria-controls="example1">Postcode</th>
                                <th class="" tabindex="0" aria-controls="example1">Import Status</th>
                                <th class="" tabindex="0" aria-controls="example1">View</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($customers))
                                @foreach($customers as $key=>$customer)
                                    <tr role="row" class="odd">
                                        <td data-toggle="tooltip" data-container="body" data-placement="top"
                                            title="{{$customer->errors->first('first_name')}}"
                                            class="{{$customer->errors->first('first_name')? 'error':''}}">{{$customer->first_name}}</td>
                                        <td data-toggle="tooltip" data-container="body" data-placement="top"
                                            title="{{$customer->errors->first('last_name')}}"
                                            class="{{$customer->errors->first('last_name')? 'error':''}}">{{$customer->last_name}}</td>
                                        <td data-toggle="tooltip" data-container="body" data-placement="top"
                                            title="{{$customer->errors->first('email')}}"
                                            class="{{$customer->errors->first('email')? 'error':''}}">{{$customer->email}}</td>
                                        <td data-toggle="tooltip" data-container="body" data-placement="top"
                                            title="{{$customer->errors->first('mobile_phone')}}"
                                            class="{{$customer->errors->first('mobile_phone')? 'error':''}}">{{$customer->mobile_phone}}</td>
                                        <td data-toggle="tooltip" data-container="body" data-placement="top"
                                            title="{{$customer->errors->first('state')}}"
                                            class="{{$customer->errors->first('state')? 'error':''}}">{{$customer->state}}</td>
                                        <td data-toggle="tooltip" data-container="body" data-placement="top"
                                            title="{{$customer->errors->first('suburb')}}"
                                            class="{{$customer->errors->first('suburb')? 'error':''}}">{{$customer->suburb}}</td>
                                        <td data-toggle="tooltip" data-container="body" data-placement="top"
                                            title="{{$customer->errors->first('postcode')}}"
                                            class="{{$customer->errors->first('postcode')?
                                        'error':''}}">{{$customer->postcode}}</td>
                                        <td>
                                            @if($customer->status == 1)
                                                Imported
                                            @else
                                                <a href="#">Error</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if($customer->id)
                                                <a href="/admin/customers/profile/{{$customer->id}}">View</a>
                                            @else
                                                <a href="#" disabled >View</a>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                            <tfoot class="hidden">
                            <tr>
                                <th rowspan="1" colspan="1">ID</th>
                                <th rowspan="1" colspan="1">Name</th>
                                <th rowspan="1" colspan="1">Email</th>
                                <th rowspan="1" colspan="1">Activated</th>
                                <th rowspan="1" colspan="1">Blocked</th>
                                <th>&nbsp;</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        @endif


    </div>

@stop
