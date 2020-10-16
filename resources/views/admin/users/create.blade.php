@extends('users.base')

@section('action-content')


<script src="{{url('tinymce/js/tinymce/jquery.tinymce.min.js')}}"></script>
<script src="{{url('tinymce/js/tinymce/tinymce.min.js')}}"></script>
<script>tinymce.init({ selector:'#content' });</script>


<div class="container">
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Add News</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
						
						<div class="form-group{{ $errors->has('lang_code') ? ' has-error' : '' }}">
                            <label for="lang_code" class="col-md-4 control-label">Language</label>

                            <div class="col-md-6">
                                {!! Form::select('lang_code', $languages, null, ['class' => 'form-control']) !!}


                                @if ($errors->has('lang_code'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lang_code') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
						
						
						<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">News Title</label>

                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}"  autofocus required>

								@if ($errors->has('title'))
									<span class="help-block">
										<strong>{{ $errors->first('title') }}</strong>
									</span>
								@endif
                            </div>
							
							
                        </div>
						
						<div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="content" class="col-md-4 control-label">Content</label>

                            <div class="col-md-12">
                                <textarea id="content" rows="10" cols="12" class="form-control" name="content">{{ old('name') }}</textarea>
								

								@if ($errors->has('content'))
									<span class="help-block">
										<strong>{{ $errors->first('content') }}</strong>
									</span>
								@endif
                            </div>
							
							
                        </div>
						
						
                        <div class="form-group">
                            <label for="avatar" class="col-md-4 control-label" >Upload Photo</label>
                            <div class="col-md-6">
                                <input type="file" id="photo1" name="photo1" required >
								@if ($errors->has('photo1'))
									<span class="help-block">
										<strong>{{ $errors->first('photo1') }}</strong>
									</span>
								@endif
								
                            </div>
                        </div>
							
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Create
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
