@extends('admin.meals.base')
@section('action-content')
<!-- Main content -->
<section class="content">
  <div class="box">
  <div class="box-header">
    <div class="row">
        <div class="col-sm-8">
          <h3 class="box-title">Analytics Details - {{ strtoupper($type) }}</h3>
        </div>
        <!--<div class="col-sm-4">
          <a class="btn btn-primary" href="{{ route('meals.create') }}">Add new meal</a>
        </div>-->
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
      <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6"></div>
      </div>
      
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
		  <h4>
		  @isset($meal)
		  {{ $meal }}
		  @endisset
		  </h4>
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Photo</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Username</th>
                <th width="12%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Email</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Datetime</th>
                
              </tr>
            </thead>
            <tbody>
      			@if(isset($data) && $data->count() > 0)
                  @foreach ($data as $da)
                      <tr role="row" class="odd">
                        <td width="15%">
                            <img class="img-responsive" alt="" src="{{ asset(env('MEAL_PIC_MAX', '/storage/photos/thumb/').$da['user']['profile_pic']) }}" />
                        </td>
                        <td width="15%">{{ $da['user']['username'] }}</td>              				  
              				  <td width="15%">{{ $da['user']['email'] }}</td>                       
                        <td width="15%">{{ $da->created_at->diffForHumans() }}</td>
                        

                    </tr>
                  @endforeach
      			@endif
            </tbody>
            <!--<tfoot>
              <tr>
                <th width="20%" rowspan="1" colspan="1">Users</th>
                <th rowspan="1" colspan="2">Action</th>
              </tr>
            </tfoot>-->
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($data)}} of {{count($data)}} entries</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $data->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.box-body -->
</div>
    </section>
    <!-- /.content -->
  
@endsection

<script>
function doLoad(a) {

alert('Coming Soon')
  
}
</script>