@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Customer</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('category.list')}}">Customers</a></li>
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
                                <h3 class="card-title">Customer Add</h3>
                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('customers.update',['id'=>$customer->id])}}">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Name </label>
                                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{$customer->name}}" required>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Mobile </label>
                                                <input type="text" name="mobile" class="form-control" placeholder="Mobile" value="{{$customer->mobile}}"  required>
                                            </div>


                                                <div class="form-group">
                                                    <label>Assigned Partner</label>
                                                    <select class="form-control select2" name="assigned_partner" >
                                                        <option value="">Please Select</option>
                                                        @foreach($partners as $p)
                                                            <option value="{{$p->id}}"
                                                            @if($p->id==$customer->assigned_partner) {{"selected"}}@endif>{{$p->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>


                                           {{-- <div class="form-group">
                                                <label>Email </label>
                                                <input type="text" name="email" class="form-control" placeholder="Email"  value="{{$customer->email}}" >
                                            </div>
--}}

                                        </div>


                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Area</label>
                                          <select class="form-control select2" name="area" >
                                        <option value="">Please Select</option>
                                        @foreach($area as $p)
                                            <option value="{{$p->name}}"
                                            @if($p->name==$customer->area){{'selected'}}@endif

                                            >{{$p->name}}</option>
                                        @endforeach
                                                </select>


                                            </div>

                                            <div class="form-group">
                                                <label>House No</label>
                                                <input type="text" name="house_no" class="form-control" placeholder="House No" value="{{$customer->house_no}}"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Building</label>
                                                <input type="text" name="building" class="form-control" placeholder="Building"  value="{{$customer->building}}" required>
                                            </div>


                                            <!-- /.form-group -->
                                        </div>


                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control" placeholder="City"  value="{{$customer->city}}" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Pincode</label>
                                                <input type="text" name="pincode" class="form-control" placeholder="Pincode" value="{{$customer->pincode}}"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>state </label>
                                                <input type="text" name="state" class="form-control" placeholder="State" value="{{$customer->state}}"  required>
                                            </div>


                                            <!-- /.form-group -->
                                        </div>
                                        <div class="col-md-3">



                                            <div class="form-group">
                                                <label>Map Address </label>
                                                <input type="text" name="map_address" class="form-control" value="{{$customer->map_address}}" placeholder="Map Address"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control select2" name="status">
                                                    <option value="">Please Select Status</option>
                                                    <option @if($customer->status==0){{'selected=selected'}}@endif value="1">Inactive </option>
                                                    <option @if($customer->status==1){{'selected=selected'}}@endif value="0">Active</option>
                                                    <option @if($customer->status==2){{'selected=selected'}}@endif value="2">Block</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Street </label>
                                                <input type="text" name="street" class="form-control" placeholder="Street"  value="{{$customer->street}}" required>
                                            </div>
                                            <!-- /.col -->
                                        </div>

                                        {{--<div class="col-md-3">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="text" name="password" class="form-control" placeholder="Password"  value="{{$customer->password}}"   required>
                                            </div>
                                        </div>--}}


                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Reffered By</label>
                                                <input type="hidden" name="reffered_by" value="{{$customer->reffered_by}}">
                                                <input type="text" readonly name="" class="form-control"  value="{{$customername}}" placeholder="Reffered By"  required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Reffered By Partner</label>


                                                    <?php
                                                    if(empty($customer->partnerRefferer->name??'')){
                                                        $partner_name="NA";
                                                    }else{
                                                        $partner_name=$customer->partnerRefferer->name??'';
                                                    }

                                                    ?>


                                                <input type="hidden" name="reffered_by_partner"  value="{{$customer->partner->id??0}}">
                                                <input type="text"  readonly class="form-control" value="{{$partner_name}}"  placeholder="Reffered By Partner" >
                                            </div>
                                        </div>


                                       {{-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Image</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile" accept="image/*"  >
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="">Upload</span>
                                                    </div>
                                                </div>
                                                <img src="{{$customer->image}}" height="100" width="200"/>
                                            </div>
                                        </div>--}}


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

