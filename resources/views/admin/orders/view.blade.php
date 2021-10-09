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
                        <div class="card">
                            <div class="card-header">
                                <a href="{{route('orders.create')}}" class="btn btn-primary">Add Order</a>

                            </div>
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
                            <td>{{$Order->status?'pending':'confirmed'}}</td>
                            <td>{{$Order->is_paid?'Yes':'No'}}</td>
                            <td>{{$Order->delivery_date}}</td>
                            <td>{{$Order->delivery_time}}</td>
                            <td>{{$Order->delivery_partner}}</td>
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

