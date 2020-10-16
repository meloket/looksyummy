@extends('admin.restaurants.base')
@section('action-content')
<!-- Main content -->
<section class="content">
  <div class="box">
  <div class="box-header">
    <div class="row">
        <div class="col-sm-8">
          <h3 class="box-title">List of Restaurants</h3>
        </div>
        <div class="col-sm-4">
          
        </div>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
      <div class="row">
        
        <div class="col-sm-6">
        {{ Form::open(array('url' => '/admin/restaurants', 'method' => 'GET')) }}
        

        Filter: 

        {{ Form::input('text', 'keyword', null, ['style' => 'width: 300px;', 'class' => 'form-control1', 'placeholder' => 'By name, place, category or phone']) }}
        
        {{ Form::submit('Filter', array('class' => 'btn btn-primary')) }} <a href="/admin/restaurants?clear=1">Clear Filters</a>

        {{ Form::close() }} 

        </div>
      </div>
      
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th width="20%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Name</th>
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Category</th>
        				<th width="5%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Menu</th>
        				<th width="25%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Location</th>
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Monthly / Annual Fee</th>
        				<th width="5%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Num. Meals*</th>
                <th width="5%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Num. Users**</th>
                <th tabindex="0" aria-controls="example2" rowspan="1" colspan="2" aria-label="Action: activate to sort column ascending">Action</th>
              </tr>
            </thead>
            <tbody>
      			@if(isset($restaurants) && $restaurants->count() > 0)
                  @foreach ($restaurants as $restaurant)
                      <tr role="row" class="odd">
                        <td width="20%">{{ $restaurant->place_name }}</td>
                        <td width="15%">{{ $restaurant->place_types }}</td>
                        <td width="5%" class="text-center"><a href="/admin/restaurant/meals/{{ $restaurant->id }}">Menu</a></td>
              				  <td width="25%">{{ $restaurant->place_street." ".$restaurant->place_vicinity }} </td>
              				  <td width="15%"></td>
              				  <td width="5%" class="text-center">{{ $restaurant->meals->count() }}</td>
                        <td width="5%" class="text-center">{{ $restaurant->restaurantUsers->count() }}</td>
                        <td width="8%" class="text-center">
                        
                        <a href="/admin/restaurant/{{ $restaurant->id }}">Edit</a>
                        </td>
                        

                    </tr>
                  @endforeach
      			@endif
            </tbody>
            
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($restaurants)}} of {{count($restaurants)}} entries</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $restaurants->links() }}
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
function doLoad(b, id) {


  alert(b)

  if(b == 3)
	  window.location.href = "{{ url('/admin/users/analytics') }}/"+id;
	//alert('Coming soon')
}
</script>