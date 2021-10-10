@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Inventory</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('inventory.list')}}">Inventory</a></li>
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
                                <h3 class="card-title">Inventory Add</h3>
                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('inventory.update', ['id'=>$inventory->id])}}">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Product Name</label>
                                                <select class="form-control select2" name="product_id">
                                                    @foreach($products as $product)
                                                        <option value="{{$product->id}}" @if($inventory->product_id == $product->id){{'selected'}}@endif>{{$product->name}}({{$product->company}})</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Quantity </label>
                                                <input type="number" name="quantity" class="form-control" placeholder="Quantity"  required value="{{$inventory->quantity}}">
                                            </div>
                                            <!-- /.form-group -->

                                            <!-- /.form-group -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Price  </label>
                                                <input type="number" name="price" class="form-control" placeholder="Price"  required value="{{intval($inventory->price)}}">
                                            </div>

                                            <div class="form-group">
                                                <label>Vendor </label>
                                                <input type="text" name="vendor" class="form-control" placeholder="Vendor"  required value="{{$inventory->vendor}}">
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Purchase Date</label>
                                                <input type="date" name="create_date" class="form-control" placeholder="Y-m-d"  required value="{{$inventory->create_date}}">
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                        <!-- /.col -->
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>	Remarks  </label>
                                                <textarea name="remarks" rows="5" class="form-control" placeholder="Remarks">{{$inventory->remarks}}</textarea>
                                            </div>


                                            <!-- /.form-group -->
                                        </div>
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

