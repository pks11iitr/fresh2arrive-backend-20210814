@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Customers</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Customer</li>
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

                                <div class="col-md-12">
                                    <form role="form" method="get" enctype="multipart/form-data" action="{{route('customers.list')}}">
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
                                                    <input type="text" name="search" class="form-control" placeholder="Search Only Customer Name">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select class="form-control select2"  name="partners"  id="Partner">
                                                        <option value="">--Select Partner--</option>
                                                        @foreach($partnersss as $p)
                                                            <option value="{{$p->id}}">{{$p->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <!-- /.form-group -->
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select class="form-control" name="customer_type">
                                                        <option value="">--Select Customer Type--</option>
                                                        <option value="inactive">In Active</option>
                                                        <option value="slow-moving">Slow Moving</option>
                                                        <option value="platinum">Platinum</option>
                                                        <option value="silver">Silver</option>
                                                        <option value="gold">Gold</option>
                                                        <option value="bronze">Bronze</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success ">Search</button>
                                                    <a href="{{route('customers.export')}}" class="btn btn-warning">Export List In Excel</a>
                                                 
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






                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>mobile</th>
                                        <th>Orders  </th>
                                        <th>Area</th>
                                        <th>Assigned Partner</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        <th>Wallet</th>
                                        <th>Registered On</th>
                                        <th>Registered Date</th>
                                    </tr>



                                    </thead>
                                    <tbody>
                                        @foreach($customer as $customers)


                            <tr>
                            <td>{{$customers->id}}</td>
                            <td>{{$customers->name}} </td>
                            <td>{{$customers->mobile}}</td>
                            <td>{{$customers->orders_count}} </td>
                            <td>{{$customers->area}}<br/>/{{$customers->street}}</td>
                            <td>{{$customers->partner->name??'Not Alloted'}}</td>
                            <td><a href="{{route('customers.history',['id'=>$customers->id])}}" class="btn btn-warning">{{\App\Models\Wallet::balance($customers->id)}}</a></td>
                            <?php
                            if($customers->status == 1 ){
                                $status="Active";
                            }elseif($customers->status == 2){
                                $status="Block";
                            }else{
                                $status="Inactive";
                            }  ?>

                        <td>{{$status}}</td>
            <td><a href="{{route('customers.edit',['id'=>$customers->id])}}">Edit</a></td>
            <td><a href="javascript:void(0)" class='btn btn-primary' onclick="openWalletPanel('{{$order->id??''}}', '{{route('user.wallet.balance', ['id'=>$customers->id])}}', {{$customers->id}})">Add/Revoke Balance</a></button></td>
                                                <td>{{date('d/m/Y',strtotime($customers->created_at))}}</td>
                                                <td>{{date('h:i:a',strtotime($customers->created_at))}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot> Total Customer - <b class="text-danger">{{$customer->total()}}</b></tfoot>

                                </table>
                                {{$customer->appends(request()->input())->links()}}
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

    <script>
        $("#Partner").select2();
    </script>

    <div class="modal fade show" id="modal-lg" style="display: none; padding-right: 15px;" aria-modal="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add/Remove Cashback/Wallet Balance&nbsp;&nbsp;&nbsp;&nbsp;Balance:<span id="user-wallet-balance"></span>&nbsp;&nbsp;Cashback:<span id="user-wallet-cashback"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="booking-form-section">
                    <form role="form" method="post" enctype="multipart/form-data" action="{{route('wallet.add.remove')}}">
                        @csrf
                        <input type="hidden" name="order_id" id="wallet-order-id" value="1">
                        <input type="hidden" name="user_id" id="wallet-user-id" value="1">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Select Add/Revoke</label>
                                        <select class="form-control" name="action_type" required="">
                                            <option value="">Select Any</option>
                                            <option value="add">Add</option>
                                            <option value="revoke">Revoke</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Calculation Type</label>
                                        <select class="form-control" name="calculation_type" required="">
                                            <option value="">Select Any</option>
                                            <option value="fixed">Fixed Amount</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Type(Cashback/Wallet Balance)</label>
                                        <select class="form-control" name="amount_type" required="">
                                            <option value="">Select Any</option>
                                            <option value="cashback">Cashback</option>
                                            <option value="balance">Wallet Balance</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Amount</label>
                                        <input type="number" name="amount" class="form-control" required="" value="0.0" min="0.01" step=".01">
                                    </div>

                                </div>


                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Description</label>
                                <input type="text" name="wallet_text" class="form-control" required="" placeholder="Max 150 characters">
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
                {{--                <div class="modal-footer justify-content-between">--}}
                {{--                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                {{--                    <button type="button" class="btn btn-primary">Save changes</button>--}}
                {{--                </div>--}}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
    <script>
        function openWalletPanel(id, url, user_id){
            $("#wallet-order-id").val(id)
            $("#wallet-user-id").val(user_id)
            $.ajax({
                url:url,
                method:'get',
                datatype:'json',
                success:function(data){
//alert(data)
                    if(data.status=='success'){
//alert()
                        $("#user-wallet-balance").html(data.data.balance)
                        $("#user-wallet-cashback").html(data.data.cashback)

                    }

                }
            })
            $("#modal-lg").modal('show')

        }
    </script>
@endsection
