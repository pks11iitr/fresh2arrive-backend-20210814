@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Partners</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                            <li class="breadcrumb-item active">Partner</li>
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
                        <div class="card">
                            <div class="card-header">
                                <div class="row">

                                    <div class="col-md-10">
                                        <form role="form" method="get" enctype="multipart/form-data" action="{{route('partners.list')}}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <select class="form-control select2" name="partner_id" >
                                                            <option value="">--Select Parner</option>
                                                            @foreach($allpartners as $partner)
                                                                <option value="{{$partner->id}}" @if($partner->id == request('partner_id')){{'selected'}}@endif>{{$partner->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <input type="text" name="search" class="form-control" placeholder="Search Only Partner Name"  required>
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
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>mobile </th>
                                        <th>{{$start3.'---'.$end3}}</th>
                                        <th>{{$start2.'---'.$end2}}</th>
                                        <th>{{$start1.'---'.$end1}}</th>
                                        <th>{{$start0.'---'.$end0}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($partners as $partner)
                                        <tr>
                                            <td>{{$partner->id}}</td>
                                            <td>{{$partner->name}}</td>
                                            <td>{{$partner->mobile}}</td>
                                            <td>{{$partners_earnings3[$partner->id]??0}}</td>
                                            <td>{{$partners_earnings2[$partner->id]??0}}</td>
                                            <td>{{$partners_earnings1[$partner->id]??0}}</td>
                                            <td>{{$partners_earnings0[$partner->id]??0}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    {{$partners->appends(request()->input())->links()}}

                                </table>
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

