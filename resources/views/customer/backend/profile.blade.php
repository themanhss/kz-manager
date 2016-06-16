@extends("layout.backend.master")

@section("custom-script")
    <script>
        $(document).ready(function () {
            $('#visitedTable').dataTable({
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "bPaginate": true,
                "bLengthChange": true,
                "bFilter": true,
                "bSort": true,
                "bInfo": true,
                "bAutoWidth": false
            });
        });
    </script>
@endsection

@section("content")
    <section class="content-header">
        <h1>
            {{$customer->first_name.' '.$customer->last_name}}
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a type="button" href="/admin/customers/edit/{{$customer->id}}" class="btn btn-primary"><i class="fa fa-pencil-square-o fa-1x
        "></i>&nbsp;&nbsp;Edit</a>&nbsp;&nbsp;
            <a type="button" href="/admin/customers/delete/{{$customer->id}}" class="btn btn-primary"><i class="fa fa-trash fa-1x
        "></i> &nbsp;&nbsp;Delete</a>
        </h1>
        <ol class="breadcrumb">
            <li><a href="/admin"><i class="fa fa-dashboard"></i> Admin</a></li>
            <li class=""><a href="/admin/customers">Manager Customer</a></li>
            <li class="active">Customer Information</li>
        </ol>
    </section>
    <div class="create-user profile-customer">

        <div class="col-md-12 form-horizontal ">

            <div class="form-group">
                <label class="col-md-1 control-label" for="assetType">Email</label>

                <div class="col-md-3">
                    <input id="email" name="email" disabled type="text" class="form-control" value="{{ $customer->email }}">
                </div>

                <label class="col-md-1 col-md-offset-1 control-label" for="assetType">State</label>

                <div class="col-md-2">
                    <select class="form-control" disabled id="state" name="state">
                        <option value="AUSTRALIAN CAPITAL TERRITORY" {{ $customer->state == "AUSTRALIAN CAPITAL TERRITORY" ? 'selected' : '' }}>AUSTRALIAN CAPITAL TERRITORY</option>
                        <option value="NEW SOUTH WALES" {{ $customer->state == "NEW SOUTH WALES" ? 'selected' : '' }}>NEW SOUTH WALES</option>
                        <option value="NORTHEN TERRITORY" {{ $customer->state == "NORTHEN TERRITORY" ? 'selected' : '' }}>NORTHEN TERRITORY</option>
                        <option value="QUEENSLAND" {{ $customer->state == "QUEENSLAND" ? 'selected' : '' }}>QUEENSLAND</option>
                        <option value="TASMANIA" {{ $customer->state == "TASMANIA" ? 'selected' : '' }}>TASMANIA</option>
                        <option value="VICTORYA" {{ $customer->state == "VICTORYA" ? 'selected' : '' }}>VICTORYA</option>
                        <option value="WESTERN AUSTRALIA" {{ $customer->state == "WESTERN AUSTRALIA" ? 'selected' : '' }}>WESTERN AUSTRALIA</option>
                    </select>
                </div>
                <label class="col-md-1 control-label" for="assetType">Postcode</label>

                <div class="col-md-2">
                    <input id="" name="email" type="text" disabled class="form-control" value="{{ $customer->postcode }}">
                </div>

            </div>
            <div class="form-group">
                <label class="col-md-1 control-label" for="assetType">Phone</label>

                <div class="col-md-3">
                    <input id="email" name="email" disabled type="text" class="form-control" value="{{ $customer->mobile_phone }}">
                </div>

                <label class="col-md-1 col-md-offset-1 control-label" for="assetType">Suburb</label>

                <div class="col-md-5">
                    <input id="" name="email" disabled type="text" class="form-control" value="{{ $customer->suburb }}">
                </div>

            </div>
        </div>

        <div class="col-md-12 form-horizontal">
            <div class="form-group">
                <div class="col-md-6">
                    <h2>Promotion communications</h2>
                </div>
                <div class="col-md-6 box-add-communication">
                    <a type="button" href="#" class="btn btn-primary"><i class="fa fa-phone fa-1x"></i>&nbsp;&nbsp;
                        Add a new communication log</a>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <table id="dataTable" class="table table-bordered table-striped dataTable" role="grid">
                        <thead>
                        <tr role="row">
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                colspan="1">Promotion</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                colspan="1">Last Update</th>
                            <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                colspan="1">View</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr role="row" class="odd">
                            <td>...</td>
                            <td>...</td>
                            <td>...</td>

                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <div class="col-md-12 form-horizontal">
            <div class="form-group">
                <div class="col-md-6">
                    <h2>Visited Pages</h2>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <table id="visitedTable" class="table table-bordered table-striped dataTable" role="grid">
                        <thead>
                            <tr role="row">
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Page</th>
                                <th class="" tabindex="0" aria-controls="example1" rowspan="1"
                                    colspan="1">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(!empty($pages))
                            @foreach($pages as $page)
                                <tr role="row" class="odd">
                                    <td>{{$page->visitor_page}}</td>
                                    <td>{{$page->visitor_date?date('d/m/Y', strtotime($page->visitor_date)):'' }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

@stop
