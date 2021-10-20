@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tickets</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Tickets</li>
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
                            <br/>
                            <div class="row">
                                <div class="col-md-9">
                                    <form role="form" method="get" enctype="multipart/form-data" action="{{route('ticket.list')}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>ReferID</label>
                                                    <input type="text" name="search" class="form-control" placeholder="Refid"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Partner</label>
                                                    <select class="form-control select2" name="partner_id" required>
                                                        @foreach($partner as $partner)
                                                            <option value="{{$partner->id}}">{{$partner->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success " style="margin-top: 30px">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            <!-- /.card-header -->
                            </div>
                            <div class="card-body">
                                <table id="example121" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Refid</th>
                                        <th>Customer Name </th>
                                        <th>Partner Name</th>
                                        <th>Order Refid</th>
                                        <th>Customer Comments</th>
                                        <th>Partner Comments</th>
                                        <th>Partner Approved</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($Ticket as $Ticket)
                                        <tr>
                                            <td>{{$Ticket->id}}</td>
                                            <td>{{$Ticket->refid}}</td>
                                            <td>{{$Ticket->customer->name}}</td>
                                            <td>{{$Ticket->partners_name->name}}</td>
                                            <td>{{$Ticket->order->refid}}</td>
                                            <td>{{$Ticket->customer_comments}}</td>
                                            <td>{{$Ticket->partner_comments}}</td>
                                            <td>{{$Ticket->partner_approved}}</td>
                                            <td>{{$Ticket->status=='Open'?'Open':'Resolve'}}</td>
                                            <td><a href="{{route('ticket.edit',['id'=>$Ticket->id])}}">Edit</a></td>
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

