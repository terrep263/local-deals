@extends('app')

@section('head_title', $course['title'] . ' | ' . getcong('site_name'))
@section('head_url', Request::url())

@section("content")

<!-- ================================
     Start Breadcrumb Area
================================= -->
<section class="breadcrumb-area" style="background-image:url({{URL::to('assets/images/bread-bg.jpg')}})">
    <div class="overlay"></div>
    <div class="container">
        <div class="breadcrumb-content">
            <h2 class="item_sec_title text-white">{{ $course['title'] }}</h2>
            <ul class="bread-list">
                <li><a href="{{URL::to('/')}}">{{trans('words.home')}}</a></li>
                <li><a href="{{URL::to('dashboard')}}">Dashboard</a></li>
                <li><a href="{{route('vendor.training.index')}}">Training</a></li>
                <li>Course {{ $course['number'] }}</li>
            </ul>
        </div>
    </div>    
</section>
<!-- ================================
     End Breadcrumb Area Area
================================= --> 

<!-- ================================
    Start Course Area
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Course {{ $course['number'] }}: {{ $course['title'] }}</h3>
                        <p class="text-muted mb-0">{{ $course['description'] }}</p>
                    </div>
                    <div class="card-body">
                        @if($progress->isCompleted())
                            <div class="alert alert-success mb-4">
                                <i class="fa fa-check-circle"></i> <strong>Completed!</strong> You completed this course on {{ $progress->completed_at->format('F d, Y') }}.
                            </div>
                        @else
                            <div class="alert alert-info mb-4">
                                <i class="fa fa-info-circle"></i> <strong>In Progress:</strong> You started this course on {{ $progress->started_at->format('F d, Y') }}.
                            </div>
                        @endif

                        <!-- Course Embed -->
                        <div class="mb-4">
                            <div class="embed-responsive embed-responsive-16by9" style="min-height: 600px;">
                                <iframe 
                                    src="{{ $course['embed_url'] }}" 
                                    class="embed-responsive-item" 
                                    allowfullscreen
                                    allow="autoplay; fullscreen">
                                </iframe>
                            </div>
                        </div>

                        <!-- Course Info -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa fa-clock fa-2x text-primary mb-2"></i>
                                        <h5>Duration</h5>
                                        <p class="mb-0">{{ $course['duration_minutes'] }} minutes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa fa-graduation-cap fa-2x text-success mb-2"></i>
                                        <h5>Status</h5>
                                        <p class="mb-0">{{ $progress->isCompleted() ? 'Completed' : 'In Progress' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fa fa-book fa-2x text-info mb-2"></i>
                                        <h5>Course Number</h5>
                                        <p class="mb-0">{{ $course['number'] }} of {{ count(config('training.courses')) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center">
                            @if($progress->isCompleted())
                                <form action="{{ route('vendor.training.complete', $course['number']) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fa fa-check"></i> Mark Complete & Continue
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('vendor.training.complete', $course['number']) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fa fa-check-circle"></i> Mark Complete & Continue
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('vendor.training.index') }}" class="btn btn-secondary btn-lg ml-2">
                                <i class="fa fa-arrow-left"></i> Back to Courses
                            </a>
                        </div>

                        <div class="mt-4">
                            <div class="alert alert-warning">
                                <i class="fa fa-exclamation-triangle"></i> <strong>Important:</strong> Please watch the entire course and complete any quizzes before marking as complete. This training is essential for your success on our platform.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ================================
    End Course Area
================================= -->

@endsection

