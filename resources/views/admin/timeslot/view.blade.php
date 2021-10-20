@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Time Slot</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Time Slot</li>
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
                                    <a href="{{route('timeslot.create')}}" class="btn btn-primary">Add Time Slot</a>
                                </div>
                               {{-- <div class="col-md-9">
                                    <form role="form" method="get" enctype="multipart/form-data" action="{{route('timeslot.list')}}">
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
                                </div>--}}
                            </div>


                        </div>
                        <div class="card">

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th> ID</th>

                                        <th>Name</th>
                                        <th>From Time</th>
                                        <th>To Time Price</th>
                                        <th>Order Till</th>
                                        <th>Isactive</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>


                                    @foreach($Timeslot as $Timeslot)
                                        <tr>
                                            <td>{{$Timeslot->id}}</td>

                                            <td>{{$Timeslot->name}}</td>
                                            <td>{{$Timeslot->from_time}}</td>
                                            <td>{{$Timeslot->to_time}}</td>
                                            <td>{{$Timeslot->order_till}}</td>
                                            <td>{{$Timeslot->isactive==1?'Active':'Inactive'}}</td>
                                            <td><a href="{{route('timeslot.edit',['id'=>$Timeslot->id])}}">Edit</a></td>
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

