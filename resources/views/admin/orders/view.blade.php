@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Orders</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Order</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card-header">
                            <a href="{{route('orders.create')}}" class="btn btn-primary">Add Order</a>
                        </div>
                        <br/>
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" method="get" enctype="multipart/form-data" action="{{route('orders.list')}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Search Type</label>
                                                <div class="form-group">
                                                    <select class="form-control" name="search_type">
                                                        <option value="">--Select Search Type--</option>
                                                        <option value="1">By Refid</option>
                                                        <option value="2">By Name</option>
                                                        <option value="3">By Mobile</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label>ReferID</label>
                                                <div class="form-group">
                                                    <input type="text" name="referid" class="form-control" placeholder="ReferID"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>From Date</label>
                                                    <input type="date" name="fromdate" class="form-control" placeholder="Search Only Product Name"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>To Date</label>
                                                <div class="form-group">
                                                    <input type="date" name="todate" class="form-control" placeholder="Search Only Product Name"  required>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <label>Partners</label>
                                                <select class="form-control select2" name="product_id" required>
                                                    <option value="">--Select Parner</option>
                                         @foreach($partner as $partner)
                                                        <option value="{{$partner->id}}">{{$partner->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success " style="margin-top: 30px">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        <div class="card">

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Refid</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Is_Pad</th>
                                        <th>Delivery Date</th>
                                        <th>Delivery Time</th>
                                        <th>Delivery Partner</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order as $Order)
                        <tr>
                            <td>{{$Order->id}}</td>
                            <td>{{$Order->refid}}</td>
                            <td>{{$Order->order_total}}</td>
                            <td>{{$Order->status==0?'pending':'confirmed'}}</td>
                            <td>{{$Order->is_paid==0?'Yes':'No'}}</td>
                            <td>{{$Order->delivery_date}}</td>
                            <td>{{$Order->delivery_time}}</td>
                            <td>{{$Order->partner->name}}</td>
                            <td><a href="{{route('orders.edit',['id'=>$Order->id])}}">Edit</a></td>

                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->
@endsection
