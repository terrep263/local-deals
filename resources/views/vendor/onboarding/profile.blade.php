@extends('app')

@section('head_title', 'Complete Business Profile | ' . getcong('site_name'))
@section('head_url', Request::url())
@section('use_hero_header', true)
@section('hero_title', 'Complete Your Profile')
@section('hero_breadcrumbs', json_encode([['label' => trans('words.home'), 'url' => '/'], ['label' => 'Profile', 'url' => '']]))

@section('content')

@include('common.page-hero-header')

<section class="dashboard-area pt-40 pb-60">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Complete Business Profile</h4>
                        <form action="{{ route('vendor.onboarding.profile.save') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label>Business Description<span class="text-danger">*</span></label>
                                <textarea name="business_description" class="form-control" rows="5" required>{{ old('business_description', $vendor->business_description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Business Logo (optional, max 2MB)</label>
                                <input type="file" name="business_logo" class="form-control">
                                @if($vendor->business_logo)
                                    <p class="mt-2">Current: <img src="{{ asset('storage/'.$vendor->business_logo) }}" alt="Logo" style="max-height:60px;"></p>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Business Hours (optional)</label>
                                @php
                                    $days = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
                                    $labels = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
                                    $hours = $vendor->business_hours ?? [];
                                @endphp
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Day</th>
                                                <th>Open</th>
                                                <th>Close</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($days as $idx => $day)
                                                <tr>
                                                    <td>{{ $labels[$idx] }}</td>
                                                    <td><input type="time" name="business_hours[{{ $day }}][open]" class="form-control" value="{{ old('business_hours.'.$day.'.open', $hours[$day]['open'] ?? '') }}"></td>
                                                    <td><input type="time" name="business_hours[{{ $day }}][close]" class="form-control" value="{{ old('business_hours.'.$day.'.close', $hours[$day]['close'] ?? '') }}"></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Profile</button>
                                <a href="{{ route('vendor.onboarding.index') }}" class="btn btn-default">Back to Onboarding</a>
                            </div>
                        </form>
                        <p class="text-muted mb-0">You must also connect Stripe to finish onboarding.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

