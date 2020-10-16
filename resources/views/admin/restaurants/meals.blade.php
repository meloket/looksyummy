@extends('admin.meals.base')
@section('action-content')
<!-- Main content -->
<section class="content">
  <div class="box">
  <div class="box-header">
    <div class="row">
        <div class="col-sm-8">
          <h3 class="box-title">List of Meals</h3>
        </div>
        <div class="col-sm-4">
            
          <a class="btn btn-primary" href="{{ route('meals.create') }}">Add new meal</a>
        </div>
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
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Photo</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Username</th>
                <th width="12%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">User Type</th>
                <th width="12%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">User Role</th>
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Category</th>
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Title</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Restaurant</th>
                <th tabindex="0" aria-controls="example2" rowspan="1" colspan="2" aria-label="Action: activate to sort column ascending">Action</th>
              </tr>
            </thead>
            <tbody>
      			@if(isset($meals) && $meals->count() > 0)
                  @foreach ($meals as $meal)
                      <tr role="row" class="odd">
                        <td width="15%">
                            <img class="img-responsive" alt="" src="{{ asset(env('MEAL_PIC_MAX', '/storage/photos/thumb/').$meal->photo) }}" />
                        </td>
                        <td width="15%">{{ $meal['User']['username'] }}</td>
              				  <td width="15%">
                        @if(isset($meal['User']['user_type']) && $meal['User']['user_type'] == "1")
                          Customers
                        @endif

                        @if(isset($meal['User']['user_type']) && $meal['User']['user_type'] == "2")
                          Business
                        @endif

                        </td>
              				  <td width="15%">{{ $meal['User']['user_role'] }}</td>
                        <td width="15%">{{ $meal['mealCategory']['name'] }}</td>
                        <td width="15%">{{ $meal->title }}</td>
                        <td width="15%"><a href="/admin/restaurants/meals/{{ $meal['restaurant_id'] }}">{{ $meal['Restaurant']['place_name'] }}</a></td>
                        <td>
                         <select onChange="javascript:doLoad(1)"><option>Select</option>        					
            						    <option onChange="javascript:doLoad(1)">Modify</option>                            
                          </select>    
                        </td>

                    </tr>
                  @endforeach
      			@endif
            </tbody>
            <tfoot>
              <tr>
                <th rowspan="1" colspan="1">Meals</th>
                <th rowspan="1" colspan="1">Username</th>
                <th rowspan="1" colspan="1">User Type</th>
                <th rowspan="1" colspan="1">User Role</th>
                <th rowspan="1" colspan="1">Category</th>
                <th rowspan="1" colspan="1">Title</th>
                <th rowspan="1" colspan="1">Restaurant</th>
                <th rowspan="1" colspan="2">Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($meals)}} of {{count($meals)}} entries</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $meals->links() }}
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