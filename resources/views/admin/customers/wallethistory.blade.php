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
                                        <th>user_id</th>
                                        <th>Date</th>
                                        <th>refid</th>
                                        <th>type</th>
                                        <th>amount_type  </th>
                                        <th>amount</th>
                                        <th>balance</th>
                                        <th>description</th>
                                        <!-- <th>iscomplete</th> -->
                                        <!-- <th>order_id</th> -->
                                        <!-- <th>order_id_response</th>
                                        <th>payment_id On</th>
                                        <th>payment_id_response</th> -->
                                       
                                    </tr>



                                    </thead>
                                    <tbody>
                                        @foreach($wallet as $customers) 

                                            <tr>
                                            <td>{{$customers->user_id}}</td>
                                            <td>{{$customers->created_at}}</td> 
                                            <td>{{$customers->refid}} </td>
                                            <td>{{$customers->type}}</td> 
                                            <td>{{$customers->amount_type}}</td>
                                            <td>{{$customers->amount}}</td>
                                            <td>{{$customers->balance}}</td>
                                            <td>{{$customers->description}}</td>
                                            
                                            <!-- <td>{{$customers->order_id}}</td> -->
                                            <!-- <td>{{$customers->order_id_response}}</td>
                                            <td>{{$customers->payment_id}}</td>
                                            <td>{{$customers->payment_id_response}}</td> -->
                                           
                             
                                            </tr>
                                        @endforeach
                                    </tbody>
                                   
                                </table>
                                {{$wallet->appends(request()->input())->links()}}
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
