@extends('app')

@section('head_title', 'Vendor Training | ' . getcong('site_name'))
@section('head_url', Request::url())

@section("content")

<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">Vendor Training</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li><a href="{{URL::to('dashboard')}}">Dashboard</a></li>
                <li>Training</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area Area
================================= --> 

<!-- ================================
    Start Training Area
================================= -->
<section class="dashboard-area bg-gray section_item_padding">
    <div class="container">
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ Session::get('flash_message') }}
            </div>
        @endif

        @if(Session::has('error_flash_message'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ Session::get('error_flash_message') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Vendor Training Courses</h3>
                        <p class="text-muted mb-0">Complete these courses to unlock deal creation and maximize your success on our platform.</p>
                    </div>
                    <div class="card-body">
                        @if($user->hasCompletedAllTraining())
                            <div class="alert alert-success">
                                <i class="fa fa-check-circle"></i> <strong>Congratulations!</strong> You've completed all required training courses.
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> <strong>Training Required:</strong> You must complete all training courses before creating deals.
                            </div>
                        @endif

                        <div class="row">
                            @foreach($coursesWithProgress as $item)
                                @php
                                    $course = $item['course'];
                                    $progress = $item['progress'];
                                    $isCompleted = $item['isCompleted'];
                                    $isLocked = $item['isLocked'];
                                @endphp
                                
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 {{ $isLocked ? 'border-secondary' : ($isCompleted ? 'border-success' : 'border-primary') }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0">
                                                    Course {{ $course['number'] }}: {{ $course['title'] }}
                                                    @if($isCompleted)
                                                        <span class="badge badge-success ml-2">Completed</span>
                                                    @elseif($isLocked)
                                                        <span class="badge badge-secondary ml-2">Locked</span>
                                                    @else
                                                        <span class="badge badge-primary ml-2">Available</span>
                                                    @endif
                                                </h5>
                                            </div>
                                            
                                            <p class="text-muted">{{ $course['description'] }}</p>
                                            
                                            <div class="mb-3">
                                                <small class="text-muted">
                                                    <i class="fa fa-clock"></i> Duration: {{ $course['duration_minutes'] }} minutes
                                                </small>
                                            </div>

                                            @if($isLocked)
                                                <div class="alert alert-warning mb-0">
                                                    <i class="fa fa-lock"></i> Complete Course {{ $course['prerequisite'] }} first to unlock this course.
                                                </div>
                                            @elseif($isCompleted)
                                                <a href="{{ route('vendor.training.show', $course['number']) }}" class="btn btn-success">
                                                    <i class="fa fa-check"></i> Review Course
                                                </a>
                                            @else
                                                <a href="{{ route('vendor.training.show', $course['number']) }}" class="btn btn-primary">
                                                    <i class="fa fa-play"></i> Start Course
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <h5>Progress Overview</h5>
                            <div class="progress mb-3" style="height: 30px;">
                                @php
                                    $totalCourses = count($coursesWithProgress);
                                    $completedCourses = $user->getCompletedCoursesCount();
                                    $percentage = $totalCourses > 0 ? ($completedCourses / $totalCourses) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $completedCourses }} / {{ $totalCourses }} Courses Completed ({{ round($percentage) }}%)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================================
    End Training Area
================================= -->

@endsection

