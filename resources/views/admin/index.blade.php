<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>{{getcong('site_name')}} Admin</title>      
         
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ URL::asset('upload/'.getcong('site_favicon')) }}">


        <link rel="icon" type="image/png" href="{{ URL::asset('admin_assets/img/favicons/favicon.png') }}" sizes="32x32">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Web fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

        <!-- OneUI CSS framework -->
        <link rel="stylesheet" id="css-main" href="{{ URL::asset('admin_assets/css/oneui.css') }}">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
    </head>
    <body>
        <!-- Login Content -->
        <div class="content overflow-hidden">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
                    <!-- Login Block -->
                    <div class="block block-themed animated fadeIn" style="box-shadow: 0 10px 10px rgba(32, 32, 32, 0.1);border-radius: 10px;overflow: hidden;">
                        <div class="block-header bg-primary">
                            <ul class="block-options">
                                <li>
                                    <a href="{{ URL::to('password/email') }}">{{trans('words.forgot_pass_text')}}</a>
                                </li>
                                 
                            </ul>
                            <h3 class="block-title">{{trans('words.sign_in')}}</h3>
                        </div>
                        <div class="block-content block-content-full block-content-narrow">
                            <!-- Login Title -->
                            <h1 class="h2 font-w700 push-20-t push-5 text-center">{{getcong('site_name')}}</h1>
                            <p class="font-w600 text-center">{{trans('words.welcome_sign_in')}}</p>
                            <!-- END Login Title -->
                            <div class="message">
                                                {{-- Display validation errors below --}}
                                                    @if (count($errors) > 0)
                                                <div class="alert alert-danger">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                                    
                                                </div>
                            <!-- Login Form -->
                            <!-- jQuery Validation (.js-validation-login class is initialized in js/pages/base_pages_login.js) -->
                            <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
                          
                                <form action="{{ url('admin/login') }}" class="js-validation-login form-horizontal push-20-t push-20" id="loginform" role="form" method="POST">
                                    @csrf
                                <div class="col-md-12" style="margin-bottom:15px;">
                                    <label for="email">{{trans('words.email')}}</label>
                                    <input class="form-control" type="email" id="example-nf-email" name="email" value="{{ old('email') }}" placeholder="Enter Email..">
                                </div>
                                <div class="col-md-12" style="margin-bottom:15px;">
                                    <label for="password">{{trans('words.password')}}</label>
                                    <input class="form-control" type="password" id="login-password" name="password" placeholder="Enter Password..">
                                </div>
                                <div class="col-md-12" style="margin-bottom:15px;">                                     
                                    <label class="css-input switch switch-sm switch-primary">
                                        <input type="checkbox" id="login-remember-me" name="remember" @checked(old('remember'))><span></span> {{trans('words.remember_me')}}
                                    </label>                                     
                                </div>
                                <div class="col-md-12" style="margin-bottom:15px;">
                                    <button class="btn btn-block btn-primary" type="submit"><i class="si si-login pull-right"></i>  {{trans('words.login_text')}}</button>
                                </div>                                 
                            </form> 
                            <!-- END Login Form -->
                        </div>
                    </div>
                    <!-- END Login Block -->
                </div>
            </div>
        </div>
        <!-- END Login Content -->
 

        <!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
        <script src="{{ URL::asset('admin_assets/js/core/jquery.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/core/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/core/jquery.slimscroll.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/core/jquery.scrollLock.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/core/jquery.appear.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/core/jquery.countTo.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/core/jquery.placeholder.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/core/js.cookie.min.js') }}"></script>
        <script src="{{ URL::asset('admin_assets/js/app.js') }}"></script>

        <!-- Page JS Plugins -->
        <script src="{{ URL::asset('admin_assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
 
         
    </body>
</html>