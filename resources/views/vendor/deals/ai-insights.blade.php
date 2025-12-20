@extends('app')

@section('head_title', 'AI Insights - ' . $deal->title)
@section('content')

<section class="section_item_padding bg-gray">
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4">
                <h2 class="mb-3">AI Insights: {{ $deal->title }}</h2>
                <a href="{{ route('vendor.deals.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa fa-arrow-left"></i> Back to Deals
                </a>
                <a href="{{ route('vendor.deals.edit', $deal) }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa fa-edit"></i> Edit Deal
                </a>
            </div>
        </div>

        @if($analysis)
        <!-- Score Card -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h3 class="mb-3">Quality Score</h3>
                        <div class="display-1 mb-3 text-{{ $analysis->getScoreColor() }}">
                            {{ $analysis->score }}<small class="text-muted">/100</small>
                        </div>
                        <h4 class="text-{{ $analysis->getScoreColor() }}">{{ $analysis->getScoreLabel() }}</h4>
                        <p class="text-muted mb-0">Last analyzed: {{ $analysis->analyzed_at->format('F d, Y g:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Strengths -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa fa-check-circle"></i> Strengths</h5>
                    </div>
                    <div class="card-body">
                        @if(count($analysis->strengths) > 0)
                        <ul class="list-unstyled mb-0">
                            @foreach($analysis->strengths as $strength)
                            <li class="mb-2">
                                <i class="fa fa-check text-success"></i> {{ $strength }}
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-muted mb-0">No strengths identified.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Weaknesses -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fa fa-times-circle"></i> Weaknesses</h5>
                    </div>
                    <div class="card-body">
                        @if(count($analysis->weaknesses) > 0)
                        <ul class="list-unstyled mb-0">
                            @foreach($analysis->weaknesses as $weakness)
                            <li class="mb-2">
                                <i class="fa fa-times text-danger"></i> {{ $weakness }}
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-muted mb-0">No weaknesses identified.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Suggestions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fa fa-lightbulb"></i> Optimization Suggestions</h5>
                    </div>
                    <div class="card-body">
                        @if(count($analysis->suggestions) > 0)
                        <ol class="mb-0">
                            @foreach($analysis->suggestions as $suggestion)
                            <li class="mb-2">{{ $suggestion }}</li>
                            @endforeach
                        </ol>
                        @else
                        <p class="text-muted mb-0">No suggestions at this time.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Competitive Analysis -->
        @if($analysis->competitive_analysis)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-chart-line"></i> Competitive Analysis</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $analysis->competitive_analysis }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @else
        <!-- No Analysis Yet -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <h5>No AI Analysis Available</h5>
                    <p>This deal hasn't been analyzed yet. Click the button below to generate AI insights.</p>
                    <form action="{{ route('vendor.deals.rescore', $deal) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-magic"></i> Generate AI Analysis
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        <!-- Competitive Pricing Analysis -->
        @if($pricingAnalysis)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fa fa-dollar-sign"></i> Competitive Pricing Analysis</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Your Discount:</strong> {{ $pricingAnalysis['your_discount'] }}%</p>
                                @if($pricingAnalysis['market_avg_discount'])
                                <p><strong>Market Average:</strong> {{ $pricingAnalysis['market_avg_discount'] }}%</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <p><strong>Your Price:</strong> ${{ number_format($pricingAnalysis['your_price'], 2) }}</p>
                                @if($pricingAnalysis['market_avg_price'])
                                <p><strong>Market Average:</strong> ${{ number_format($pricingAnalysis['market_avg_price'], 2) }}</p>
                                @endif
                            </div>
                        </div>
                        @if($pricingAnalysis['position'] !== 'no_competition')
                        <div class="mt-3">
                            @php
                                $badgeClass = match($pricingAnalysis['position']) {
                                    'very_competitive' => 'success',
                                    'competitive', 'strong_discount' => 'info',
                                    'weak' => 'warning',
                                    default => 'secondary'
                                };
                                $badgeText = match($pricingAnalysis['position']) {
                                    'very_competitive' => 'Very Competitive',
                                    'competitive' => 'Competitive',
                                    'strong_discount' => 'Strong Discount',
                                    'weak' => 'Weak - Consider Improving',
                                    default => 'Unknown'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} badge-lg">{{ $badgeText }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">Actions</h5>
                        <div class="d-grid gap-2 d-md-flex">
                            <form action="{{ route('vendor.deals.rescore', $deal) }}" method="POST" class="me-2">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-sync"></i> Re-analyze Deal
                                </button>
                            </form>
                            <a href="{{ route('vendor.deals.edit', $deal) }}" class="btn btn-outline-primary">
                                <i class="fa fa-edit"></i> Apply Suggestions
                            </a>
                            <a href="{{ route('deals.show', $deal->slug) }}" class="btn btn-outline-info" target="_blank">
                                <i class="fa fa-eye"></i> View Public Deal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


