@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Sales Report</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Order</li>
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
                            {{--                            <a href="{{route('orders.create')}}" class="btn btn-primary">Add Order</a>--}}
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <form role="form" method="get" enctype="multipart/form-data" action="{{route('orders.sale-report')}}">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>From Date</label>
                                                <input type="date" name="fromdate" class="form-control" placeholder="Search Only Product Name"  value="{{request('fromdate')}}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label>To Date</label>
                                            <div class="form-group">
                                                <input type="date" name="todate" class="form-control" placeholder="Search Only Product Name"  value="{{request('todate')}}">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Delivery Slot</label>
                                            <select class="form-control select2" name="timeslots[]" multiple>
                                                @foreach($timeslots as $ts)
                                                    <option value="{{$ts->id}}" @if(in_array($ts->id , request('timeslots')??[])){{'selected'}}@endif >{{$ts->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-success " style="margin-top: 30px">Search</button>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <a class="btn btn-info" href="{{route('orders.report')}}">Reset</a>
                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </div>
                        </div>


                        <div class="card">

                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Total Quantity</th>
                                        <th>Packet Size</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($quantities as $quantity)
                                        <tr>
                                            <td>{{$quantity->product->name??''}}</td>
                                            <td>{{$quantity->packet_count}}</td>
                                            <td>{{$quantity->product->display_pack_size??''}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if(count($quantities))
                                {{$quantities->appends(request()->input())->links()}}
                                @endif
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
@section('scripts')
    <script>
    $(".select2").select2();
    </script>
@endsection
