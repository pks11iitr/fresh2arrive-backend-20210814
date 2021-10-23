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

                            <form role="form" method="post" action="{{route('partners.store')}}">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Name </label>
                                                <input type="text" name="name" class="form-control" placeholder="Name"  required>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Mobile </label>
                                                <input type="text" name="mobile" class="form-control" placeholder="Mobile"  required  >
                                            </div>

                                            <!-- /.form-group -->
                                        </div>
                                        <!-- /.col -->

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Address </label>
                                                <input type="text" name="address" class="form-control" placeholder="Address"  required>
                                            </div>
                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control" placeholder="City"  required>
                                            </div>


                                            <!-- /.form-group -->
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Pincode</label>
                                                <input type="text" name="pincode" class="form-control" placeholder="Pincode"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>state </label>
                                                <input type="text" name="state" class="form-control" placeholder="State"  required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Notification Token </label>
                                            <input type="text" name="notification_token" class="form-control" placeholder="Notification Token"  required>
                                        </div>
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control select2" name="status">
                                                    <option value="">Please Select Status</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
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
                                                <input type="text" name="support_mobile" class="form-control" placeholder="Support mobile"  required  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Support Whatsapp </label>
                                                <input type="text" name="support_whatsapp" class="form-control" placeholder="Support Whatsapp"  required  >
                                            </div>
                                        </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Areas</label>
                                                    <select class="select3" multiple="multiple" data-placeholder="Select  Areas" name="area_ids[]" autofocus style="width: 100%;">
                                                        @foreach($area as $area)
                                                            <option value="{{$area->id}}">{{$area->name}}</option>
                                                        @endforeach
                                                    </select>
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


