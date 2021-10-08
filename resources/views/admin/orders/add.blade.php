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

                            <form role="form" method="post" enctype="multipart/form-data" action="">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Refid </label>
                                                <input type="text" name="refid" class="form-control" placeholder="Refid"  required>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>User Id </label>
                                                <input type="text" name="user_id" class="form-control" placeholder="User Id"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Order Total </label>
                                                <input type="text" name="order_total" class="form-control" placeholder="Order Total"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Status  </label>
                                                <input type="text" name="status" class="form-control" placeholder="Status"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Is Paid  </label>
                                                <input type="text" name="is_paid" class="form-control" placeholder="Is Paid"  required>
                                            </div>
                                            <!-- /.form-group -->

                                        </div>
                                        <!-- /.col -->

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Payment Mode</label>
                                                <input type="text" name="payment_mode" class="form-control" placeholder="Payment Mode"  required>
                                            </div>
                                            <div class="form-group">
                                                <label>Address Id </label>
                                                <input type="text" name="address_id" class="form-control" placeholder="Address Id"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Use Points</label>
                                                <input type="text" name="use_points" class="form-control" placeholder="Use Points"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Use Balance </label>
                                                <input type="text" name="use_balance" class="form-control" placeholder="Use Balance "  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Balance Used  </label>
                                                <input type="text" name="balance_used" class="form-control" placeholder="Balance Used "  required>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>






                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Points Used</label>
                                                <input type="text" name="points_used" class="form-control" placeholder="Points Used"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Coupon Applied</label>
                                                <input type="text" name="coupon_applied" class="form-control" placeholder="Coupon Applied"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Coupon Discount </label>
                                                <input type="text" name="coupon_discount" class="form-control" placeholder="Coupon Discount"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Delivery Charge </label>
                                                <input type="text" name="delivery_charge" class="form-control" placeholder="Delivery Charge"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Delivery Date  </label>
                                                <input type="text" name="delivery_date" class="form-control" placeholder="Delivery Date "  required>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Delivery Slot </label>
                                                <input type="text" name="delivery_slot" class="form-control" placeholder="Delivery Slot"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Delivery Time  </label>
                                                <input type="text" name="delivery_time" class="form-control" placeholder="Delivery Time  "  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Delivered At  </label>
                                                <input type="text" name="delivered_at" class="form-control" placeholder="Delivered At  "  required>
                                            </div>

                                            <div class="form-group">
                                                <label> Is Completed </label>
                                                <select class="form-control select2" name="is_completed">
                                                    <option value="">Please Select Status</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Echo Charges  </label>
                                                <input type="text" name="echo_charges" class="form-control" placeholder="Echo Charges "  required>
                                            </div>
                                            <!-- /.col -->
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Delivery Partner</label>
                                                <input type="text" name="delivery_partner" class="form-control" placeholder="Delivery Partner"  required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Invoice Number </label>
                                                <input type="text" name="invoice_number" class="form-control" placeholder="Invoice Number "  required>
                                            </div>
                                        </div>


                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Delivery Partner Location </label>
                                                <input type="text" name="delivery_partner_location" class="form-control" placeholder="Delivery Partner Location "  required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label> Cashback Given  </label>
                                                <input type="text" name="cashback_given" class="form-control" placeholder="Cashback Given  "  required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Item Ticket Status  </label>
                                                <input type="text" name="item_ticket_status" class="form-control" placeholder="Item Ticket Status  "  required>
                                            </div>
                                        </div>

                                        <div class="col-md-3">

                                            <div class="form-group">
                                                <label>Partner Ticket Status  </label>
                                                <input type="text" name="partner_ticket_status" class="form-control" placeholder="Partner Ticket Status   "  required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> Delivery Image</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="delivery_image" class="custom-file-input" id="exampleInputFile" accept="image/*" required>
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="">Upload</span>
                                                    </div>
                                                </div>
                                            </div>
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

