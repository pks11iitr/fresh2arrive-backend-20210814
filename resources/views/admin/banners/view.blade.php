@extends('admin.admin')
@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Banners</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
              <li class="breadcrumb-item active">Banner</li>
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
                <a href="{{route('banners.create')}}" class="btn btn-primary">Add Banner</a>

              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                      <th>Banner ID</th>
                      <th>Image</th>
                      <th>Type</th>
                      {{--<th>Parent Category</th>--}}
                      <th>Isactive</th>
                      <th>Action</th>
                  </tr>
                  @foreach($banners as $banner)
                      <tr>
                          <td>{{$banner->id}}</td>
                          <td><img src="{{$banner->image}}" height="50" width="100"/></td>
                          <td>{{$banner->type}}</td>
                          {{--<th>Parent Category</th>--}}
                          <td>{{$banner->isactive?'Active':'Inactive'}}</td>
                          <td><a href="{{route('banners.edit', ['id'=>$banner->id])}}">Edit</a></td>
                      </tr>
                  @endforeach
                  </thead>
                  <tbody>

                  </tbody>
                </table>
                  {{$banners->links()}}
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

