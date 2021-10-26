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

                                        <div class="col-md-4">
                                            <b>Ticket Type</b> -   <b class="text-danger">{{$Ticket->ticket_type}}</b>
                                        </div>

                                        <div class="col-md-4">
                                            <b>RefID</b> -   <b class="text-danger">{{$Ticket->refid}}</b>
                                        </div>

                                    </div>
                                    <br/>
                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Customer Comments </label>
                                                <textarea class="form-control" readonly rows="5" name="admin_comments"
                                                >{{$Ticket->customer_comments}}</textarea>
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Partner Comments </label>
                                                <textarea class="form-control" readonly rows="5" name="admin_comments"
                                                >{{$Ticket->partner_comments}}</textarea>
                                            </div>
                                        </div>


                                        <div class="col-md-6">

                                        </div>
                                    </div>



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
                                        </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                                <!-- /.card-body -->
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <table id="example121" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Product Name</th>
                                    <th>Packet Count</th>
                                    <th>Issue</th>
                                    <th>Image</th>
                                    <th>Date</th>


                                </tr>
                                </thead>
                                <tbody>


                                @foreach($Ticket->ticket_items as $Ticketitem)
                                    <tr>
                                        <td>{{$Ticketitem->id}}</td>
                                        <td>{{$Ticketitem->order_details->name}}</td>
                                        <td>{{$Ticketitem->packet_count}}</td>
                                        <td>{{$Ticketitem->issue}}</td>
                                        <td><img src="{{$Ticketitem->image}}"  style="width:100px;height:50px"/></td>
                                        <td>{{$Ticketitem->	created_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                           {{-- {{$Tickets->appends(request()->input())->links()}}--}}
                        </div>





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

