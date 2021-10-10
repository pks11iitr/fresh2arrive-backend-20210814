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
                        <div class="card">
                            <div class="card-header">
                                <a href="{{route('inventory.create')}}" class="btn btn-primary">Add Inventory</a>

                            </div>
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

