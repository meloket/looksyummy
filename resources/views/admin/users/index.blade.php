@extends('admin.users.base')
@section('action-content')
<!-- Main content -->
<section class="content">
  <div class="box">
  <div class="box-header">
    <div class="row">
        <div class="col-sm-8">
          <h3 class="box-title">List of Users</h3>
        </div>
        <div class="col-sm-4">
          
        </div>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body">
      <div class="row">
        <div class="col-sm-10">
        {{ Form::open(array('url' => '/admin/users', 'method' => 'GET')) }}
        

        Filter: 

        {{ Form::input('text', 'keyword', null, ['style' => 'width: 300px;', 'class' => 'form-control1', 'placeholder' => 'By usernme, First Name, Last Name or Email']) }}
        {!! Form::select('user_type', array('0' => 'User Type', '1' => 'Users', '2' => 'Business'), null, ['class' => 'form-control1']) !!}
        {{ Form::submit('Filter', array('class' => 'btn btn-primary')) }} <a href="/admin/users?clear=1">Clear Filters</a>

        {{ Form::close() }} 
        </div>
        <div class="col-sm-6">
          

        </div>
      </div>
      
    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
      <div class="row">
        <div class="col-sm-12">
          <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
            <thead>
              <tr role="row">
                <th width="10%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Id</th>
                <th width="10%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Type</th>
                <th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">First Name</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Last Name</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Username</th>
                <th width="10%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Phone</th>
        				<th width="15%" class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Department: activate to sort column ascending">Email</th>
                <th tabindex="0" aria-controls="example2" rowspan="1" colspan="2" aria-label="Action: activate to sort column ascending">Action</th>
              </tr>
            </thead>
            <tbody>
      			@if(isset($users) && $users->count() > 0)
                  @foreach ($users as $user)
                      <tr role="row" class="odd">
                        <td width="10%">{{ $user->id }}</td>
                        <td width="10%">{{ $user->user_type }}</td>
                        <td width="15%">{{ $user->first_name }}</td>
              				  <td width="15%">{{ $user->last_name }}</td>
              				  <td width="15%">{{ $user->username }}</td>
              				  <td width="10%">{{ $user->phone }}</td>
                        <td width="20%">{{ $user->email }}</td>
                        <td>
                        <a href="/admin/users/analytics/{{ $user->id }}">Analytics</a><br /> 
                        <a href="/admin/users/edit/{{ $user->id }}">Edit</a>
                        <a href="/admin/users/destroy/{{ $user->id }}"  onclick="javascript:return confirm('OK to confirm.')">Delete</a>
                         <!--<select onChange="javascript:doLoad(1, {{$user->id}})"><option>Select</option>
                            <option>Update</option>
                            @if($user->user_type == "2")
            						    <option onChange="javascript:doLoad(3, {{$user->id}})">Analytics</option>
                            @endif
                            <option onChange="javascript:doLoad(2, {{$user->id}})">Change Password</option>
                          </select> -->   
                        </td>

                    </tr>
                  @endforeach
      			@endif
            </tbody>
            <tfoot>
              <tr>
                <th width="20%" rowspan="1" colspan="1">User</th>
                <th rowspan="1" colspan="2">Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-5">
          <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">Showing 1 to {{count($users)}} of {{count($users)}} entries</div>
        </div>
        <div class="col-sm-7">
          <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            {{ $users->links() }}
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