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

                                <div class="col-md-10">
                                    <form role="form" method="get" enctype="multipart/form-data" action="{{route('customers.list')}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <select class="form-control" name="search_type">
                                                        <option value="">--Select Search Type--</option>
                                                        <option value="1">By Name</option>
                                                        <option value="2">By Mobile</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input type="text" name="search" class="form-control" placeholder="Search Only Customer Name"  required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-success ">Search</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> 



<div class="row"> 
<div class="col-md-10">
    <form role="form" method="get"  action="{{route('customers.list')}}">
        @csrf
        <div class="row">
        
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Partner</label>
            <select class="form-control select2"  name="partners"  id="Partner">
                <option value="">--Select Partner--</option>
                            @foreach($partnersss as $p) 
                    <option value="{{$p->id}}">{{$p->name}}</option> 
                            @endforeach
                        </select>
                    </div>
                    <!-- /.form-group -->
                </div>
                                   
           
            <div class="col-md-4">
                <div class="form-group">
                    <br/>
                    
                    <button type="submit"  class="btn btn-danger">Show All Customers</button>
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
                                        <th>Area</th>
                                        <th>Assigned Partner</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>


                       

                                    </thead>
                                    <tbody>
                                        @foreach($customer as $customers)
                                        

                                       <?php if(empty($customers->areas_name->name)){
                                            $area_name="NA";
                                        }else{
                                            $area_name=$customers->areas_name->name;
                                        }?>
                                            <tr>
                            <td>{{$customers->id}}</td>
                            <td>{{$customers->name}}</td>
                            <td>{{$customers->mobile}}</td>
                            <td>{{$area_name}}</td>
                            <td>{{$customers->partner->name}}</td>
                            <td>{{\App\Models\Wallet::balance($customers->id)}}</td>
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
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot> Total Customer - <b class="text-danger">{{$count}}</b></tfoot>

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
@endsection

