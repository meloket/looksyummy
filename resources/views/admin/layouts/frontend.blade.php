<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title') &mdash; eHoliday -  Rental Website</title>

		<!-- Bootstrap -->
		
        <link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/style.css') }}" media="all">
		<link rel="stylesheet" type="text/css" href="{{ asset('/frontend/css/responsive.css') }}" media="all">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/bootstrap.css') }}" media="all">
		<link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/font-awesome.css') }}">
		<link rel="stylesheet"  media="all" type="text/css" href="{{ asset('frontend/css/daterangepicker.css') }}">

		<!---google_fonts---->
		<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,700i,900,900i" rel="stylesheet">


		<!-- Add this css File in head tag-->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" media="all">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" rel="stylesheet" media="all">
		<link href="{{ asset('frontend/css/BannerSlider-style.css') }}" rel="stylesheet" type="text/css">
		
		

    </head>
    <body>
        <header class="main_menu">
		  <div class="navbar navbar-fixed-top custom_nav" role="navigation">
			<div class="container">
			  <div class="row">                
                    @include('partials.frontend.top1')
              </div>
			  <div class="row">                
                    @include('partials.frontend.top2')
              </div>
			  
            </div>
		  </div>
		  @include('partials.frontend.login')
		</header>
        
        @yield('content')
            
		<footer class="footer-bs">
		  <div class="container">
			<div class="row">
			  <div class="col-sm-3 footer-brand animated fadeInLeft">
			  {{ Form::open(array('url' => '/subscribe', 'id' => 'NewsletterSubs', 'method' => 'post')) }}
				<h4>{{ __('messages.newsletter') }}</h4>
				<p>{{ __('messages.enter_your_email') }}</p>
				<p>
				
				<div class="input-group">

				  <input type="text" id="sub_email" name="email" class="form-control" placeholder="{{ __('messages.search_for') }}...">
				  <span class="input-group-btn">
				  <button class="btn btn-default envelope_btn" type="submit" id="SubsButton" form="NewsletterSubs" value="Submit"><span class="glyphicon glyphicon-envelope">	</span></button>

				  </span> </div>
				<!-- /input-group -->
				</p>
			  {{ Form::close() }}
			  </div>
			  <div class="col-sm-4 footer-nav animated fadeInUp">
				<h4>{{ __('messages.latest_news') }}</h4>
				@if(isset($global_news) && count($global_news) > 0)
				@foreach($global_news as $nw)
				<div class="row"> <a href="/news/{{ $nw->id }}">
				  <div class=" col-xs-12 nopad">
					<div class="tab_post_list_left pull-left">
					  <figure><img src="{{ asset(env('PHOTO_MAX', '/storage/photos/max/').$nw->photo) }}" class="img-responsive"></figure>
					</div>
					<div class="tab_post_list_right">
					  <div class="tab_post_title">
						<p>{{ $nw->title }}</p>
					  </div>
					</div>
				  </div>
				  </a> </div>
				@endforeach
				@endif
			  </div>
			  <div class="col-sm-2 footer-social animated fadeInDown">
				<h4>{{ __('messages.follow_us') }}</h4>
				<ul>
				  <li><a href="https://www.facebook.com/jungers.eholiday.5">{{ __('messages.facebook') }}</a></li>
				  <li><a href="https://twitter.com/eholiday3">{{ __('messages.twitter') }}</a></li>
				  <!--<li><a href="#">Instagram</a></li>
				  <li><a href="#">RSS</a></li>-->
				</ul>
			  </div>
			  <div class="col-sm-3 footer-ns animated fadeInRight contact">
				<h4>{{ __('messages.contact_us') }}</h4>
				@if(isset($global_settings))
				<p><i class="fa fa-map-marker" aria-hidden="true"></i>{{ $global_settings->full_address }}</p>
				<p><i class="fa fa-envelope" aria-hidden="true"></i></i><a href="mailto:{{ $global_settings->email }}">{{ $global_settings->email }}</a></p>
				<p><i class="fa fa-phone" aria-hidden="true"></i>{{ $global_settings->phone }}</p>
				<p><i class="fa fa-whatsapp" aria-hidden="true"></i>{{ $global_settings->whatsapp }}</p>
				@endif
			  </div>
			</div>
		  </div>
		  <div class="footer_buttom container-fluid">
          	<div class="container">
            	<div class="row" style="padding-top:20px;">
                	<div class="col-sm-2 copywrite"> <p>© <?php echo date("Y") ?> @yield('title') </p></div>
                    <div class="col-sm-10">
                    	<ul class="footer_menu">
                        <li><a href="/">{{ __('messages.home') }}</a></li>
                        <li><a href="/about-us">{{ __('messages.about_us') }}</a></li>
                        <li><a href="/our-vision">{{ __('messages.our_vision') }}</a></li>
                        <li><a href="/properties">{{ __('messages.properties') }}</a></li>
                        <li><a href="/gallery">{{ __('messages.gallery') }}</a></li>
                        <li><a href="/partners">{{ __('messages.partners') }}</a></li>		
                        <li><a href="/terms-of-use">{{ __('messages.terms') }}</a></li>	
                        <li><a href="/privacy-policy">{{ __('messages.privacy_policy') }}</a></li>			
                        <li><a href="/contact-us">{{ __('messages.contact_us') }}</a></li>
                      </ul>
                    </div>
                </div>
            </div>
		  </div>
		</footer>

		
		<!-- jQuery (necessary for Bootstrap_JavaScript plugins) ---> 
		<!--<script src="js/jquery.min.js"></script> --> 

		<script src="{{ asset('/frontend/js/jquery-1.11.3.min.js') }}"></script> 
		<script src="{{ asset('/frontend/js/bootstrap.min.js') }}"></script> 
		<script type="text/javascript" src="{{ asset('/frontend/js/slider-swipe.js') }}"></script> 
		<!--<script src="//ajax.googleapis.com/ajax/libs/jquerymobile/1.4.3/jquery.mobile.min.js"></script> --> 

		<!--<script>
			$(document).ready(function() {  
				 $(".carousel-inner").swiperight(function() {  
					  $(this).parent().carousel('prev');  
						});  
				   $(".carousel-inner").swipeleft(function() {  
					  $(this).parent().carousel('next');  
			   });  
			});  
		</script>-->
		
		<script>
		
		
			$(document).ready(function() {
				//if($("#NewsletterSubs").submit()) {
					//alert("Test")
				//}
				
				var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
				
				$("#NewsletterSubs").submit(function()
				{
						
					var sEmail = $("#sub_email").val();
					
					if (filter.test(sEmail)) {

						return true;

					}

					else {

						alert("{{ __('messages.enter_valid_email') }}")
						 $("#sub_email").focus();
						return false;

					}


				});

			});  
		</script>
		
		
		
	</body>
  
    
</html>