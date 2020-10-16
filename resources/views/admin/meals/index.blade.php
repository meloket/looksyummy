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
        <div class="col-sm-8">
        {{ Form::open(array('url' => '/admin/meals', 'method' => 'GET')) }}
        
        Filter: 
        {{ Form::input('text', 'keyword', $keyword, ['style' => 'width: 300px;', 'class' => 'form-control1', 'placeholder' => 'By meal']) }}
        {!! Form::select('meal_category_id', $meal_categories, $meal_category_id, ['class' => 'form-control1', 'placeholder' => 'All']) !!}
        {{ Form::submit('Filter', array('class' => 'btn btn-primary')) }} <a href="/admin/meals?clear=1">Clear Filters</a>
        {{ Form::close() }} 
        </div>
        <div class="col-sm-6"></div>
      </div>
      
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th width="5%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Id</th>        				
                <th width="10%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Photo</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Username</th>
                <th width="10%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Type</th>
                <th width="10%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Category</th>
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Title</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Restaurant</th>
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Price</th>
                <th width="12%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Posted</th>
                <th tabindex="0" aria-controls="example2" rowspan="1" colspan="2" aria-label="Action: activate to sort column ascending">Action</th>
              </tr>
            </thead>
            <tbody>
      			@if(isset($meals) && $meals->count() > 0)
                  @foreach ($meals as $meal)
                      <tr role="row" class="odd">
                        <td>{{ $meal->id }}</td>

                        <td width="10%">
                            <img class="img-responsive" alt="" src="{{ asset(env('MEAL_PIC_MAX', '/storage/photos/thumb/').$meal->photo) }}" />
                        </td>
                        <td width="15%">{{ $meal['User']['username'] }}</td>
              				  <td width="10%">
                        @if(isset($meal['User']['user_type']) && $meal['User']['user_type'] == "1")
                          Customers
                        @endif

                        @if(isset($meal['User']['user_type']) && $meal['User']['user_type'] == "2")
                          Business
                        @endif

                        </td>
              				  <!--<td width="15%">{{ $meal['User']['user_role'] }}</td>-->
                        <td width="10%">{{ $meal['mealCategory']['name'] }}</td>
                        <td width="15%">{{ $meal->title }}</td>
                        <td width="15%">{{ $meal['Restaurant']['place_name'] }}</td>
                        <td width="10%" class="text-right">{{ $meal->amount }}</td>
                        <!--<td width="15%">
                        @if($meal->active) 
                          {{ "Yes" }}
                        @else
                          {{ "No" }}
                        @endif
                        </td>-->
                        <td width="25%">{{ $meal->created_at }}</td>
                        <td>
                        <a href="/admin/meals/edit/{{ $meal->id }}" class="text-center">Edit</a> |                         
                        <a href="/admin/meals/delete/{{ $meal->id }}" class="text-center" onclick="javascript:return confirm('OK to confirm.')">Delete</a>
                        </td>

                    </tr>
                    
                  @endforeach
      			@endif
            </tbody>
            <tfoot>
              <tr>
                <th rowspan="1" colspan="1">Id</th>
                <th rowspan="1" colspan="1">Photo</th>
                <th rowspan="1" colspan="1">Username</th>
                <th rowspan="1" colspan="1">User Type</th>
                <th rowspan="1" colspan="1">Category</th>
                <th rowspan="1" colspan="1">Title</th>
                <th rowspan="1" colspan="1">Restaurant</th>
                <th rowspan="1" colspan="1">Price</th>
                <th rowspan="1" colspan="1">Posted</th>
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

  alert(a)
  window.location.href='/admin/meals/edit/'+a
  
}
</script>