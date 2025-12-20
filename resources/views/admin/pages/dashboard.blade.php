@extends("admin.admin_app")

@section("content")

 <!-- Page Header -->
                <div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
                    <div class="push-50-t push-15">
                        <h1 class="h2 text-white animated zoomIn">{{trans('words.dashboard')}}</h1>
                        <h2 class="h5 text-white-op animated zoomIn">{{trans('words.welcome')}} {{Auth::user()->first_name}} {{Auth::user()->last_name}}</h2>
                    </div>
                </div>
                <!-- END Page Header -->

                <!-- Stats -->
                <div class="content content-narrow">
                   <div class="row">
                        <!-- Module 6: Deal Management -->
                        <div class="col-sm-6 col-lg-3">
                            <a class="block block-link-hover1 text-center" href="{{ route('admin.deals.index') }}">
                                <div class="block-content block-content-full bg-primary">
                                    <i class="fa fa-tags fa-5x text-white"></i>
                                </div>
                                <div class="block-content block-content-full block-content-mini">
                                    <strong>{{$deals}}</strong> Total Deals
                                    @if($pendingDeals > 0)
                                    <br><small class="text-warning">{{$pendingDeals}} Pending</small>
                                    @endif
                                </div>
                            </a>
                        </div>
                        
                        <!-- Module 6: Vendors -->
                        <div class="col-sm-6 col-lg-3">
                            <a class="block block-link-hover1 text-center" href="{{ route('admin.vendors.index') }}">
                                <div class="block-content block-content-full bg-success">
                                    <i class="fa fa-store fa-5x text-white"></i>
                                </div>
                                <div class="block-content block-content-full block-content-mini">
                                    <strong>{{$vendors}}</strong> Vendors
                                    <br><small>{{$activeSubscriptions}} Active Subscriptions</small>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Module 6: Support Tickets -->
                        <div class="col-sm-6 col-lg-3">
                            <a class="block block-link-hover1 text-center" href="{{ route('admin.support.index') }}">
                                <div class="block-content block-content-full bg-warning">
                                    <i class="fa fa-ticket fa-5x text-white"></i>
                                </div>
                                <div class="block-content block-content-full block-content-mini">
                                    <strong>{{$openTickets}}</strong> Open Tickets
                                </div>
                            </a>
                        </div>
                        
                        <!-- Module 6: Revenue -->
                        <div class="col-sm-6 col-lg-3">
                            <a class="block block-link-hover1 text-center" href="{{ route('admin.reports.index') }}">
                                <div class="block-content block-content-full bg-info">
                                    <i class="fa fa-dollar fa-5x text-white"></i>
                                </div>
                                <div class="block-content block-content-full block-content-mini">
                                    <strong>${{number_format($totalRevenue, 2)}}</strong> Total Revenue
                                    <br><small>Today: ${{number_format($todayRevenue, 2)}}</small>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Module 6: Active Deals -->
                        <div class="col-sm-6 col-lg-3">
                            <a class="block block-link-hover1 text-center" href="{{ route('admin.deals.index', ['tab' => 'active']) }}">
                                <div class="block-content block-content-full bg-modern">
                                    <i class="fa fa-check-circle fa-5x text-white"></i>
                                </div>
                                <div class="block-content block-content-full block-content-mini">
                                    <strong>{{$activeDeals}}</strong> Active Deals
                                </div>
                            </a>
                        </div>
                        
                        <!-- Module 1: Subscriptions -->
                        <div class="col-sm-6 col-lg-3">
                            <a class="block block-link-hover1 text-center" href="{{ route('admin.subscriptions.index') }}">
                                <div class="block-content block-content-full bg-amethyst">
                                    <i class="fa fa-credit-card fa-5x text-white"></i>
                                </div>
                                <div class="block-content block-content-full block-content-mini">
                                    <strong>{{$activeSubscriptions}}</strong> Active Subscriptions
                                </div>
                            </a>
                        </div>
                        
                        <!-- Vendor Training -->
                        @if(config('training.enabled'))
                            @php
                                try {
                                    $totalVendors = \App\Models\User::where('usertype', '!=', 'Admin')->count();
                                    $trainedVendors = \App\Models\User::where('usertype', '!=', 'Admin')
                                        ->get()
                                        ->filter(function($user) {
                                            return $user->hasCompletedAllTraining();
                                        })
                                        ->count();
                                    $trainingCompletionRate = $totalVendors > 0 ? round(($trainedVendors / $totalVendors) * 100, 1) : 0;
                                } catch (\Exception $e) {
                                    $totalVendors = 0;
                                    $trainedVendors = 0;
                                    $trainingCompletionRate = 0;
                                }
                            @endphp
                            <div class="col-sm-6 col-lg-3">
                                <a class="block block-link-hover1 text-center" href="{{ route('vendor.training.index') }}" title="View Vendor Training">
                                    <div class="block-content block-content-full bg-smooth">
                                        <i class="fa fa-graduation-cap fa-5x text-white"></i>
                                    </div>
                                    <div class="block-content block-content-full block-content-mini">
                                        <strong>{{$trainedVendors}}/{{$totalVendors}}</strong> Trained Vendors
                                        <br><small>{{$trainingCompletionRate}}% Completion Rate</small>
                                    </div>
                                </a>
                            </div>
                        @endif
                        
                        <!-- Original Stats -->
                        <div class="col-sm-6 col-lg-3">
                            <a class="block block-link-hover1 text-center" href="{{ URL::to('admin/categories') }}">
                                <div class="block-content block-content-full bg-primary">
                                    <i class="fa fa-bars fa-5x text-white"></i>
                                </div>
                                <div class="block-content block-content-full block-content-mini">
                                    <strong>{{$categories}}</strong> {{trans('words.categories')}}
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <a class="block block-link-hover1 text-center" href="{{ URL::to('admin/users') }}">
                                <div class="block-content block-content-full bg-city">
                                    <i class="fa fa-users fa-5x text-white"></i>
                                </div>
                                <div class="block-content block-content-full block-content-mini">
                                    <strong>{{$users}}</strong> {{trans('words.users')}}
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- END Stats -->

                <!-- Page Content -->
                <div class="content">
                    <div class="row">
                       
                    </div>
                     
                </div>
                <!-- END Page Content -->

@endsection