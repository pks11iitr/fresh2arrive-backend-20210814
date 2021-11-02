@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Product</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('products.list')}}">Products</a></li>
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
                                <h3 class="card-title">Product Add</h3>
                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('products.store')}}">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Category</label>
                                                <select class="form-control select2" name="category_id">
                                                    @foreach($categories as $category)
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Company</label>
                                                <input type="text" name="company" class="form-control" placeholder="Company"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Product Name </label>
                                                <input type="text" name="name" class="form-control" placeholder="Name"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Display Pack Size</label>
                                                <input type="text" name="display_pack_size" class="form-control" placeholder="Display Pack Size"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Price Per Unit</label>
                                                <input type="number" name="price_per_unit" class="form-control" placeholder="Price Per Unit"  step="0.1" required>
                                            </div>
                                            <!-- /.form-group -->

                                        </div>
                                        <!-- /.col -->

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Cut Price Per Unit</label>
                                                <input type="number" name="cut_price_per_unit" class="form-control" placeholder="Cut Price Per Unit" step="0.1" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Unit Name </label>
                                                <select class="form-control select2" name="unit_name">
                                                    <option value="">Please Select</option>
                                                    @foreach(['Kg', 'Pcs.', 'Gm', 'Dozen','Ltr','ML','Pack'] as $val)
                                                    <option value="{{$val}}">{{$val}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Packet Price</label>
                                                <input type="number" name="packet_price" class="form-control" placeholder="Packet Price"  required step="0.1">
                                            </div>

                                            <div class="form-group">
                                                <label>Consumed Quantity </label>
                                                <input type="text" name="consumed_quantity" class="form-control" placeholder="Consumed Quantity "  required>
                                            </div>

                                            <div class="form-group">
                                                <label> Tag</label>
                                                <select class="form-control select2" name="tag">
                                                    <option value="">Please Select Status</option>
                                                    <option value="Deal of the day">Deal of the day</option>
                                                    <option value="Limited Stock">Limited Stock</option>
                                                </select>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>






                                        <div class="col-md-4">

                                            <div class="form-group">
                                                <label>Min Qty</label>
                                                <input type="number" name="min_qty" class="form-control" placeholder="Min Qty"  required>
                                            </div>

                                            <div class="form-group">
                                                <label>Max Qty</label>
                                                <input type="number" name="max_qty" class="form-control" placeholder="Max Qty"  required>
                                            </div>

                                            <div class="form-group">
                                                <label> Product Type </label>
                                                <select class="form-control select2" name="show_only_pack_price">
                                                    <option value="0">Fruits/Vegie</option>
                                                    <option value="1">Grocery</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Commissions </label>
                                                <input type="number" name="commissions" class="form-control" placeholder="Commissions"  required>
                                            </div>


                                            <!-- /.form-group -->
                                        </div>





                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>  Image</label>
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
                                            <div class="form-group">
                                                <label> Is Active </label>
                                                <select class="form-control select2" name="isactive">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>

                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label> Is Hot </label>
                                                <select class="form-control select2" name="is_hot">
                                                    <option value="1">Yes</option>
                                                    <option value="0">No</option>

                                                </select>
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

