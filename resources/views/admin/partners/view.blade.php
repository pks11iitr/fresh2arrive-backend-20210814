@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Partners</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Partner</li>
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
                                <div class="row">

                                    <div class="col-md-10">
                                        <form role="form" method="get" enctype="multipart/form-data" action="{{route('partners.list')}}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <select class="form-control" name="search_type">
                                                            <option value="">--Select Search Type--</option>
                                                            <option value="1">By Name</option>
                                                            <option value="2">By Mobile</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <input type="text" name="search" class="form-control" placeholder="Search Only Partner Name"  required>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                <select name="area" class="form-control">
                                                @foreach($areas as $area)
                                                 <option value="{{$area->id}}">{{$area->name}} {{$area->remarks}}</option>
                                                @endforeach
                                                </select>
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
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>mobile </th>
                                        <th>Area </th>


                                        <th>City</th>
                                        <th>Pincode</th>
                                        <th>State</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($partners as $partner)
                                        <tr>
                                            <td>{{$partner->id}}</td>
                                            <td>{{$partner->name}}</td>
                                            <td>{{$partner->mobile}}</td>
                                         <td>@foreach($partner->areas as $area){{$area->name}},@endforeach</td>
                                            <td>{{$partner->city}}</td>
                                            <td>{{$partner->pincode}}</td>
                                            <td>{{$partner->state}}</td>
                                            <td>{{$partner->status?'Active':'Inactive'}}</td>
                                            <td><a href="{{route('partners.edit',['id'=>$partner->id])}}">Edit</a></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    {{$partners->appends(request()->input())->links()}}

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

