@extends('layouts.frontend')

@section('title', 'eHoliday')

@section('content')

<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://code.jquery.com/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<section class="banner_block">
  <div class="container Home_Search_area_holder">
    <div class="Home_Search_area">
      {{ Form::open(array('url' => '/search', 'method' => 'GET')) }}
        <div class="custom_form_group">
		  @if(isset($locations))
		  
          <p>@lang('messages.location')</p>
          <select class="selectpicker" name="location_id" style="width:100%">
			<option value="">@lang('messages.all')</option>
			@foreach($locations as $location)
            <option value="{{ $location->id }}">{{ $location['translation'][0]['name'] }}</option>
			@endforeach
          </select>
		  
		  @endif
        </div>
        <div class="custom_form_group">
		  @if(isset($property_types))
          <p>@lang('messages.property_type')</p>
          <select class="selectpicker" name="property_type_id" style="width:100%">
		    <option value="">@lang('messages.all')</option>
			@foreach($property_types as $property_type)			
            <option value="{{ $property_type->id }}">{{ $property_type['translation'][0]['name'] }}</option>
			@endforeach
          </select>
		  @endif
        </div>
        <div class="custom_form_group">
          <p>@lang('messages.guests')</p>
          <select class="selectpicker small_picker">
            <option>1</option>
            <option>2</option>
            <option>3</option>
            <option>4</option>
            <option>5</option>
            <option>6</option>
            <option>7</option>
            <option>8</option>
            <option>9</option>
            <option>10</option>
          </select>
        </div>
        <!--<div class="custom_form_group demo">
          <p>@lang('labels.booking_dates')</p>
		  
		  
          <input id="check_in_date" name="check_in_date" class="selectpicker form-control" type="text">
          <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
          </div>-->
        <div class=" custom_form_group  pull-right">
          <button class="Enquiry" >{{ __('messages.search') }}</button>
        </div>
      {{ Form::close() }}
    </div>
  </div>
  
  @if(isset($slides) && $slides->count() > 0)
  @php($n = 0)
  <div id="bootstrap-touch-slider" class="carousel bs-slider fade  control-round indicators-line" data-ride="carousel" data-pause="hover" data-interval="5000" > 
    
    <!-- Indicators -->
    <ol class="carousel-indicators">
	  @foreach($slides as $slide)
	  
	  @if($n == 0)
		<li data-target="#bootstrap-touch-slider" data-slide-to="{{ $n }}" class="active"></li>
      @else
		<li data-target="#bootstrap-touch-slider" data-slide-to="{{ $n }}"></li>
	  @endif
	  @php($n++)
	  @endforeach
    </ol>
	
	
	@php($n = 0) 
    <!-- Wrapper For Slides -->
    <div class="carousel-inner" role="listbox"> 
      
	  @foreach($slides as $slide)
      <!-- Third Slide -->
	   @if($n == 0)
       <div class="item active"> 
       @else
	   <div class="item"> 
       @endif
	   
        <!-- Slide Background -->
        <div class="image_holder slide-image" style="background:url({{ asset(env('PHOTO_MAX', '/storage/photos/thumb/').$slide['image']) }}); " ></div>
        <div class="bs-slider-overlay"></div>
        <div class="container">
          <div class="row"> 
            <!-- Slide Text Layer -->
            <div class="slide-text slide_style_center">
              <h1 data-animation="animated zoomInRight">{{  $slide['translation'][0]['title'] }}<span></span></h1>
              <p data-animation="animated fadeInLeft">{{  $slide['translation'][0]['subtitle'] }}</p>
            </div>
          </div>
        </div>
      </div>
      <!-- End of Slide --> 
	  
	  @php($n++)
      @endforeach
      
      
    </div>
    <!-- End of Wrapper For Slides --> 
    
    <!-- Left Control --> 
    <a class="left carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="prev"> <span class="fa fa-angle-left" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a> 
    
    <!-- Right Control --> 
    <a class="right carousel-control" href="#bootstrap-touch-slider" role="button" data-slide="next"> <span class="fa fa-angle-right" aria-hidden="true"></span> <span class="sr-only">Next</span> </a> </div>
  <!-- End  bootstrap-touch-slider Slider --> 
  @endif
</section>

<section class="main">
  <div id="about" class="container row_pad">
    <h2 class="inner_headline text-center">{{ $page['translation'][0]['title'] }}</h2>
    <div class="row">
	@if($page->photo != "")
      <div class=" col-sm-4"><img src="{{ asset(env('PHOTO_MAX', '/storage/photos/max/').$page->photo) }}" class="img-responsive" style="width:100%; padding-bottom:20px;"></div>
	@endif
      <div class="col-sm-8">
        <p>{{ $page['translation'][0]['content'] }}</p>
      </div>
    </div>
  </div>
  <!---/about--->
  <div id="accomodation" class="container-fluid">
    <div class="container row_pad">
      <h2 class="inner_headline text-center">{{ __('messages.luxurious_accomodations') }}</h2>
      <div class="row">
		@if(isset($properties))
		@foreach($properties as $property)
        <div class="col-sm-4"> <a href="/property/{{ $property['id'] }}"  class=" flat_item_box">
          <div class="flat-thumb">
            <div class="click_detail"> {{ __('messages.read_more') }}</div>
            <img src="{{ asset(env('PHOTO_MAX', '/storage/photos/max/').$property->photo) }}" class="img-responsive" alt=""> </div>
			<div class="flat_box_footer">
				<div class="flat_title">
				  <h5>{{ $property['translation'][0]['title'] }}</h5>
				</div>
				<div class="flat_room_de"> <span> {{ $property['num_rooms'] }} {{ __('messages.persons') }}</span></div>
			</div>
          </a>
          
        </div>
		@endforeach
        @endif
		
		<!--/flat_item_box--->
		
		
      </div>
    </div>
  </div>
  
  <!---start_accomodation--->
  <div id="aera_in" class="container row_pad">
    <h2 class="inner_headline text-center">{{ __('messages.area_information') }}</h2>
    <div class="row">
	@php ($i = 1)
	@if(isset($locations))
	@foreach($locations as $location)
	@php ($galleries = $location['location_gallery'])
	@if(count($galleries) > 0)
			  
      <div class="col-sm-4">
        <div class="content-area">
		
			@php ($carousel = "myCarousel_".$i)
		    @php ($j = 1)
		  
				
          <div id="{{ $carousel }}" class="carousel slide"> 
		  
            <!-- Indicators -->
			
			<ol class="carousel-indicators">
				@foreach($galleries as $gallery)
				@if($j == 1)
					<li data-target="#{{ $carousel }}" data-slide-to="0" class="active"></li>
				@else
					<li data-target="#{{ $carousel }}" data-slide-to="0"></li>
				@endif
				@php ($j++)
				@endforeach              
            </ol>
			
			
            
            <!-- Wrapper for slides -->
            <div class="carousel-inner">
			  @php ($j = 1)
			  @if(count($galleries) > 0)
					@foreach($galleries as $gallery)
					@if($j == 1)
						<div class="item active"><img src="{{ asset(env('PHOTO_MAX', '/storage/photos/max/').$gallery->image) }}"> </div>
					@else
						<div class="item"><img src="{{ asset(env('PHOTO_MAX', '/storage/photos/max/').$gallery->image) }}"> </div>
					@endif
					@php ($j++)
					@endforeach 
				
			  @endif
            </div>
          </div>
          
          <!-- Controls --> 
          <a class="left carousel-control" href="#{{ $carousel }}" data-slide="prev"> <span class="icon-prev"></span> </a> <a class="right carousel-control" href="#{{ $carousel }}" data-slide="next"> <span class="icon-next"></span> </a> 
		</div>
		<div class="palce_name">
			<h5>{{ $location['translation'][0]['name'] }} </h5>
		</div>
		
      </div>
      <!---/slider--->
	@endif
	@php ($i++)
	@endforeach
	@endif
 

      
    </div>
  </div>

  
  <div id="Partners_in" class="container row_pad">
	<div class="row block">
		<a href="{{ url('/partners') }}">
		<div class="col-sm-4 Hline">
			<h2>{{ __('messages.our_partners') }}  &nbsp; &nbsp;</h2>
			
		</div>
        <div class="col-sm-8">
        	@if(count($partners) > 0)			
			<ul class="partner_place">
				@foreach($partners as $partner)
				<li>{{ $partner->name }}</li>
				@endforeach
			</ul>
			@endif
        </div>
		</a>
	</div>
  </div>  
  
  <!---Start Sponsors--->
  
  @if(isset($sponsors) && count($sponsors) > 0)
  <div id="aera_in" class="container row_pad">
    <h2 class="inner_headline text-center">{{ __('messages.our_sponsors') }} </h2>
    <div class="row">
	@php ($i = 1)
	@foreach($sponsors as $sponsor)
	
			  
      <div class="col-sm-4">
        <div class="content-area">			
			<div class="item active"><a href="{{ $sponsor->url }}" target="_blank"><img src="{{ asset(env('PHOTO_MAX', '/storage/photos/max/').$sponsor->photo) }}" alt="{{ $sponsor->name }}" class="img-responsive" width="250" /></a> </div>		           
		</div>
      </div>
      <!---/slider--->
	
	@php ($i++)
	@endforeach
	
 

      
    </div>
  </div>
  @endif
  
 <!-- -/Sponsors  - -->
</section>

<link href="{{ asset('/frontend/css/jquery.datepick.css') }}" rel="stylesheet">
<script src="{{ asset('/frontend/js/jquery.plugin.min.js') }}"></script>
<script src="{{ asset('/frontend/js/jquery.datepick.js') }}"></script>



<script>

$(document).ready(function () {
    //console.log("jquery "+jQuery('#check_in_date').val());
	$('#check_in_date').datepick({dateFormat: 'yyyy-mm-dd'});
}(jQuery));

</script>


@endsection
