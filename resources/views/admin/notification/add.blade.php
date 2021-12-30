@extends('admin.admin')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Notification</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('area.list')}}">Notification</a></li>
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
                                <h3 class="card-title">Notification Add</h3>
                            </div>
                            <!-- /.card-header -->

                            <form role="form" method="post" action="" enctype="multipart/form-data">
                                @csrf

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Partner/Customer </label>
                                                <select name="type" required class="form-control">
                                                    <option value="customer">Customer</option>
                                                    <option value="partner">Partner</option>
                                                </select>
                                            </div>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="title" class="form-control" placeholder="Title"  required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Body </label>
                                                <textarea class="form-control" name="description" rows="5"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Image</label>
                                                <div class="input-group">
                                                    <div class="custom-file">
                                                        <input type="file" name="image" class="custom-file-input"  accept="image/*" id="imgInp">
                                                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                                    </div>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="">Upload</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <img id="blah" src="#"  alt="" style="width:250px;height:100px;"/>
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

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example2" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th> ID</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                         
                                        <th>Userid</th>
                                        <th>Type</th>
                                        
                                        <th>Date</th>
                                        
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($notifications as $notification)
                                        <tr>
                                            <td>{{$notification->id}}</td>
                                            <td>{{$notification->title}}</td>
                                            <td>{{$notification->description}}</td>
                                            
                                            <td>{{$notification->user_id}}</td>
                                            <td>{{$notification->type}}</td>
                                            <td>{{$notification->created_at}}</td>
                                           
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>

                                       {{$notifications->appends(request()->input())->links()}}

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


    </div>
    <!-- ./wrapper -->

    <script>
  imgInp.onchange = evt => {
  const [file] = imgInp.files
  if (file) {
    blah.src = URL.createObjectURL(file)
  }
}
</script> 
 

@endsection


