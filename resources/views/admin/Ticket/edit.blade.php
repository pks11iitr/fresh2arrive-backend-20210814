@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Ticket</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('ticket.list')}}">Ticket</a></li>
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
                                <h3 class="card-title">Ticket Update</h3>

                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" action="{{route('ticket.update',['id' =>$Ticket->id])}}">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Admin Comments </label>
                                                <textarea class="form-control" rows="5" name="admin_comments"
>{{$Ticket->admin_comments}}</textarea>
                                            </div>
                                        </div>


                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control select2" name="status">
                                                    <option value="">Please Select Status</option>
                                                    <option @if($Ticket->status=='Open') {{'selected=selected'}}@endif value="Open">Open</option>
                                                    <option @if($Ticket->status=='Resolved'){{'selected=selected'}}@endif value="Resolved">Resolved</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- /.col -->







                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
                                    </div>
                                </div>
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

