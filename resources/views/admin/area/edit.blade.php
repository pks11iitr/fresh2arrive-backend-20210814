@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Area</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('area.list')}}">Area</a></li>
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
                                <h3 class="card-title">Area Add</h3>
                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" action="{{route('area.update',['id'=>$area->id])}}">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Name </label>
                                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{$area->name}}" required>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control" placeholder="City" value="{{$area->city}}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>state </label>
                                                <input type="text" name="state" class="form-control" placeholder="State" value="{{$area->state}}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Pincode</label>
                                                <input type="text" name="pincode" class="form-control" placeholder="Pincode" value="{{$area->pincode}}" required>
                                            </div>


                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Lat</label>
                                                <input type="text" name="lat" class="form-control" placeholder="Lat" value="{{$area->lat}}"  required>
                                            </div>


                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Lang</label>
                                                <input type="text" name="lang" class="form-control" placeholder="Lang" value="{{$area->lang}}"  required>
                                            </div>


                                        </div>


                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Distance</label>
                                                <input type="text" name="distance" class="form-control" placeholder="Distance" value="{{$area->distance}}"  required>
                                            </div>


                                        </div>

                                        
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control select2" name="isactive">
                            <option value="1" @if($area->isactive==1){{'selected'}}@endif>Active</option>
                            <option value="0" @if($area->isactive==0){{'selected'}}@endif>Inactive</option>
                                                </select>
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
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

