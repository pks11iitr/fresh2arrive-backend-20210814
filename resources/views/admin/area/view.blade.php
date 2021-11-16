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
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Area</li>
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
                                <a href="{{route('area.create')}}" class="btn btn-primary">Add Area</a>
                                <a href="{{route('area.import')}}" class="btn btn-warning">Import</a>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th> ID</th>
                                        <th>Name</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Pincode</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($areas as $area)
                                        <tr>
                                            <td>{{$area->id}}</td>
                                            <td>{{$area->name}}</td>
                                            <td>{{$area->city}}</td>
                                            <td>{{$area->state}}</td>
                                            <td>{{$area->pincode}}</td>
                                            <td>{{$area->isactive==1?'Active':'Inactive'}}</td>
                                            <td><a href="{{route('area.edit',['id'=>$area->id])}}">Edit</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>

                                       {{$areas->appends(request()->input())->links()}}

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

