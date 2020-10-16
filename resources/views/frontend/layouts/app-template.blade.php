<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/icon" href="{{ asset("/bower_components/Apex/images/favicon.ico") }}"/>
    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="{{ asset("/bower_components/Apex/css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Slick slider -->
    <link href="{{ asset("/bower_components/Apex/css/slick.css") }}" rel="stylesheet">
    <!-- Theme color -->
    <link id="switcher" href="{{ asset("/bower_components/Apex/css/theme-color/orange-theme.css") }}" rel="stylesheet">


    <!-- Main Style -->
    <link href="{{ asset("/bower_components/Apex/style.css") }}" rel="stylesheet">

    <!-- Fonts -->

    <!-- Open Sans for body and title font -->
	  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700,800" rel="stylesheet">
 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

  
  	
  	<!-- Main Header -->
	
	@if (Request::segment(1) == "privacy-policy" || Request::segment(1) == "terms")  
		@include('frontend.layouts.headerInner')
	@else
		@include('frontend.layouts.header')
	@endif
	 
	 
    
    
    @yield('content')
    <!-- /.content-wrapper -->
    

	<!-- Start Menu -->
	@if (Request::segment(1) != "privacy-policy" && Request::segment(1) != "terms") 
	<button class="mu-menu-btn">
		<i class="fa fa-bars"></i>
	</button>
	
	<div class="mu-menu-full-overlay">
		<div class="mu-menu-full-overlay-inner">
			<a class="mu-menu-close-btn" href="#"><span class="mu-line"></span></a>
			<nav class="mu-menu" role="navigation">
				<ul>
					<li><a href="#mu-header">Header</a></li>
					<li><a href="#mu-feature">App Feature</a></li>
					<li><a href="#mu-video">Promo Video</a></li>
					<li><a href="#mu-apps-screenshot">Apps Screenshot</a></li>
					<li><a href="#mu-download">Download</a></li>
					<li><a href="#mu-faq">FAQ</a></li>
					<li><a href="#mu-contact">Get In Touch</a></li>
				</ul>
			</nav>
		</div>
	</div>
	@endif
	<!-- End Menu -->

	<!-- Footer -->
  @include('frontend.layouts.footer')
	 
  </body>
</html>
