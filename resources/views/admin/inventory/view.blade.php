@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Inventory</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Inventory</li>
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
                            <div class="row">
                                <div class="col-md-2">
                                    <a href="{{route('inventory.create')}}" class="btn btn-primary" style="margin-top: 30px;">Add Inventory</a>
                                </div>
                                <div class="col-md-10">
                                    <form role="form" method="get" enctype="multipart/form-data" action="{{route('inventory.list')}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Product</label>
                                                    <select class="form-control select2" name="product_id" required>
                                                        @foreach($product as $product)
                                                            <option value="{{$product->id}}">{{$product->name}}</option>
                                                        @endforeach
                                                    </select>
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
                                            <div class="col-md-2">
                                                <div class="form-group" >

                                                    <button type="submit" class="btn btn-success " style="margin-top: 30px;">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">



                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Inventory ID</th>
                                        <th>Product</th>
                                        <th>Quantity </th>
                                        <th>Date </th>
                                        <th>Price</th>
                                        {{--<th>Parent Category</th>--}}
                                        <th>Vendor</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inventory as $inv)
                                            <tr>
                                                <td>{{$inv->id}}</td>
                                                <td>{{$inv->product->name??''}}({{$inv->product->company??''}})</td>
                                                <td>{{$inv->quantity}}</td>
                                                <td>{{$inv->create_date}}</td>
                                                <td>{{$inv->price}}</td>
                                                <td>{{$inv->vendor}}</td>
                                                <td>{{$inv->remarks}}</td>
                                                <td><a href="{{route('inventory.edit',['id'=>$inv->id])}}">Edit</a></td>
                                                @endforeach
                                            </tr>
                                    </tbody>
                                </table>
                                {{$inventory->appends(request()->input())->links()}}
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

