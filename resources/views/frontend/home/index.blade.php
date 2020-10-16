@extends('frontend.home.base')
@section('action-content')

	<!-- Start main content -->
		
	<main role="main">

	<!-- Start Feature -->
	<section id="mu-feature">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="mu-feature-area">

						<div class="mu-title-area">
							<h2 class="mu-title">OUR APP FEATURES</h2>
							<span class="mu-title-dot"></span>
							<p>Discover different images of meals. Share your images to other social networks as well. Use the Map/GPS feature to get directions to the restaurant that the image was taking..</p>
						</div>

						<!-- Start Feature Content -->
						<div class="mu-feature-content">
							<div class="row">
								<div class="col-md-6">
									<div class="mu-feature-content-left">
										<img class="mu-profile-img" src="{{ asset("/bower_components/Apex/images/iphone-group.png") }}" alt="iphone Image">
									</div>
								</div>
								<div class="col-md-6">
									<div class="mu-feature-content-right">

										<!-- Start single feature item -->
										<div class="media">
											<div class="media-left">
												<button class="btn mu-feature-btn" type="button">
													<i class="fa fa-tablet" aria-hidden="true"></i>
												</button>
											</div>
											<div class="media-body">
												<h3 class="media-heading">Tag Meals</h3>
												<p>Tag a restaurant with the meal you had there. This way each meal will have a restaurants attached to it and people can come to the restaurant to have it using the app.</p>
											</div>
										</div>
										<!-- End single feature item -->

										<!-- Start single feature item -->
										<div class="media">
											<div class="media-left">
												<button class="btn mu-feature-btn" type="button">
													<i class="fa fa-sliders" aria-hidden="true"></i>
												</button>
											</div>
											<div class="media-body">
												<h3 class="media-heading">Browse Meals</h3>
												<p>Browse hundreds of meals posted by a vast community of our users.</p>
											</div>
										</div>
										<!-- End single feature item -->

										<!-- Start single feature item -->
										<div class="media">
											<div class="media-left">
												<button class="btn mu-feature-btn" type="button">
													<i class="fa fa-tachometer" aria-hidden="true"></i>
												</button>
											</div>
											<div class="media-body">
												<h3 class="media-heading">Follow Users</h3>
												<p>Follow your most favourite foodie & meal contributor and get instant notification whenever a new meal is posted by the user.</p>
											</div>
										</div>
										<!-- End single feature item -->

										<!-- Start single feature item -->
										<div class="media">
											<div class="media-left">
												<button class="btn mu-feature-btn" type="button">
													<i class="fa fa-map-marker" aria-hidden="true"></i>
												</button>
											</div>
											<div class="media-body">
												<h3 class="media-heading">Post</h3>
												<p>Post new meals from restaurant you visit.</p>
											</div>
										</div>
										<!-- End single feature item -->

									</div>
								</div>
							</div>
						</div>
						<!-- End Feature Content -->

					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Feature -->

	<!-- Start Video -->
	<!--<section id="mu-video">
		<div class="mu-video-overlay">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="mu-video-area">
							<h2>Watch Promo Video</h2>
							<a class="mu-video-play-btn" href="#"><i class="fa fa-play" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		</div>-->

		<!-- Start Video content -->
		<!--<div class="mu-video-content">
			<div class="mu-video-iframe-area">
				<a class="mu-video-close-btn" href="#"><i class="fa fa-times" aria-hidden="true"></i></a>
				<iframe class="mu-video-iframe" width="854" height="480" src="https://www.youtube.com/embed/9r40_ffCZ_I" frameborder="0" allowfullscreen></iframe> 
			</div>
		</div>
		<!-- End Video content -->

	<!--</section> -->
	<!-- End Video -->

	<!-- Start Apps Screenshot -->
	<section id="mu-apps-screenshot">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="mu-apps-screenshot-area">

						<div class="mu-title-area">
							<h2 class="mu-title">APP SCREENSHOT</h2>
							<span class="mu-title-dot"></span>
							<!--<p>Discover different images of meals
Share your images to other social networks as well. 
Use the map/gps feature to get directions to the restaurant that the image was taking..</p>-->
						</div>


						<!-- Start Apps Screenshot Content -->
						<div class="mu-apps-screenshot-content">

							<div class="mu-apps-screenshot-slider">

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/14.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/2.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/3.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/4.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/5.jpg") }}" alt="App screenshot img">
								</div>
								
								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/6.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/7.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/8.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/9.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/10.jpg") }}" alt="App screenshot img">
								</div>
								
								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/11.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/12.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/13.jpg") }}" alt="App screenshot img">
								</div>

								<div class="mu-single-screeshot">
									<img src="{{ asset("/bower_components/Apex/images/screenshot/14.jpg") }}" alt="App screenshot img">
								</div>



						</div>
						<!-- End Apps Screenshot Content -->

					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Apps Screenshot -->

	<!-- Start Download -->
	<section id="mu-download">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="mu-download-area">

						<div class="mu-title-area">
							<h2 class="mu-title">GET THE APP</h2>
							<span class="mu-title-dot"></span>
							<p>Use the following links to go to the respective app stores. Currently we are available in iOS and Android platform.</p>
						</div>


						<div class="mu-download-content">
							<a class="mu-apple-btn" target="_blank" href="https://itunes.apple.com/us/app/looksyummy-food-app/id1464182551?mt=8"><i class="fa fa-apple"></i><span>apple store</span></a>
							<a class="mu-google-btn" target="_blank" href="https://play.google.com/store/apps/details?id=com.looksyummyllc.looksyummy"><i class="fa fa-android"></i><span>google play</span></a>
							<!--<a class="mu-windows-btn" href="#"><i class="fa fa-windows"></i><span>windows store</span></a>-->
						</div>


					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Download -->

	<!-- Start FAQ -->
	<section id="mu-faq">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="mu-faq-area">

						<div class="mu-title-area">
							<h2 class="mu-title">FAQ</h2>
							<span class="mu-title-dot"></span>
						</div>


						<div class="mu-faq-content">

							<div class="panel-group" id="accordion">

								<div class="panel panel-default">
								  <div class="panel-heading">
									<h4 class="panel-title">
									  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true">
										<span class="fa fa-minus"></span> What is Lookyummy App? 
									  </a>
									</h4>
								  </div>
								  <div id="collapseOne" class="panel-collapse collapse in">
									<div class="panel-body">
									  LooksYummy is an easy way to capture and share images of your meals from different restaurants that you visit. Whether it's breakfast, brunch, lunch, dinner, or a late night snack; share your images of your meal with friends and family. Follow your friends and family to see their posts as well.
									</div>
								  </div>
								</div>

								<div class="panel panel-default">
								  <div class="panel-heading">
									<h4 class="panel-title">
									  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
										<span class="fa fa-plus"></span> How do I setup this App? 
									  </a>
									</h4>
								  </div>
								  <div id="collapseTwo" class="panel-collapse collapse">
									<div class="panel-body">
									  Simply click on any of the links from "GET THE APP" section above. Currently this app is available in iOS and Android versions. Please use the link depending upon the handset you are using. Alternatively, you can find the app by searching the respective app stores. To do that simply type "looksyummy" in search bar of your app store.
									</div>
								  </div>
								</div>

								<div class="panel panel-default">
								  <div class="panel-heading">
									<h4 class="panel-title">
									  <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
										<span class="fa fa-plus"></span> Does it cost anything to become a member? 
									  </a>
									</h4>
								  </div>
								  <div id="collapseThree" class="panel-collapse collapse">
									<div class="panel-body">
									  This app is absolutely free for individual user (contributor of meals). For Businesses we do have attractive subscription plans. Irrespective of whether you want to sign up as an individual or a business you can Sign Up free of cost. You can use Sign Up option provided in the app to do so.
									</div>
								  </div>
								</div>

								<div class="panel panel-default">
								  <div class="panel-heading">
									<h4 class="panel-title">
									  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
										<span class="fa fa-plus"></span> What is your policy regarding privacy? 
									  </a>
									</h4>
								  </div>
								  <div id="collapseFour" class="panel-collapse collapse">
									<div class="panel-body">
									  Lookyummy is committed to securing each and every piece of personal and business information provided by our users. We will never distribute these information to any third-party institutions under any circumstances. You can read our detailed "Privacy Policy" statement can be found by clicking the Privacy Policy link provided in the menu. 
									</div>
								  </div>
								</div>

								<div class="panel panel-default">
								  <div class="panel-heading">
									<h4 class="panel-title">
									  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
										<span class="fa fa-plus"></span> Are there more help resources available? 
									  </a>
									</h4>
								  </div>
								  <div id="collapseFive" class="panel-collapse collapse">
									<div class="panel-body">
									  More help & how-tos regarding this app may be found inside the Help menu of the app.
									</div>
								  </div>
								</div>


							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End FAQ -->


	<!-- Start Contact -->
	<section id="mu-contact">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="mu-contact-area">

						<div class="mu-title-area">
							<h2 class="mu-heading-title">GET IN TOUCH</h2>
							<span class="mu-title-dot"></span>
							<p>Please enter your name, email and complete message and hit submit button. Once we receive your message allow us a time of 24-48 hours to get back to you.</p>
						</div>

						

						<!-- Start Contact Content -->
						<div class="mu-contact-content">
							<div class="row">
							
							<div class="col-md-7">
								<div class="mu-contact-left">
									<div id="form-messages"></div>
										{{ Form::open(array('url' => '/contact-us', 'method' => 'POST')) }}
											<div class="form-group">                
												<input type="text" class="form-control" placeholder="Name" id="name" name="name" required>
											</div>
											<div class="form-group">                
												<input type="email" class="form-control" placeholder="Enter Email" id="email" name="email" required>
											</div>              
											<div class="form-group">
												<textarea class="form-control" placeholder="Message" id="message" name="message" required></textarea>
											</div>
											<button type="submit" class="mu-send-msg-btn"><span>SUBMIT</span></button>
										{{ Form::close() }}
									</div>
								</div>

								<div class="col-md-5">
									<div class="mu-contact-right">
										<div class="mu-contact-right-single">
											<div class="mu-icon"><i class="fa fa-map-marker"></i></div>
											<p><strong>Office Location</strong></p>
											<p>4138 NW 96th way Sunrise, FL 33351</p>
										</div>

										<div class="mu-contact-right-single">
											<div class="mu-icon"><i class="fa fa-phone"></i></div>
											<p><strong>Phone Number</strong></p>											
											<p>+19546272704</p>
										</div>

										<div class="mu-contact-right-single">
											<div class="mu-icon"><i class="fa fa-envelope"></i></div>
											<p><strong>Email Address</strong></p>
											<p>looksyummyllc@gmail.com</p>
										</div>

										<div class="mu-contact-right-single">
											<div class="mu-social-media">
												<a href="https://www.facebook.com/looksyummyapp/" target="_blank"><i class="fa fa-facebook"></i></a>
												<a href="https://twitter.com/looksyummyapp" target="_blank"><i class="fa fa-twitter"></i></a>
												<a href="#"><i class="fa fa-google-plus"></i></a>
												<a href="#"><i class="fa fa-linkedin"></i></a>
												<a href="#"><i class="fa fa-youtube"></i></a>
											</div>
										</div>

									</div>
								</div>		

							</div>
						</div>
						<!-- End Contact Content -->

					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Contact -->

</main>

<!-- End main content -->	
		
		
@endsection