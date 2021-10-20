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
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('timeslot.list')}}">Time Slot</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- left column -->
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Time Slot</h3>
                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" action="{{route('timeslot.store')}}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control" placeholder="Name"  required>
                                            </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>From Time </label>
                                                    <input type="time" name="from_time" class="form-control" placeholder="Name"  required>
                                                </div>
                                                </div>
                                            <div class="col-md-2">
                                            <div class="form-group">
                                                <label>To Time </label>
                                                <input type="time" name="to_time" class="form-control" placeholder="Display Pack Size"  required>
                                            </div>
                                            </div>
                                            <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Order Till</label>
                                                <input type="time" name="order_till" class="form-control"required>
                                            </div>
                                            </div>
                                                <div class="col-md-2">
                                                <div class="form-group">
                                                    <label> Is Active </label>
                                                    <select class="form-control select2" name="isactive">
                                                        <option value="1">Active</option>
                                                        <option value="0">Inactive</option>

                                                    </select>
                                                </div>
                                                </div>

                                            </div>
                                            <!-- /.form-group -->

                                        </div>
                                        <!-- /.col -->


                                        <!-- /.row -->


                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                <!-- /.card-body -->

                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                    <!--/.col (right) -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->


    </div>
    <!-- ./wrapper -->
@endsection

