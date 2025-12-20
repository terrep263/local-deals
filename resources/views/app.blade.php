<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Site Title -->
<title>@yield('head_title', getcong('site_name'))</title>

<!-- Meta Tags -->
<meta name="author" content="">
<link rel="canonical" href="@yield('head_url', url('/'))">
<meta name="description" content="@yield('head_description', getcong('site_description'))" />
<meta name="keywords" content="" />
<meta property="og:type" content="article" />
<meta property="og:title" content="@yield('head_title',  getcong('site_name'))" />
<meta property="og:description" content="@yield('head_description', getcong('site_description'))" />
<meta property="og:image" content="@yield('head_image', url('/upload/'.getcong('site_logo')))" />
<meta property="og:url" content="@yield('head_url', url('/'))" />
<meta property="og:image:width" content="1024" />
<meta property="og:image:height" content="1024" />
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:image" content="@yield('head_image', url('/upload/'.getcong('site_logo')))">
<link rel="image_src" href="@yield('head_image', url('/upload/'.getcong('site_logo')))" title="logo_white">

@stack('head')

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
<link rel="stylesheet" href="{{ URL::asset('assets/css/'.getcong('styling').'.css') }}">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Work+Sans:100,200,300,400,500,600,700,800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Mukta:200,300,400,500,600,700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Font Awesome CDN (new homepage) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-1zyA1kGg8uZUXadkHwVDqveufqdpypu32n7Z1xXHrp+UMtO4Zwzp1KimG++HjMATkUMlzUxr87PQqeK8HVH+Rw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Homepage CSS fallback (no Vite build needed) -->
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<!-- UI Contract (design tokens & components) -->
<link rel="stylesheet" href="{{ asset('css/ui-contract.css') }}">

@php
    // Prefer configured page_bg_image; fall back to legacy hero/overlay asset from theme
    $headerBanner = getcong('page_bg_image')
        ? "url('".asset('upload/'.getcong('page_bg_image'))."')"
        : "url('".asset('assets/images/hero-bg.jpg')."')";
@endphp
<style>
:root{
    --header-banner-img: {{ $headerBanner ? $headerBanner : 'initial' }};
}
</style>
</head> 
<body class="{{ (View::hasSection('hide_header') || request()->is('/')) ? '' : 'with-header' }} {{ request()->is('/') ? 'is-home' : 'is-not-home' }}">

<!-- Start Per Loader -->
<div class="loader-container">
    <div class="loader-ripple">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<!-- End Per Loader -->

  @if (!View::hasSection('hide_header') && !request()->is('/'))
    @include("common.header")
  @endif
 
    <div id="page-content">
        @yield("content")
    </div>

  @if (!View::hasSection('hide_footer'))
    @include("common.footer")
  @endif

@if (!View::hasSection('hide_footer'))
<!-- Start Back to Top -->
<div id="back-to-top">
    <i class="far fa-angle-up" title="Go top"></i>
</div>
<!-- End Back to Top -->
@endif

 

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
<script src="{{ URL::asset('assets/js/custom-main.js') }}"></script>

<script>
    (function() {
        // Toggle fixed-top on scroll for nav (Viavi behavior)
        const nav = document.querySelector('.main-nav-header');
        const onScroll = () => {
            if (!nav) return;
            if (window.scrollY > 0) {
                nav.classList.add('fixed-top');
            } else {
                nav.classList.remove('fixed-top');
            }
        };
        document.addEventListener('scroll', onScroll, { passive: true });
        document.addEventListener('DOMContentLoaded', onScroll);
    })();
</script>

<script src="{{ URL::asset('assets/tinymce/tinymce.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () { 
      if ($(".elm1_editor").length > 0) {
        tinymce.init({
          selector: "textarea.elm1_editor",           
          height: 300,
          plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
           toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor",
          style_formats: [
            { title: 'Bold text', inline: 'b' },
            { title: 'Red text', inline: 'span', styles: { color: '#ff0000' } },
            { title: 'Red header', block: 'h1', styles: { color: '#ff0000' } },
            { title: 'Example 1', inline: 'span', classes: 'example1' },
            { title: 'Example 2', inline: 'span', classes: 'example2' },
            { title: 'Table styles' },
            { title: 'Table row 1', selector: 'tr', classes: 'tablerow1' }
          ]
        });
      }
    });
</script>
<script type="text/javascript">
$(document).ready(function(e) {    
   $("#category").change(function(){
       var cat=$("#category").val();
    $.ajax({
    type: "GET",
     url: "{{ URL::to('ajax_subcategories') }}/"+cat,
     //data: "cat=" + cat,
     success: function(result){
         //$("#sub_category").html(result);
         $("#sub_category").html(result).selectpicker('refresh');
      }
    });    
  });
  //$("#inputTag").tagsinput('items');
});
</script> 
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/tags/jquery.tagsinput.css') }}" /> 
<script type="text/javascript" src="{{ URL::asset('assets/tags/jquery.tagsinput.js') }}"></script>
<script type="text/javascript">
    function onAddTag(tag) {
      alert("Added a amenities: " + tag);
    }
    function onRemoveTag(tag) {
      alert("Removed a amenities: " + tag);
    }
    function onChangeTag(input,tag) {
      alert("Changed a amenities: " + tag);
    }
    $(function() {
      $('#amenities_tags').tagsInput({width:'auto'});
    });
</script>
 
</body>
</html>