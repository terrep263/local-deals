@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
                <div class="content bg-gray-lighter">
                    <div class="row items-push">
                        <div class="col-sm-7">
                            <h1 class="page-heading">
                            {{trans('words.settings')}}
                            </h1>
                        </div>
                        <div class="col-sm-5 text-right hidden-xs">
                            <ol class="breadcrumb push-10-t">
                                <li><a href="{{ URL::to('admin/dashboard') }}">{{trans('words.dashboard')}}</a></li>
                                <li><a class="link-effect" href="">{{trans('words.settings')}}</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- END Page Header -->

                <!-- Page Content -->
                <div class="content content-boxed">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                             
                        @if (count($errors) > 0)
                                        <div class="alert alert-danger">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                 @if(Session::has('flash_message'))
                                        <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            {{ Session::get('flash_message') }}
                                        </div>
                                    @endif
                        

                             <!-- Block Tabs Alternative Style -->
                            <div class="block">
                                <ul class="nav nav-tabs nav-tabs-alt" data-toggle="tabs">
                                    
                                     <li role="presentation" class="active">
                                        <a href="#general_settings" aria-controls="general_settings" role="tab" data-toggle="tab">{{trans('words.general')}}</a>
                                    </li>                                    

                                    <li role="presentation">
                                        <a href="#smtp_settings" aria-controls="smtp_settings" role="tab" data-toggle="tab">SMTP</a>
                                    </li>

                                    <li role="presentation">
                                        <a href="#social_settings" aria-controls="social_settings" role="tab" data-toggle="tab">{{trans('words.social_login')}}</a>
                                    </li>

                                    <li role="presentation">
                                        <a href="#homepage_settings" aria-controls="homepage_settings" role="tab" data-toggle="tab">{{trans('words.home_page')}}</a>
                                    </li>                                 

                                    <li role="presentation">
                                        <a href="#aboutus_settings" aria-controls="aboutus_settings" role="tab" data-toggle="tab">{{trans('words.about_page')}}</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#contactus_settings" aria-controls="contactus_settings" role="tab" data-toggle="tab">{{trans('words.contact_page')}}</a>
                                    </li>

                                    <li role="presentation">
                                        <a href="#terms_of_service" aria-controls="terms_of_service" role="tab" data-toggle="tab">{{trans('words.terms_of_service')}}</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#privacy_policy" aria-controls="privacy_policy" role="tab" data-toggle="tab">{{trans('words.privacy_policy')}}</a>
                                    </li>
                                   
                                    <li role="presentation">
                                        <a href="#other_Settings" aria-controls="other_Settings" role="tab" data-toggle="tab">{{trans('words.other_settings')}}</a>
                                    </li>
                                     
                                </ul>
                                <div class="block-content tab-content">
 

                                    <div class="col-lg-10 tab-pane active" id="general_settings">
 
                                        <form action="{{ url('admin/settings') }}" class="form-horizontal padding-15" name="account_form" id="account_form" role="form" method="POST" enctype="multipart/form-data">
                                        @csrf
                
                
                                        <div class="form-group">
                                            <label for="avatar" class="col-sm-3 control-label">{{trans('words.site_logo')}}</label>
                                            <div class="col-sm-9">
                                                <div class="media">
                                                    <div class="media-left">
                                                        @if($settings->site_logo)
                                                         
                                                            <img src="{{ URL::asset('upload/'.$settings->site_logo) }}" class="header_site_logo" alt="person">
                                                        @endif
                                                                                        
                                                    </div>
                                                    <div class="media-body media-middle">
                                                        <input type="file" name="site_logo" class="filestyle">
                                                        <small class="text-muted bold">Size 220x52px</small>
                                                    </div>
                                                </div>
                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="avatar" class="col-sm-3 control-label">{{trans('words.site_favicon')}}</label>
                                            <div class="col-sm-9">
                                                <div class="media">
                                                    <div class="media-left">
                                                        @if($settings->site_favicon)
                                                         
                                                            <img src="{{ URL::asset('upload/'.$settings->site_favicon) }}" class="site_favicon_icon" alt="person">
                                                        @endif
                                                                                        
                                                    </div>
                                                    <div class="media-body media-middle">
                                                        <input type="file" name="site_favicon" class="filestyle">
                                                        <small class="text-muted bold">Size 16x16px</small>
                                                    </div>
                                                </div>
                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">{{trans('words.site_name')}}</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" class="form-control">
                                            </div>
                                        </div>                                        
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">{{trans('words.site_email')}}</label>
                                            <div class="col-sm-9">
                                                <input type="email" name="site_email" value="{{ old('site_email', $settings->site_email) }}" class="form-control">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">{{trans('words.site_description')}}</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" name="site_description" class="form-control" rows="5" placeholder="A few words about site">{{ old('site_description', $settings->site_description) }}</textarea>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">{{trans('words.default_timezone')}}</label>
                                            <div class="col-sm-9">
                                            @php($timeZone = old('time_zone', $settings->time_zone))
                                            <select class="js-select2 form-control" name="time_zone" style="width:100%;">                               
                                                @foreach(generate_timezone_list() as $key=>$tz_data)
                                                <option value="{{$key}}" @selected($timeZone == $key)>{{$tz_data}}</option>
                                                @endforeach                            
                                            </select>
                                            </div>
                                        </div>                                        
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">{{trans('words.site_style')}}</label>
                                            <div class="col-sm-9">
                                                @php($styling = old('styling', $settings->styling))
                                                <select class="js-select2 form-control" name="styling" style="width:100%;">                                                                    
                                                    <option value="style-one" @selected($styling=="style-one")>Style 1 - Coral Red (#ff6b6b)</option>
                                                    <option value="style-two" @selected($styling=="style-two")>Style 2 - Blue (#0072ff)</option>
                                                    <option value="style-three" @selected($styling=="style-three")>Style 3 - Orange (#FD841F)</option>
                                                    <option value="style-four" @selected($styling=="style-four")>Style 4 - Green (#76b852)</option>
                                                    <option value="style-five" @selected($styling=="style-five")>Style 5 - Teal (#34A293)</option>
                                                    <option value="style-six" @selected($styling=="style-six")>Style 6 - Purple (#7743DB)</option>                                                    
                                                </select>
                                                <small class="form-text text-muted mt-2">
                                                    <strong>Style Colors:</strong><br>
                                                    <span style="color: #ff6b6b;">● Style 1: Coral Red</span> | 
                                                    <span style="color: #0072ff;">● Style 2: Blue</span> | 
                                                    <span style="color: #FD841F;">● Style 3: Orange</span> | 
                                                    <span style="color: #76b852;">● Style 4: Green</span> | 
                                                    <span style="color: #34A293;">● Style 5: Teal</span> | 
                                                    <span style="color: #7743DB;">● Style 6: Purple</span>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">{{trans('words.currency_code')}} 
                                            </label>
                                            <div class="col-sm-9">
                                                @php($currencyCode = old('currency_code', $settings->currency_code))
                                                <select name="currency_code" id="currency_code" class="js-select2 form-control" style="width:100%;">
                                                    @foreach(getCurrencyList() as $index => $currency_list)
                                                    <option value="{{$index}}" @selected($currencyCode==$index)>{{$index}} - {{$currency_list}}</option>
                                                    @endforeach                                                
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">Facebook URL</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">Twitter URL</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="twitter_url" value="{{ old('twitter_url', $settings->twitter_url) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">Instagram URL</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">LinkedIn URL</label>
                                            <div class="col-sm-9">
                                                <input type="text" name="linkedin_url" value="{{ old('linkedin_url', $settings->linkedin_url) }}" class="form-control">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <label for="avatar" class="col-sm-3 control-label">{{trans('words.footer_logo')}}</label>
                                            <div class="col-sm-9">
                                                <div class="media">
                                                    <div class="media-left">
                                                        @if($settings->site_footer_logo)
                                                         
                                                            <img src="{{ URL::asset('upload/'.$settings->site_footer_logo) }}" class="footer_logo" alt="person">
                                                        @endif
                                                                                        
                                                    </div>
                                                    <div class="media-body media-middle">
                                                        <input type="file" name="site_footer_logo" class="filestyle">
                                                        <small class="text-muted bold">Size 220x52px</small>
                                                    </div>
                                                </div>
                            
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">{{trans('words.footer_text')}}</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" name="site_footer_text" class="form-control" rows="5">{{ old('site_footer_text', $settings->site_footer_text) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">{{trans('words.site_copyright_text')}}</label>
                                            <div class="col-sm-9">
                                                <textarea type="text" name="site_copyright" class="form-control" rows="5">{{ old('site_copyright', $settings->site_copyright) }}</textarea>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group">
                                            <div class="col-md-offset-3 col-sm-9 ">
                                                <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                                                 
                                            </div>
                                        </div>

                                    </form> 
                                    </div>

                                    <div class="col-lg-10 tab-pane" id="smtp_settings">


                                    <form action="{{ url('admin/smtp_settings') }}" class="form-horizontal padding-15" name="pass_form" id="pass_form" role="form" method="POST">
                                        @csrf
                                            
                                             
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Host</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="smtp_host" value="{{ old('smtp_host', $settings->smtp_host) }}" class="form-control" placeholder="mail.example.com">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Port</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="smtp_port" value="{{ old('smtp_port', $settings->smtp_port) }}" class="form-control" placeholder="465">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Email</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="smtp_email" value="{{ old('smtp_email', $settings->smtp_email) }}" class="form-control" placeholder="info@example.com">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Password</label>
                                                <div class="col-sm-9">
                                                    <input type="password" name="smtp_password" value="{{ old('smtp_password', $settings->smtp_password) }}" class="form-control" placeholder="****">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">Encryption
                                            </label>
                                            <div class="col-sm-9">
                                                @php($smtpEncryption = old('smtp_encryption', $settings->smtp_encryption))
                                                <select class="form-control" name="smtp_encryption">                          <option value="SSL" @selected($smtpEncryption=="SSL")>SSL</option>      
                                                    <option value="TLS" @selected($smtpEncryption=="TLS")>TLS</option>                                                       
                                                </select>
                                            </div>
                                        </div>
                                            
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                    <div class="col-lg-10 tab-pane" id="social_settings">


                                    <form action="{{ url('admin/social_login_settings') }}" class="form-horizontal padding-15" name="pass_form" id="pass_form" role="form" method="POST">
                                        @csrf
                                            
                                            <b><i class="fa fa-google"></i> Google Settings</b><br/><br/>

                                        <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">Google Login 
                                            </label>
                                            <div class="col-sm-9">
                                                @php($googleLogin = old('google_login', $settings->google_login))
                                                <select class="form-control" name="google_login">                                
                                                    <option value="1" @selected($googleLogin=="1")>ON</option>
                                                    <option value="0" @selected($googleLogin=="0")>OFF</option>   
                                                </select>
                                            </div>
                                        </div>
                 
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Google Client ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="google_client_id" value="{{ old('google_client_id', $settings->google_client_id) }}" class="form-control">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Google Secret</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="google_client_secret" value="{{ old('google_client_secret', $settings->google_client_secret) }}" class="form-control">
                                                </div>
                                            </div>
                                            <hr>
                                             
                                            <b><i class="fa fa-facebook"></i> Facebook Settings</b><br/><br/>

                                            <div class="form-group">
                                            <label for="" class="col-sm-3 control-label">Facebook Login
                                            </label>
                                            <div class="col-sm-9">
                                            @php($facebookLogin = old('facebook_login', $settings->facebook_login))
                                                <select class="form-control" name="facebook_login">                              
                                                    <option value="1" @selected($facebookLogin=="1")>ON</option>
                                                    <option value="0" @selected($facebookLogin=="0")>OFF</option>   
                                                </select>
                                            </div>
                                        </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Facebook App ID</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="facebook_app_id" value="{{ old('facebook_app_id', $settings->facebook_app_id) }}" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Facebook Client Secret</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="facebook_client_secret" value="{{ old('facebook_client_secret', $settings->facebook_client_secret) }}" class="form-control">
                                                </div>
                                            </div>
                                             
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                    <div class="col-lg-12 tab-pane" id="homepage_settings">
    <form action="{{ url('admin/homepage_settings') }}" class="form-horizontal padding-15" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ========== HERO SECTION ========== --}}
        <div class="panel panel-default">
            <div class="panel-heading"><strong>🌟 Hero Section</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Badge Text</label>
                    <div class="col-sm-9">
                        <input type="text" name="hero_badge_text" value="{{ old('hero_badge_text', $settings->hero_badge_text) }}" class="form-control" placeholder="New Deals Added Daily">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Title</label>
                    <div class="col-sm-9">
                        <input type="text" name="hero_title" value="{{ old('hero_title', $settings->hero_title) }}" class="form-control" placeholder="Shop Local Deals & ">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Title Highlight (colored)</label>
                    <div class="col-sm-9">
                        <input type="text" name="hero_title_highlight" value="{{ old('hero_title_highlight', $settings->hero_title_highlight) }}" class="form-control" placeholder="Save Big!">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Subtitle</label>
                    <div class="col-sm-9">
                        <textarea name="hero_subtitle" class="form-control" rows="2">{{ old('hero_subtitle', $settings->hero_subtitle) }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Disclaimer</label>
                    <div class="col-sm-9">
                        <input type="text" name="hero_disclaimer" value="{{ old('hero_disclaimer', $settings->hero_disclaimer) }}" class="form-control" placeholder="*Projected goals...">
                    </div>
                </div>
                
                {{-- Hero Stats --}}
                <div class="form-group">
                    <label class="col-sm-3 control-label">Hero Stats</label>
                    <div class="col-sm-9">
                        @php($heroStats = old('hero_stats', $settings->hero_stats ?? [
                            ['number' => '500+*', 'label' => 'Local Businesses'],
                            ['number' => '10K+*', 'label' => 'Happy Customers'],
                            ['number' => '$2M+*', 'label' => 'Total Savings'],
                        ]))
                        @foreach($heroStats as $i => $stat)
                        <div class="row" style="margin-bottom:10px;">
                            <div class="col-xs-4">
                                <input type="text" name="hero_stats[{{ $i }}][number]" value="{{ $stat['number'] ?? '' }}" class="form-control" placeholder="500+">
                            </div>
                            <div class="col-xs-8">
                                <input type="text" name="hero_stats[{{ $i }}][label]" value="{{ $stat['label'] ?? '' }}" class="form-control" placeholder="Local Businesses">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== PROMO BANNER ========== --}}
        <div class="panel panel-default">
            <div class="panel-heading"><strong>📢 Promo Banner (Scrolling Text)</strong></div>
            <div class="panel-body">
                @php($promoItems = old('promo_banner_items', $settings->promo_banner_items ?? [
                    ['emoji' => '⚡', 'text' => 'LIMITED TIME OFFERS'],
                    ['emoji' => '🎯', 'text' => 'UP TO 75% OFF'],
                    ['emoji' => '🏆', 'text' => 'BEST LOCAL DEALS'],
                    ['emoji' => '💰', 'text' => 'SAVE MORE TODAY'],
                    ['emoji' => '🔥', 'text' => 'NEW DEALS DAILY'],
                ]))
                @foreach($promoItems as $i => $item)
                <div class="form-group">
                    <label class="col-sm-2 control-label">Item {{ $i + 1 }}</label>
                    <div class="col-sm-2">
                        <input type="text" name="promo_banner_items[{{ $i }}][emoji]" value="{{ $item['emoji'] ?? '' }}" class="form-control" placeholder="⚡">
                    </div>
                    <div class="col-sm-8">
                        <input type="text" name="promo_banner_items[{{ $i }}][text]" value="{{ $item['text'] ?? '' }}" class="form-control" placeholder="LIMITED TIME OFFERS">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ========== SALE BANNER ========== --}}
        <div class="panel panel-default">
            <div class="panel-heading"><strong>🏷️ Sale Banner</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Enabled</label>
                    <div class="col-sm-9">
                        @php($saleBannerEnabled = old('sale_banner_enabled', $settings->sale_banner_enabled ?? false))
                        <input type="hidden" name="sale_banner_enabled" value="0">
                        <input type="checkbox" name="sale_banner_enabled" value="1" {{ $saleBannerEnabled ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Label</label>
                    <div class="col-sm-9">
                        <input type="text" name="sale_banner_label" value="{{ old('sale_banner_label', $settings->sale_banner_label) }}" class="form-control" placeholder="🎉 LIMITED TIME">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Title</label>
                    <div class="col-sm-9">
                        <input type="text" name="sale_banner_title" value="{{ old('sale_banner_title', $settings->sale_banner_title) }}" class="form-control" placeholder="Holiday Deals Event">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Subtitle</label>
                    <div class="col-sm-9">
                        <input type="text" name="sale_banner_subtitle" value="{{ old('sale_banner_subtitle', $settings->sale_banner_subtitle) }}" class="form-control" placeholder="Exclusive savings from local businesses">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Discount %</label>
                    <div class="col-sm-9">
                        <input type="text" name="sale_banner_discount" value="{{ old('sale_banner_discount', $settings->sale_banner_discount) }}" class="form-control" placeholder="75%">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Button Text</label>
                    <div class="col-sm-9">
                        <input type="text" name="sale_banner_button_text" value="{{ old('sale_banner_button_text', $settings->sale_banner_button_text) }}" class="form-control" placeholder="Shop All Deals →">
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== HOW IT WORKS ========== --}}
        <div class="panel panel-default">
            <div class="panel-heading"><strong>📋 How It Works</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Section Label</label>
                    <div class="col-sm-9">
                        <input type="text" name="how_it_works_label" value="{{ old('how_it_works_label', $settings->how_it_works_label) }}" class="form-control" placeholder="Simple Process">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Section Title</label>
                    <div class="col-sm-9">
                        <input type="text" name="how_it_works_title" value="{{ old('how_it_works_title', $settings->how_it_works_title) }}" class="form-control" placeholder="How It Works">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Section Subtitle</label>
                    <div class="col-sm-9">
                        <input type="text" name="how_it_works_subtitle" value="{{ old('how_it_works_subtitle', $settings->how_it_works_subtitle) }}" class="form-control">
                    </div>
                </div>
                
                <hr>
                <p><strong>Steps:</strong></p>
                @php($steps = old('how_it_works_steps', $settings->how_it_works_steps ?? [
                    ['emoji' => '🔍', 'title' => 'Browse Deals', 'description' => 'Explore hundreds of exclusive deals from restaurants, spas, fitness centers and more across Lake County.'],
                    ['emoji' => '🛒', 'title' => 'Purchase & Save', 'description' => 'Buy deals at huge discounts. Pay securely online and receive your voucher instantly via email.'],
                    ['emoji' => '🎉', 'title' => 'Redeem & Enjoy', 'description' => 'Show your voucher at the business to redeem. Enjoy amazing experiences while supporting local!'],
                ]))
                @foreach($steps as $i => $step)
                <div style="background:#f9f9f9; padding:15px; margin-bottom:15px; border-radius:5px;">
                    <p><strong>Step {{ $i + 1 }}</strong></p>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Emoji</label>
                        <div class="col-sm-10">
                            <input type="text" name="how_it_works_steps[{{ $i }}][emoji]" value="{{ $step['emoji'] ?? '' }}" class="form-control" placeholder="🔍">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-10">
                            <input type="text" name="how_it_works_steps[{{ $i }}][title]" value="{{ $step['title'] ?? '' }}" class="form-control" placeholder="Browse Deals">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10">
                            <textarea name="how_it_works_steps[{{ $i }}][description]" class="form-control" rows="2">{{ $step['description'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ========== TESTIMONIALS ========== --}}
        <div class="panel panel-default">
            <div class="panel-heading"><strong>💬 Testimonials</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Section Label</label>
                    <div class="col-sm-9">
                        <input type="text" name="testimonials_label" value="{{ old('testimonials_label', $settings->testimonials_label) }}" class="form-control" placeholder="Customer Love">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Section Title</label>
                    <div class="col-sm-9">
                        <input type="text" name="testimonials_title" value="{{ old('testimonials_title', $settings->testimonials_title) }}" class="form-control" placeholder="What People Say">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Section Subtitle</label>
                    <div class="col-sm-9">
                        <input type="text" name="testimonials_subtitle" value="{{ old('testimonials_subtitle', $settings->testimonials_subtitle) }}" class="form-control">
                    </div>
                </div>
                
                <hr>
                <p><strong>Testimonials:</strong></p>
                @php($testimonials = old('testimonials', $settings->testimonials ?? [
                    ['text' => 'Found an amazing spa deal in Clermont. Saved $120 on a massage package! This site is my go-to for local deals now.', 'name' => 'Jennifer Wilson', 'title' => 'Clermont Resident', 'initials' => 'JW'],
                    ['text' => "Great way to discover new restaurants in Lake County. We've tried 5 new places this month and saved over \$200!", 'name' => 'Michael Rodriguez', 'title' => 'Mount Dora Local', 'initials' => 'MR'],
                    ['text' => 'As a small business owner, this platform helped me reach new customers. Easy to use and great support team!', 'name' => 'Sarah Thompson', 'title' => 'Business Owner', 'initials' => 'ST'],
                ]))
                @foreach($testimonials as $i => $t)
                <div style="background:#f9f9f9; padding:15px; margin-bottom:15px; border-radius:5px;">
                    <p><strong>Testimonial {{ $i + 1 }}</strong></p>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Quote</label>
                        <div class="col-sm-10">
                            <textarea name="testimonials[{{ $i }}][text]" class="form-control" rows="2">{{ $t['text'] ?? '' }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-4">
                            <input type="text" name="testimonials[{{ $i }}][name]" value="{{ $t['name'] ?? '' }}" class="form-control" placeholder="Jennifer Wilson">
                        </div>
                        <label class="col-sm-2 control-label">Title</label>
                        <div class="col-sm-4">
                            <input type="text" name="testimonials[{{ $i }}][title]" value="{{ $t['title'] ?? '' }}" class="form-control" placeholder="Clermont Resident">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Initials</label>
                        <div class="col-sm-4">
                            <input type="text" name="testimonials[{{ $i }}][initials]" value="{{ $t['initials'] ?? '' }}" class="form-control" placeholder="JW" maxlength="3">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ========== CTA SECTION ========== --}}
        <div class="panel panel-default">
            <div class="panel-heading"><strong>📣 CTA Section (Call to Action)</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Title</label>
                    <div class="col-sm-9">
                        <input type="text" name="cta_title" value="{{ old('cta_title', $settings->cta_title) }}" class="form-control" placeholder="Own a Local Business?">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Subtitle</label>
                    <div class="col-sm-9">
                        <input type="text" name="cta_subtitle" value="{{ old('cta_subtitle', $settings->cta_subtitle) }}" class="form-control" placeholder="List your deals and reach thousands of Lake County customers today">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Button Text</label>
                    <div class="col-sm-9">
                        <input type="text" name="cta_button_text" value="{{ old('cta_button_text', $settings->cta_button_text) }}" class="form-control" placeholder="List Your Business Free →">
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== FOOTER ========== --}}
        <div class="panel panel-default">
            <div class="panel-heading"><strong>📄 Footer</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Footer Description</label>
                    <div class="col-sm-9">
                        <textarea name="footer_description" class="form-control" rows="2">{{ old('footer_description', $settings->footer_description) }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Copyright Text</label>
                    <div class="col-sm-9">
                        <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $settings->footer_copyright) }}" class="form-control" placeholder="© 2025 Lake County Local Deals. All rights reserved.">
                    </div>
                </div>
            </div>
        </div>

        <hr>
        <div class="form-group">
            <div class="col-sm-12">
                <button type="submit" class="btn btn-primary btn-lg">{{trans('words.save_settings')}} <i class="fa fa-check"></i></button>
            </div>
        </div>
    </form>
</div>
                                     

                                    <div class="col-lg-10 tab-pane" id="aboutus_settings">


                                            <form action="{{ url('admin/aboutus_settings') }}" class="form-horizontal padding-15" name="pass_form" id="pass_form" role="form" method="POST">
                                            @csrf
                
                 
                                           <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.title')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="about_title" value="{{ old('about_title', $settings->about_title) }}" class="form-control">
                                                </div>
                                            </div>  
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.about_page')}}</label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" name="about_description" class="elm1_editor" rows="5">{{ old('about_description', $settings->about_description) }}</textarea>
                                                </div>
                                                 
                                            </div>
                                             
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                    <div class="col-lg-10 tab-pane" id="contactus_settings">


                                            <form action="{{ url('admin/contactus_settings') }}" class="form-horizontal padding-15" name="contactus_settings_form" id="contactus_settings_form" role="form" method="POST">
                                            @csrf
                
                 
                                           <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.title')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="contact_title" value="{{ old('contact_title', $settings->contact_title) }}" class="form-control">
                                                </div>
                                            </div>  
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.address')}}</label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" name="contact_address" class="form-control" rows="5">{{ old('contact_address', $settings->contact_address) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.contact_email')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="contact_email" value="{{ old('contact_email', $settings->contact_email) }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.contact_number')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="contact_number" value="{{ old('contact_number', $settings->contact_number) }}" class="form-control">
                                                </div>
                                            </div>
                                             
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>


                                    <div class="col-lg-10 tab-pane" id="terms_of_service">


                                            <form action="{{ url('admin/terms_of_service') }}" class="form-horizontal padding-15" name="terms_of_service_form" id="terms_of_service_form" role="form" method="POST">
                                            @csrf
                
                 
                                           <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.title')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="terms_of_title" value="{{ old('terms_of_title', $settings->terms_of_title) }}" class="form-control">
                                                </div>
                                            </div>  
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.terms_of_service')}} </label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" name="terms_of_description" class="elm1_editor" rows="5">{{ old('terms_of_description', $settings->terms_of_description) }}</textarea>
                                                </div>
                                                 
                                            </div>
                                             
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                    <div class="col-lg-10 tab-pane" id="privacy_policy">


                                            <form action="{{ url('admin/privacy_policy') }}" class="form-horizontal padding-15" name="privacy_policy_form" id="privacy_policy_form" role="form" method="POST">
                                            @csrf
                
                 
                                           <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.title')}}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="privacy_policy_title" value="{{ old('privacy_policy_title', $settings->privacy_policy_title) }}" class="form-control">
                                                </div>
                                            </div>  
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">{{trans('words.privacy_policy')}} </label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" name="privacy_policy_description" class="elm1_editor" rows="5">{{ old('privacy_policy_description', $settings->privacy_policy_description) }}</textarea>
                                                </div>
                                                 
                                            </div>
                                             
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                    
                                    <div class="col-lg-10 tab-pane" id="other_Settings">


                                            <form action="{{ url('admin/headfootupdate') }}" class="form-horizontal padding-15" name="pass_form" id="pass_form" role="form" method="POST">
                                            @csrf
                
                 
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Header Code</label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" name="site_header_code" class="form-control" rows="5" placeholder="You may want to add some html/css/js code to header. ">{{ old('site_header_code', $settings->site_header_code) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="" class="col-sm-3 control-label">Footer Code</label>
                                                <div class="col-sm-9">
                                                    <textarea type="text" name="site_footer_code" class="form-control" rows="5" placeholder="You may want to add some html/css/js code to footer. ">{{ old('site_footer_code', $settings->site_footer_code) }}</textarea>
                                                </div>
                                            </div>
                                             
                                            <hr>
                                            <div class="form-group">
                                                <div class="col-md-offset-3 col-sm-9">
                                                    <button type="submit" class="btn btn-primary">{{trans('words.save_settings')}} <i class="md md-lock-open"></i></button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>

                                </div>
                            </div>
                            <!-- END Block Tabs Alternative Style -->
                        </div>
                        
                    </div>
                </div>
                <!-- END Page Content -->

                

@endsection

