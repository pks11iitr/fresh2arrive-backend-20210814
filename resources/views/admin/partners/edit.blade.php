@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Partner</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('partners.list')}}">Partners</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Partner Add</h3>
                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" action="{{route('partners.update',['id' =>$Partners->id])}}" enctype="multipart/form-data">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Name </label>
                                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{$Partners->name}}" required>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Mobile </label>
                                                <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="{{$Partners->mobile}}"  required  >
                                            </div>

                                            <!-- /.form-group -->
                                        </div>
                                        <!-- /.col -->

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Address </label>
                                                <input type="text" name="address" class="form-control" placeholder="Address"  value="{{$Partners->address}}" required>
                                            </div>
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control" placeholder="City" value="{{$Partners->city}}"  required>
                                            </div>


                                            <!-- /.form-group -->
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Pincode</label>
                                                <input type="text" name="pincode" class="form-control" placeholder="Pincode"  value="{{$Partners->pincode}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label>state </label>
                                                <input type="text" name="state" class="form-control" placeholder="State"  value="{{$Partners->state}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                           {{-- <div class="form-group">
                                                <label>Notification Token </label>
                                                <input type="text" name="notification_token" class="form-control" placeholder="Notification Token" value="{{$Partners->notification_token}}"  required>
                                            </div>--}}
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control select2" name="status">
                                                    <option value="">Please Select Status</option>
                                                    <option @if($Partners->status==1) {{'selected=selected'}}@endif value="1">Active</option>
                                                    <option @if($Partners->status==0){{'selected=selected'}}@endif value="0">Inactive</option>
                                                    <option @if($Partners->status==2){{'selected=selected'}}@endif value="2">Blocked</option>

                                                </select>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Support Mobile </label>
                                                <input type="text" name="support_mobile" class="form-control" placeholder="Mobile" value="{{$Partners->support_mobile}}"  required  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Support Whatsapp </label>
                                                <input type="text" name="support_whatsapp" class="form-control" placeholder="Mobile" value="{{$Partners->support_whatsapp}}"  required  >
                                            </div>
                                        </div>

                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label>Areas</label>
                                                <select class="select3" multiple="multiple" data-placeholder="Select  Areas" name="area_ids[]" autofocus style="width: 100%;">
                                                    @foreach($areas as $area)
                    <option value="{{$area->id}}" @if(in_array($area->id, $assigned_areas)){{'selected'}}@endif>{{$area->name}}({{$area->city}}) - ({{$area->remarks}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Store Name </label>
                                                <input type="text" name="store_name" class="form-control" placeholder="Store Name" value="{{$Partners->store_name}}"  required  >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>House No. </label>
                                                <input type="text" name="house_no" class="form-control" placeholder="House `No" value="{{$Partners->house_no}}"  required  >
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Land Mark </label>
                                                <input type="text" name="landmark" class="form-control" placeholder="Landmark" value="{{$Partners->landmark}}"  required  >
                                            </div>
                                        </div>
                                    </div>


                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Pan No. </label>
                                                    <input type="text" name="pan_no" class="form-control" placeholder="Pan No." value="{{$Partners->pan_no}}"  required  >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Aadhaar No. </label>
                                                    <input type="text" name="aadhaar_no" class="form-control" placeholder="Aadhaar No." value="{{$Partners->aadhaar_no}}"  required  >
                                                </div>
                                            </div>
                                        </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Upload Pan Image </label>
                                                <input type="file" name="pan_url" class="form-control" placeholder="Pan No."  >
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Upload Aadhaar Image </label>
                                                <input type="file" name="aadhaar_url" class="form-control" placeholder="Aadhaar No."     >
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">

                                                <a href="{{$Partners->pan_url}}" class="btn btn-warning">Click and Preview Pan Photo</a>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <a href="{{$Partners->aadhaar_url}}" class="btn btn-danger">Click and Preview Aadhaar Photo</a>

                                            </div>
                                        </div>
                                    </div>



                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                <!-- /.card-body -->

                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->


    </div>
    <!-- ./wrapper -->
@endsection

