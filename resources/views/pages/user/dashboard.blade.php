@extends('app')

@section('head_title',trans('words.dashboard').' | '.getcong('site_name') )

@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', trans('words.dashboard'))
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => trans('words.dashboard'), 'url' => '']]))

@section("content")

@include('common.page-hero-header') 

<!-- ================================
    Start Dashboard Area
================================= -->
<section class="dashboard-area bg-gray section_item_padding">
    <div class="container">

            @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                        {{ Session::get('flash_message') }}
                    </div>
            @endif

            @if(Session::has('error_flash_message'))
                    <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                        {{ Session::get('error_flash_message') }}
                    </div>
            @endif

        <div class="row">
 
            <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">               
               <div class="profile-block card card-body">
                 <div class="img-profile">
                        
                      @if(Auth::user()->image_icon)
                        <img alt="User Photo" src="{{URL::to('upload/members/'.Auth::user()->image_icon)}}" class="img-rounded" alt="profile_img" title="profile pic">
                      @else
                        <img src="{{URL::to('assets/images/avatar.jpg')}}" class="img-rounded" alt="profile_img" title="profile pic">
                      @endif

                        
                </div>
                   <div class="profile-title-item">
                      <h5>{{Auth::user()->first_name.' '.Auth::user()->last_name}}</h5>
                      <span>{{Auth::user()->email}}</span>
                      <a href="{{URL::to('profile')}}" class="primary_item_btn mb-2 mr-1">{{trans('words.edit_profile')}}</a>
                    </div>
               </div>
               @if(Auth::user()->isVendor())
                   @include('vendor.partials.subscription-widget')
               @endif

               <!-- Subscription Card -->
               <div class="card mb-3">
                   <div class="card-body">
                       <h4 class="card-title mb-3">Subscription Plan</h4>
                       @if($subscription)
                           @php
                               $tierName = ucfirst($subscription->package_tier);
                               $price = $subscription->package_tier === 'starter' ? 'FREE' : '$' . number_format($packageFeatures->monthly_price ?? 0, 0) . '/mo';
                               $statusBadge = [
                                   'active' => ['class' => 'badge-success', 'text' => 'Active'],
                                   'canceled' => ['class' => 'badge-danger', 'text' => 'Canceled'],
                                   'past_due' => ['class' => 'badge-warning', 'text' => 'Past Due'],
                                   'trialing' => ['class' => 'badge-info', 'text' => 'Trialing'],
                               ];
                               $status = $statusBadge[$subscription->status] ?? ['class' => 'badge-secondary', 'text' => ucfirst($subscription->status)];
                           @endphp
                           <ul class="list-items mb-4">
                               <li><strong>Plan:</strong> {{ $tierName }} Plan</li>
                               <li><strong>Price:</strong> {{ $price }}</li>
                               <li><strong>Status:</strong> <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span></li>
                               @if($subscription->current_period_end)
                               <li><strong>Next Billing:</strong> {{ $subscription->current_period_end->format('M d, Y') }}</li>
                               @endif
                           </ul>
                           <div class="d-flex gap-2">
                               <a href="{{ route('pricing') }}" class="btn btn-sm btn-primary mb-2">Upgrade Plan</a>
                               @if($subscription->package_tier !== 'starter')
                               <a href="{{ route('subscription.portal') }}" class="btn btn-sm btn-outline-secondary mb-2">Manage Subscription</a>
                               @endif
                           </div>
                       @else
                           <p class="text-muted">No active subscription</p>
                           <a href="{{ route('pricing') }}" class="btn btn-sm btn-primary">Select Plan</a>
                       @endif
                   </div>
               </div>

               <!-- Usage Statistics Card -->
               @if($packageFeatures)
               <div class="card mb-3">
                   <div class="card-body">
                       <h4 class="card-title mb-3">Usage Statistics</h4>
                       
                       <!-- Simultaneous Deals -->
                       <div class="mb-3">
                           <div class="d-flex justify-content-between mb-1">
                               <span><strong>Active Deals:</strong></span>
                               <span>
                                   {{ $activeDealsCount }} 
                                   @if($packageFeatures->simultaneous_deals == -1)
                                       (Unlimited)
                                   @else
                                       / {{ $packageFeatures->simultaneous_deals }}
                                   @endif
                               </span>
                           </div>
                           @if($packageFeatures->simultaneous_deals != -1)
                           <div class="progress" style="height: 20px;">
                               @php
                                   $usagePercent = min(100, ($activeDealsCount / $packageFeatures->simultaneous_deals) * 100);
                                   $progressClass = $usagePercent >= 100 ? 'bg-danger' : ($usagePercent >= 80 ? 'bg-warning' : 'bg-success');
                               @endphp
                               <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $usagePercent }}%">
                                   {{ number_format($usagePercent, 0) }}%
                               </div>
                           </div>
                           @if($usagePercent >= 100)
                           <small class="text-danger mt-1 d-block">
                               <i class="fa fa-exclamation-triangle"></i> You've reached your limit. Upgrade for more.
                           </small>
                           @endif
                           @endif
                       </div>

                       <!-- Total Inventory -->
                       <div class="mb-3">
                           <strong>Total Deal Inventory:</strong> {{ number_format($totalInventory) }} spots (across {{ $activeDealsCount }} {{ $activeDealsCount == 1 ? 'deal' : 'deals' }})
                       </div>

                       <!-- Feature Access -->
                       <div class="mt-3">
                           <strong>Feature Access:</strong>
                           <ul class="list-unstyled mt-2">
                               <li>
                                   <i class="fa {{ $packageFeatures->ai_scoring_enabled ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                   AI Scoring
                               </li>
                               <li>
                                   <i class="fa {{ $packageFeatures->analytics_access ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                   Analytics Access
                               </li>
                               <li>
                                   <i class="fa {{ $packageFeatures->priority_placement ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                   Priority Placement
                               </li>
                               <li>
                                   <i class="fa {{ $packageFeatures->featured_placement ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                   Featured Placement
                               </li>
                               <li>
                                   <i class="fa {{ $packageFeatures->api_access ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                   API Access
                               </li>
                               <li>
                                   <i class="fa {{ $packageFeatures->auto_approval ? 'fa-check text-success' : 'fa-times text-danger' }}"></i>
                                   Auto-Approval
                               </li>
                           </ul>
                       </div>
                   </div>
               </div>
               @endif

               <!-- Vendor Training Widget -->
               @if(config('training.enabled') && Auth::user()->usertype != 'Admin')
                   @php
                       $user = Auth::user();
                       $hasCompletedAll = $user->hasCompletedAllTraining();
                       $completedCount = $user->getCompletedCoursesCount();
                       $totalCourses = count(config('training.courses', []));
                       $nextCourse = $user->getNextCourse();
                   @endphp
                   <div class="card mb-3 {{ $hasCompletedAll ? 'border-success' : 'border-warning' }}">
                       <div class="card-body">
                           <h4 class="card-title mb-3">
                               <i class="fa fa-graduation-cap"></i> Vendor Training
                               @if($hasCompletedAll)
                                   <span class="badge badge-success float-right">Complete</span>
                               @else
                                   <span class="badge badge-warning float-right">Required</span>
                               @endif
                           </h4>
                           
                           @if($hasCompletedAll)
                               <div class="alert alert-success mb-3">
                                   <i class="fa fa-check-circle"></i> <strong>Congratulations!</strong> You've completed all training courses.
                               </div>
                           @else
                               <div class="alert alert-warning mb-3">
                                   <i class="fa fa-exclamation-triangle"></i> <strong>Training Required:</strong> Complete all courses to unlock deal creation.
                               </div>
                               
                               <div class="progress mb-3" style="height: 25px;">
                                   @php
                                       $percentage = $totalCourses > 0 ? ($completedCount / $totalCourses) * 100 : 0;
                                   @endphp
                                   <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%">
                                       {{ $completedCount }} / {{ $totalCourses }} Courses
                                   </div>
                               </div>
                               
                               @if($nextCourse)
                                   <p class="mb-2"><strong>Next Course:</strong> {{ $nextCourse['title'] }}</p>
                               @endif
                           @endif
                           
                           <a href="{{ route('vendor.training.index') }}" class="btn btn-primary btn-block">
                               <i class="fa fa-book"></i> View Training Courses
                           </a>
                       </div>
                   </div>
               @endif

               <!-- Legacy Plan Info (for backward compatibility) -->
               <div class="card">
                   <div class="card-body">
                       <h4 class="card-title mb-3">{{trans('words.plan_info')}}</h4>
                       @if($user->plan_id!=0) 
                       <ul class="list-items mb-4">
                           <li>{{trans('words.plan_name')}}:  {{\App\Models\SubscriptionPlan::getSubscriptionPlanInfo($user->plan_id)->plan_name}} </li>
                           
                           <li>{{trans('words.purchase_date')}}:  <span style="background: #5cb85c;color: #ffffff;padding: 3px 6px;border-radius: 4px;">{{date('D, d M Y',$user->start_date)}}</span> </li>

                           <li>{{trans('words.expiry_date')}}: <span style="background: #ea5555;color: #ffffff;padding: 3px 6px;border-radius: 4px;">{{date('D, d M Y',$user->exp_date)}}</span>  </li>
                           
                           <li>{{trans('words.listings')}} {{trans('words.allowed')}}:  {{\App\Models\SubscriptionPlan::getSubscriptionPlanInfo($user->plan_id)->plan_listing_limit}} </li>
                        </ul>
                        <a href="{{URL::to('pricing')}}" class="primary_item_btn mb-2 mr-1">{{trans('words.upgrade_plan')}}</a>   
                        @else
                        <a href="{{URL::to('pricing')}}" class="primary_item_btn mb-2 mr-1">{{trans('words.select_plan')}}</a>    
                        @endif
                   </div>
               </div>
            </div>
            <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
                <!-- Quick Actions -->
                @if(config('training.enabled') && Auth::user()->usertype != 'Admin')
                    @php
                        $user = Auth::user();
                        $hasCompletedAll = $user->hasCompletedAllTraining();
                        $completedCount = $user->getCompletedCoursesCount();
                        $totalCourses = count(config('training.courses', []));
                    @endphp
                    @if(!$hasCompletedAll)
                        <div class="alert alert-warning mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fa fa-exclamation-triangle"></i> <strong>Training Required:</strong> Complete {{ $totalCourses - $completedCount }} more course(s) to unlock deal creation.
                                </div>
                                <a href="{{ route('vendor.training.index') }}" class="btn btn-warning">
                                    <i class="fa fa-graduation-cap"></i> Complete Training
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
                
                <div class="row dashboard-list-item">
                    <div class="col-lg-4">
                        <div class="icon-card">
                            <div class="icon purple"><i class="fal fa-list"></i></div>
                            <div class="content">
                              <h6 class="mb-10">{{trans('words.all_listings')}}</h6>
                              <h3 class="text-bold mb-10">{{$total_listings}}</h3>                             
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="icon-card">
                            <div class="icon success"><i class="fal fa-check-circle"></i></div>
                            <div class="content">
                              <h6 class="mb-10">{{trans('words.published')}}</h6>
                              <h3 class="text-bold mb-10">{{$publish_listings}}</h3>                             
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="icon-card">
                            <div class="icon primary"><i class="fal fa-clock"></i></div>
                            <div class="content">
                              <h6 class="mb-10">{{trans('words.pending')}}</h6>
                              <h3 class="text-bold mb-10">{{$pending_listings}}</h3>                             
                            </div>
                        </div>
                    </div>
                    @if(config('training.enabled') && Auth::user()->usertype != 'Admin')
                    <div class="col-lg-4">
                        <a href="{{ route('vendor.training.index') }}" class="icon-card" style="text-decoration: none; color: inherit;">
                            <div class="icon success"><i class="fal fa-graduation-cap"></i></div>
                            <div class="content">
                              <h6 class="mb-10">Training</h6>
                              <h3 class="text-bold mb-10">
                                  @php
                                      $user = Auth::user();
                                      $completedCount = $user->getCompletedCoursesCount();
                                      $totalCourses = count(config('training.courses', []));
                                  @endphp
                                  {{ $completedCount }}/{{ $totalCourses }}
                              </h3>
                              <small>Courses Completed</small>
                            </div>
                        </a>
                    </div>
                    @endif
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                      <div class="table-wrapper">
                         <table class="fl-table">
                            <thead>
                                <tr>
                                    <th>#{{trans('words.id')}}</th>
                                    <th>{{trans('words.title')}}</th>
                                    <th>{{trans('words.status')}}</th>
                                    <th>{{trans('words.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($my_listings as $listings_data)
                                <tr>
                                    <td>{{$listings_data->id}}</td>
                                    <td class="user-list-title"><span>{{$listings_data->title}}</span></td>
                                    <td>
                                    @if($listings_data->status==0)
                                        <span class="expires-plan-item">{{trans('words.pending')}}</span>     @else
                                        <span class="current-plan-item">{{trans('words.published')}}</span>
                                    @endif
                                    </td>
                                     
                                    <td>
                                        <a href="{{URL::to('edit_listing/'.$listings_data->id)}}" class="btn btn-sm edit-btn bg-success text-white mr-1">{{trans('words.edit')}}</a>
                                        <a href="{{URL::to('delete_listing/'.$listings_data->id)}}" class="btn btn-sm delete-btn bg-danger text-white" onclick="return confirm('{{trans('words.remove_cofirm_msg')}}')">{{trans('words.delete')}}</a>
                                    </td>
                                </tr>
                                @endforeach
                                 
                            </tbody>
                            <tbody></tbody>
                         </table>
                      </div>
                    </div>
                     
                     
                </div>
           </div>
        </div>
    </div>
</section>
<!-- ================================
     End Dashboard Area
================================= --> 

 
@endsection