@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Order</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('orders.list')}}">Orders</a></li>
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
                                <h3 class="card-title">Order Add</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                            <form role="form" method="post" enctype="multipart/form-data" action="" onsubmit="return false">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Refid </label>
                                                <input type="text" name="refid" class="form-control" placeholder="Refid"  value="{{$order->refid}}" disabled>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>User</label>
                                                <input type="text" name="user_id" class="form-control" placeholder="User Id"   value="{{$order->customer->name.' ('.$order->user_id.')'}}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>Order Total </label>
                                                <input type="text" name="order_total" class="form-control" placeholder="Order Total"  value="{{$order->order_total}}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>Status  </label>
                                                <input type="text" name="status" class="form-control" placeholder="Status"  value="{{$order->status}}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>Is Paid  </label>
                                                <input type="text" name="is_paid" class="form-control" placeholder="Is Paid"  value="{{$order->is_paid==1?'yes':'no'}}" disabled>
                                            </div>
                                            <!-- /.form-group -->

                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Coupon Applied</label>
                                                <input type="text" name="coupon_applied" class="form-control" placeholder="Coupon Applied"  value="{{$order->coupon_applied}}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>Coupon Discount </label>
                                                <input type="text" name="coupon_discount" class="form-control" placeholder="Coupon Discount"  value="{{$order->coupon_discount}}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>Delivery Date  </label>
                                                <input type="date" name="delivery_date" class="form-control" placeholder="Delivery Date "  value="{{$order->delivery_date}}" disabled>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Delivery Time  </label>
                                                <input type="text" name="delivery_time" class="form-control" placeholder="Delivery Time  " value="{{$order->delivery_time}}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>Delivered At  </label>
                                                <input type="text" name="delivered_at" class="form-control" placeholder="Delivered At  "  disabled value="{{$order->delivered_at}}">
                                            </div>

                                            <div class="form-group">
                                                <label> Is Completed </label>
                                                <input type="text" name="is_paid" class="form-control" placeholder="Is Paid"  value="{{$order->is_completed==1?'yes':'no'}}" disabled>
                                            </div>

                                            <div class="form-group">
                                                <label>Echo Charges  </label>
                                                <input type="text" name="echo_charges" class="form-control" placeholder="Echo Charges "  value="{{$order->echo_charges??0}}" disabled>
                                            </div>
                                            <!-- /.col -->
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Delivery Partner</label>
                                                <input type="text" name="delivery_partner" class="form-control" placeholder="Delivery Partner"  value="{{$order->partner->name}}" disabled>
                                            </div>
                                        </div>

{{--                                        <div class="col-md-3">--}}

{{--                                            <div class="form-group">--}}
{{--                                                <label>Invoice Number </label>--}}
{{--                                                <input type="text" name="invoice_number" class="form-control" placeholder="Invoice Number "  required>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}


                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Delivery Partner Location </label>
                                                <input type="text" name="delivery_partner_location" class="form-control" placeholder="Delivery Partner Location "  value="{{$order->delivery_partner_location??''}}" disabled>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Delivery Image</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <img src="{{$order->delivery_image}}" height="50" weidth="100"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- /.row -->
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-primary" onclick="openWalletPanel('{{$order->id}}', '{{route('user.wallet.balance', ['id'=>$order->user_id])}}')" >Add/Revoke Balance</button>
                                        </div>
                                        <div class="col-md-6 text-right">
                                            @if($order->status=='confirmed')

                                                <a  align="right" class="btn btn-success " href="{{route('orders.updateStatus', ['user_id'=>$order->user_id,'order_id'=>$order->id])}}" >Process</a>

                                            @endif

                                        </div>
                                    </div>





                                </div>
                                <!-- /.card-body -->

                            </form>
                            </div>

                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Company</th>
                                        <th>Size</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->details as $detail)
                                        <tr>
                                            <td>{{$detail->name??''}}</td>
                                            <td>{{$detail->company??''}}</td>
                                            <td>Size: {{$detail->display_pack_size??''}}</td>
                                            <td>Quantity: {{$detail->packet_count??0}}</td>
                                            <td>Rs. {{$detail->price??0}}/Item</td>
                                            <td>Rs. {{($detail->price??0)*($detail->packet_count??0)}} Total</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                            </div>


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

    <!-- ./wrapper -->
@endsection
@section('scripts')
    <script>
        function openWalletPanel(id, url){
            $("#wallet-order-id").val(id)
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
