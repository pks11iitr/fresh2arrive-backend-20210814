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

                            <form role="form" method="post" action="{{route('timeslot.update', ['id'=>$timeslot->id])}}">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control" placeholder="Name"   value="{{$timeslot->name}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>From Time </label>
                                                <input type="time" name="from_time" class="form-control"  value="{{$timeslot->from_time}}" placeholder="Name"  required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>To Time </label>
                                                <input type="time" name="to_time" class="form-control" placeholder="Display Pack Size"   value="{{$timeslot->to_time}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Order Till</label>
                                                <input type="time" value="{{$timeslot->order_till}}" name="order_till" class="form-control"required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label> Is Active </label>
                                                <select class="form-control select2" name="isactive">
                                                    <option value="1" @if($timeslot->isactive==1){{'selected'}}@endif>Active</option>
                                                    <option value="0" @if($timeslot->isactive==0){{'selected'}}@endif>Inactive</option>

                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.form-group -->
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label> Day </label>
                                                <select class="form-control select2" name="day">
                                                    <option value="0" selected>--Select Day--</option>
                                                    <option value="1" @if($timeslot->day==1){{'selected'}}@endif >Monday</option>
                                                    <option value="2" @if($timeslot->day==2){{'selected'}}@endif >Tuesday</option>
                                                    <option value="3" @if($timeslot->day==3){{'selected'}}@endif >Wednesday</option>
                                                    <option value="4" @if($timeslot->day==4){{'selected'}}@endif >Thursday</option>
                                                    <option value="5" @if($timeslot->day==5){{'selected'}}@endif >Friday</option>
                                                    <option value="6" @if($timeslot->day==6){{'selected'}}@endif >Saturday</option>
                                                    <option value="7" @if($timeslot->day==7){{'selected'}}@endif >Sunday</option>

                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Slot Capacity</label>
                                                <input type="text" name="slot_capacity" class="form-control" value="{{$timeslot->slot_capacity}}">
                                            </div>
                                        </div>
                                    </div>
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

