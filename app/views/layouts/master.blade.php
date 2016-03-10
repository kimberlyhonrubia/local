<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MAP</title>

	{{-- Stylesheet --}}
	<link rel="stylesheet" href="{{ elixir('css/vendor.css') }}"/>
	@yield('stylesheet')

</head>
<body class="nifty-ready  pace-done">
	<div class="pace pace-inactive">
		<div class="pace-progress" data-progress-text="100%" data-progress="99" style="width: 100%;">
	  		<div class="pace-progress-inner"></div>
		</div>
		<div class="pace-activity"></div>
	</div>
	<div id="content-container">
		
		<div class="boxed">
				
			<!--Page content-->
			<!--===================================================-->
			<div id="page-content">
				
				<div class="row">
					<div class="col-lg-3">
						<!-- Sidebar -->
						@include('layouts.sidebar')
						<!-- Sidebar -->
					</div>

					<div class="col-lg-9">
						<!-- Content -->
					    @yield('content')
						<!-- Content -->
					</div>
				</div>
				
				
			</div>
			<!--===================================================-->
			<!--End page content-->

		</div>

		
		<!-- Footer -->
		@include('layouts.footer')
		<!-- Footer -->
        


        <!-- SCROLL TOP BUTTON -->
        <!--===================================================-->
        <button id="scroll-top" class="btn"><i class="fa fa-chevron-up"></i></button>
        <!--===================================================-->



	</div>
	<!--===================================================-->
	<!-- END OF CONTAINER -->



	{{-- Javascripts --}}
	<script type="text/javascript" src="{{ elixir('js/vendor.js') }}"></script>
	@yield('scripts')

</body>
</html>
