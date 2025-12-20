<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Site Title -->
<title>Page not found!</title>

<!-- Meta Tags -->
<meta name="author" content="">
<link rel="canonical" href="@yield('head_url', url('/'))">
<meta name="description" content="Page not found" />
<meta name="keywords" content="" />
<meta property="og:type" content="article" />
<meta property="og:title" content="Page not found" />
<meta property="og:description" content="Page not found" />

<link rel="image_src" href="@yield('head_image', url('/upload/logo.png'))" title="logo_white">

<!-- Favicon -->
<link rel="icon" href="{{ URL::asset('upload/'.getcong('site_favicon')) }}">

<!-- Load CSS Files -->
<link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/css/font-awesome.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/css/owl.carousel.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/css/owl.theme.default.min.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/css/animated-headline.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/css/jquery.fancybox.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/css/style_switcher.css') }}">
<link rel="stylesheet" href="{{ URL::asset('assets/css/'.getcong('styling').'.css') }}" id="theme">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Mukta:200,300,400,500,600,700&amp;display=swap" rel="stylesheet">
</head>
<body>

<!-- Start Per Loader -->
<div class="loader-container">
    <div class="loader-ripple">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<!-- End Per Loader -->

   

<!-- ================================
     Start Error Area
================================= -->
<section class="error-area padding-top-60px">
    <div class="container">
        <div class="col-lg-6 mx-auto mb-4">
            <div class="error-content text-center">
                <img src="{{URL::to('assets/images/404-img.png')}}" alt="error-image" class="w-100">
                <h3 class="item_sec_title mt-4 mb-3">Oops! Page Not Found.</h3>
                <p class="item_sec_desc">The Page you are Looking for Not Avaible!</p>
                <a href="{{URL::to('/')}}" class="primary_item_btn mt-3">Back to Home</a>
            </div>
        </div>
        
    </div>
</section>
<!-- ================================
     End Error Area
================================= -->
 
<!-- Load JS Files -->
<script src="{{ URL::asset('assets/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/bootstrap-select.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.fancybox.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/animated-headline.js') }}"></script>
<script src="{{ URL::asset('assets/js/owl.carousel.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/waypoints.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.MultiFile.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/rating-script.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery.lazy.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/switcher.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/custom-main.js') }}"></script>
 
</body>
</html> 
