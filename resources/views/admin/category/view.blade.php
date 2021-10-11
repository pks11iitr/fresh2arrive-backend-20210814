@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Categories</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Category</li>
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
                                <a href="{{route('category.create')}}" class="btn btn-primary">Add Category</a>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Category ID</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Earn Upto</th>
                                        <th>Isactive</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                            @foreach($category as $category)
                                <tr>
                                    <td>{{$category->id}}</td>
                                    <td>{{$category->name}}</td>
                                    <td><img src="{{$category->image}}" height="50" width="100"/></td>
                                    <td>{{$category->earn_upto}}</td>
                                    <td>{{$category->isactive?'Active':'Inactive'}}</td>
                                     <td><a href="{{route('category.edit',['id'=>$category->id])}}">Edit</a></td>
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

