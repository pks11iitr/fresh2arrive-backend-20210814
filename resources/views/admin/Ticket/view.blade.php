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
                                <a href="{{route('partners.create')}}" class="btn btn-primary">Add Partner</a>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
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

