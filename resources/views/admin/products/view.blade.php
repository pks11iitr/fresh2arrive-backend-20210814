@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Products</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Product</li>
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
                                <div class="col-md-3">
                                    <a href="{{route('products.create')}}" class="btn btn-primary">Add Product</a>
                                </div>
                                <div class="col-md-9">
                                    <form role="form" method="get" enctype="multipart/form-data" action="{{route('products.list')}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <input type="text" name="search" class="form-control" placeholder="Search Only Product Name"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success ">Search</button>
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
                                        <th>Product ID</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Display Pack Size</th>
                                        <th>Packet Price</th>
                                        <th>Commissions</th>
                                        <th>Tag</th>
                                        <th>Isactive</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($products as $products)
                                        <tr>
                                            <td>{{$products->id}}</td>
                                            <td>{{$products->name}}</td>
                                            <td><imc src="{{$products->image}}" height="50" width="100"/></td>
                                            <td>{{$products->display_pack_size}}</td>
                                            <td>{{$products->price_per_unit}}</td>
                                            <td>{{$products->commissions}}</td>
                                            <td>{{$products->tag}}</td>
                                            <td>{{$products->isactive==1?'Active':'Inactive'}}</td>
                                            <td><a href="{{route('products.edit',['id'=>$products->id])}}">Edit</a></td>
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

