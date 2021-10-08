@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Coupon</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('category.list')}}">Coupons</a></li>
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
                                <h3 class="card-title">Coupon Add</h3>
                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" enctype="multipart/form-data" action="">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Code </label>
                                                <input type="text" name="code" class="form-control" placeholder="Code"  required>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Discount Type </label>
                                                <input type="text" name="discount_type" class="form-control" placeholder="Discount Type"  required>
                                            </div>




                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Minimum Order </label>
                                                <input type="text" name="minimum_order" class="form-control" placeholder="Minimum Order"  required>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Maximum Discount </label>
                                                <input type="text" name="maximum_discount" class="form-control" placeholder="Maximum Discount"  required>
                                            </div>




                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-3">

                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Expiry Date </label>
                                                <input type="text" name="expiry_date" class="form-control" placeholder="Expiry Date"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Usage Type </label>
                                                <input type="text" name="usage_type" class="form-control" placeholder="Usage Type"  required>
                                            </div>

                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Discount </label>
                                                <input type="text" name="discount" class="form-control" placeholder="Discount"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Isactive</label>
                                                <select class="form-control select2" name="isactive">
                                                    <option value="">Please Select Status</option>
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>
                                                </select>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    </div>
                                        <div class="row">
                                        <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Description </label>
                                            <textarea  name="description" class="form-control" placeholder="Description" rows="5"  required></textarea>
                                        </div>
                                        </div>
                                        <!-- /.row -->
                                    </div>
                                    <!-- /.row -->
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

