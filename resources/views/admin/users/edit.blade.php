@extends('admin.users.base')

@section('action-content')


<script src="{{url('tinymce/js/tinymce/jquery.tinymce.min.js')}}"></script>
<script src="{{url('tinymce/js/tinymce/tinymce.min.js')}}"></script>
<script>tinymce.init({ selector:'#content' });</script>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update User</div>
                <div class="panel-body">


                    @include('flash-message')
					
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" action="{{ url('/admin/users/update/'.$user->id) }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
						
						<div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="firstname" class="col-md-4 control-label">First Name</label>

                            <div class="col-md-6">
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" autofocus>

								@if ($errors->has('first_name'))
									<span class="help-block">
										<strong>{{ $errors->first('first_name') }}</strong>
									</span>
								@endif
                            </div>
							
							
                        </div>
						
						
						<div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <label for="firstname" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $user->last_name }}">

								@if ($errors->has('last_name'))
									<span class="help-block">
										<strong>{{ $errors->first('last_name') }}</strong>
									</span>
								@endif
                            </div>
							
							
                        </div>
						
						
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}">

								@if ($errors->has('email'))
									<span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
								@endif
                            </div>

                        </div>
						
						<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ $user->username }}" required>

								@if ($errors->has('username'))
									<span class="help-block">
										<strong>{{ $errors->first('username') }}</strong>
									</span>
								@endif
                            </div>

                        </div>
						
                        
						<div class="form-group{{ $errors->has('business_name') ? ' has-error' : '' }}">
                            <label for="business_name" class="col-md-4 control-label">Business Name</label>

                            <div class="col-md-6">
                                <input id="business_name" type="text" class="form-control" name="business_name" value="{{ $user->business_name }}">

								@if ($errors->has('business_name'))
									<span class="help-block">
										<strong>{{ $errors->first('business_name') }}</strong>
									</span>
								@endif
                            </div>

                        </div>

                        <label for="show" class="col-md-4 control-label">Show</label>
                        <div class="col-md-6 radio-inline">
                            <div class="form-group{{ $errors->has('show') ? ' has-error' : '' }}">
                            
                                <input type="checkbox" name="show" value="1"{{ ($user->show == '1') ? " checked=checked" : "" }}>
                                @if ($errors->has('show'))
                                <span class="help-block">
                                <strong>{{ $errors->first('show') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
						
					
                        <label for="active" class="col-md-4 control-label">Active</label>
                        <div class="col-md-6 radio-inline">
                            <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">
                            
                                <input type="checkbox" name="active" value="1"{{ ($user->active == '1') ? " checked=checked" : "" }}>
                                @if ($errors->has('active'))
                                <span class="help-block">
                                <strong>{{ $errors->first('active') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
						
						
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
