@extends('admin.meals.base')

@section('action-content')


<script src="{{url('tinymce/js/tinymce/jquery.tinymce.min.js')}}"></script>
<script src="{{url('tinymce/js/tinymce/tinymce.min.js')}}"></script>
<script>tinymce.init({ selector:'#content' });</script>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Update Meal</div>
                <div class="panel-body">
				
					
                    <form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" action="{{ url('/admin/meals/update/'.$meal->id) }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        
						<div class="form-group{{ $errors->has('lang_code') ? ' has-error' : '' }}">
                            <label for="lang_code" class="col-md-4 control-label">Meal category</label>

                            <div class="col-md-6">
                                {!! Form::select('meal_category_id', $meal_categories, $meal->meal_category_id, ['placeholder' => 'Select', 'class' => 'form-control required']) !!}


                                @if ($errors->has('meal_category_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('meal_category_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						
                        <div class="form-group{{ $errors->has('lang_code') ? ' has-error' : '' }}">
                            <label for="lang_code" class="col-md-4 control-label">Restaurants</label>

                            <div class="col-md-6">
                                {!! Form::select('restaurant_id', $restaurants, $meal->restaurant_id, ['placeholder' => 'Select', 'class' => 'form-control required']) !!}


                                @if ($errors->has('restaurant_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('restaurant_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        
                        <div class="form-group">
                            <label for="avatar" class="col-md-4 control-label" >Upload Photo</label>
                            <div class="col-md-6">
                                <input type="file" id="photo1" name="photo1">
                                @if ($errors->has('photo1'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('photo1') }}</strong>
                                    </span>
                                @endif

                                <br /><img class="img-responsive" alt="" src="{{ asset(env('MEAL_PIC_MAX', '/storage/photos/thumb/').$meal->photo) }}" />
                                
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">Title</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ $meal->title }}"  autofocus required>

                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            
                        </div>
                        

						<div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="content" class="col-md-4 control-label">Description</label>

                            <div class="col-md-6">
                                {{ Form::textarea('description', $meal->description) }}
								

								@if ($errors->has('content'))
									<span class="help-block">
										<strong>{{ $errors->first('content') }}</strong>
									</span>
								@endif
                            </div>
							
							
                        </div>
						
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="amount" class="col-md-4 control-label">Amount</label>

                            <div class="col-md-6">
                                <input id="amount" type="text" class="form-control" name="amount" value="{{ $meal->amount }}"  autofocus>

                                @if ($errors->has('amount'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('amount') }}</strong>
                                    </span>
                                @endif
                            </div>
                            
                            
                        </div>
						
                        <label for="active" class="col-md-4 control-label">Active</label>
                        <div class="col-md-6 radio-inline">
                            <div class="form-group{{ $errors->has('active') ? ' has-error' : '' }}">

                                <input type="checkbox" name="active" value="1"{{ ($meal->active == 1) ? " checked=checked" : "" }}>
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
