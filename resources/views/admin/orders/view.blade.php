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
                        <h5>Total Amount: {{$total_amount??0}}</h5>
                        <h5>Total Orders: {{$orders->total()}}</h5>
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
{{--                            <a href="{{route('orders.create')}}" class="btn btn-primary">Add Order</a>--}}
                        </div>


    <br/>

    <div class="row">
                                <div class="col-md-12">
                                    <form role="form" method="get" enctype="multipart/form-data" action="{{route('orders.list')}}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Search Type</label>
                                                <div class="form-group">
                                                    <select class="form-control" name="search_type">
                                                        <option value="">--Select Search Type--</option>
                                                        <option value="1">By Refid</option>
                                                        <option value="2">By Mobile</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label>Value</label>
                                                <div class="form-group">
                                                    <input type="text" name="search_value" class="form-control" placeholder="Value">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success " style="margin-top: 30px">Search</button>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <a class="btn btn-info" href="{{route('orders.list')}}?export=1&{{request()->getQueryString()}}">Export</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>




                        <br/>
                            <div class="row">
                                <div class="col-md-12">
                                    <form role="form" method="get" enctype="multipart/form-data" action="{{route('orders.list')}}">
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
                                                    <input type="text" name="referid" class="form-control" placeholder="ReferID">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>From Date</label>
                                                    <input type="date" name="fromdate" class="form-control" placeholder="Search Only Product Name"  value="{{request('fromdate')}}">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label>To Date</label>
                                                <div class="form-group">
                                                    <input type="date" name="todate" class="form-control" placeholder="Search Only Product Name"  value="{{request('todate')}}">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <label>Partners</label>
                                                <select class="form-control select2" name="partner_id" >
                                                    <option value="">--Select Parner</option>
                                         @foreach($partner as $partner)
                                                        <option value="{{$partner->id}}" @if($partner->id == request('partner_id')){{'selected'}}@endif>{{$partner->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Status</label>
                                                <select class="form-control select2" name="status">
                                                    <option value="">--Select Status</option>
                                                    @foreach(['confirmed', 'processing', 'delivered'] as $status)
                                                        <option value="{{$status}}" @if($status == request('status')){{'selected'}}@endif>{{ucfirst($status)}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
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
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>OR.No</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Is_Paid</th>
                                        <th>Delivery Date</th>
                                        <th>Delivery Time</th>
                                        <th>Delivery Partner</th>
                                        <th>Address Of Customer</th>
                                        <th>Society Name</th>
                                        <th>Customer Registered Date</th>
                                        <th>Last Order Date</th>
                                        <th>Last Order No</th>

                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($orders as $Order)
                    <tr>
                            <td>{{$Order->id}}</td>
                            <td>{{$Order->customer->name}}</td>
                            <td>{{$Order->customer->mobile}}</td>
                            <td>{{$Order->customer->orders_count}}</td>
                            <td>{{$Order->order_total}}</td>
                            <td>{{$Order->status}}</td>
                            <td>{{$Order->is_paid==1 ? 'Yes':'No'}}</td>
                            <td>{{date('d-m-Y', strtotime($Order->delivery_date));}}</td>
                            <td>{{$Order->delivery_time}}</td>
                            <td>{{$Order->partner->name}}</td>
                            <td>
                                {{$Order->customer->building??''}},
                                {{$Order->customer->street??''}},
                                {{$Order->customer->city??''}},
                                {{$Order->customer->state??''}},
                                {{$Order->customer->pincode??''}},
                                {{$Order->customer->house_no??''}}
                            </td>
                            <td>{{$Order->customer->building??''}}</td>
                            <td>{{date('d-m-Y', strtotime($Order->customer->created_at));}}</td>
                            <td>{{date('d-m-Y', strtotime($Order->created_at));}}</td>
                            <td>{{$Order->id}}</td>
                            <td><a href="{{route('orders.edit',['id'=>$Order->id])}}">Edit</a> <a href="{{route('invoice',['orderid'=>$Order->id])}}" class="btn btn-success">Print</a>
                            </td>
                    </tr>





                                        @endforeach
                                    </tbody>
                                </table>
                                {{$orders->appends(request()->input())->links()}}
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

