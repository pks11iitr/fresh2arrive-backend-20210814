@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Banner</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('banners.list')}}">Banner</a></li>
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
                                <h3 class="card-title">Banner Add</h3>
                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" enctype="multipart/form-data" action="{{route('banners.update', ['id'=>$banner->id])}}">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Entity Type</label>
                                                <select class="form-control select2" name="type">
                                                    @foreach(['banner', 'refer', 'product', 'share'] as $type)
                                                        <option value="{{$type}}" @if($banner->type==$type){{'selected'}}@endif>{{ucfirst($type)}} Banner</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group">
                                                <label>Image</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input" id="exampleInputFile" accept="image/*">
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                    </div>

                                                </div>
                                                <br>
                                                <img src="{{$banner->image}}" height="100" width="200"/>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Isactive</label>
                                                <select class="form-control select2" name="isactive">
                                                   <option value="1" @if($banner->isactive==1){{'selected'}}@endif>Yes</option>
                                                    <option value="0" @if($banner->isactive==0){{'selected'}}@endif>No</option>
                                                </select>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                        @if($banner->type=='product')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Banner Products</label>
                                                <select class="form-control select2" name="product_ids[]" multiple id="product_ids">
                                                    @foreach($products as $p)
                                                        @foreach($banner->products as $bp)
                                                        <option value="{{$p->id}}" @if($bp->id == $p->id){{'selected'}}@endif >{{$p->name.'('.$p->company.')'}}</option>
                                                            @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- /.form-group -->
                                        </div>
                                    @endif
                                        <!-- /.col -->
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
@section('scripts')
    <script>

        $("#product_ids").select2();

    </script>
@endsection

