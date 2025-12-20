@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Deal Details</h1>
        <h2 class="h5 text-white-op animated zoomIn">{{ $deal->title }}</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    @if(Session::has('flash_message'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ Session::get('flash_message') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Deal Information</h3>
            <div class="block-options">
                <a href="{{ route('admin.deals.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">ID</th>
                            <td>{{ $deal->id }}</td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td><strong>{{ $deal->title }}</strong></td>
                        </tr>
                        <tr>
                            <th>Vendor</th>
                            <td>
                                <strong>{{ $deal->vendor->first_name }} {{ $deal->vendor->last_name }}</strong><br>
                                <small>{{ $deal->vendor->email }}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $deal->category->category_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $statusBadge = [
                                        'active' => ['class' => 'badge-success', 'text' => 'Active'],
                                        'pending_approval' => ['class' => 'badge-warning', 'text' => 'Pending'],
                                        'paused' => ['class' => 'badge-info', 'text' => 'Paused'],
                                        'sold_out' => ['class' => 'badge-primary', 'text' => 'Sold Out'],
                                        'expired' => ['class' => 'badge-secondary', 'text' => 'Expired'],
                                        'rejected' => ['class' => 'badge-danger', 'text' => 'Rejected'],
                                    ];
                                    $status = $statusBadge[$deal->status] ?? ['class' => 'badge-secondary', 'text' => ucfirst($deal->status)];
                                @endphp
                                <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Regular Price</th>
                            <td>${{ number_format($deal->regular_price, 2) }}</td>
                        </tr>
                        <tr>
                            <th>Deal Price</th>
                            <td><strong class="text-success">${{ number_format($deal->deal_price, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Discount</th>
                            <td>{{ $deal->discount_percentage }}% off (Save ${{ number_format($deal->savings_amount, 2) }})</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Inventory</th>
                            <td>{{ $deal->inventory_sold }} / {{ $deal->inventory_total }} sold ({{ $deal->inventory_remaining }} remaining)</td>
                        </tr>
                        <tr>
                            <th>Starts At</th>
                            <td>{{ $deal->starts_at ? $deal->starts_at->format('M d, Y g:i A') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Expires At</th>
                            <td>{{ $deal->expires_at ? $deal->expires_at->format('M d, Y g:i A') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Stripe Payment Link</th>
                            <td>
                                <a href="{{ $deal->stripe_payment_link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fa fa-external-link"></i> View Payment Link
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Location</th>
                            <td>
                                @if($deal->location_address){{ $deal->location_address }}<br>@endif
                                {{ $deal->location_city }}, FL {{ $deal->location_zip }}
                            </td>
                        </tr>
                        <tr>
                            <th>Views / Clicks</th>
                            <td>{{ $deal->view_count }} views / {{ $deal->click_count }} clicks</td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $deal->created_at->format('M d, Y g:i A') }}</td>
                        </tr>
                        @if($deal->admin_approved_at)
                        <tr>
                            <th>Approved By</th>
                            <td>
                                {{ $deal->approvedBy->first_name ?? 'N/A' }} {{ $deal->approvedBy->last_name ?? '' }}<br>
                                <small>{{ $deal->admin_approved_at->format('M d, Y g:i A') }}</small>
                            </td>
                        </tr>
                        @endif
                        @if($deal->admin_rejection_reason)
                        <tr>
                            <th>Rejection Reason</th>
                            <td class="text-danger">{{ $deal->admin_rejection_reason }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <h4>Description</h4>
                    <div class="card">
                        <div class="card-body">
                            {!! nl2br(e($deal->description)) !!}
                        </div>
                    </div>
                </div>
            </div>

            @if($deal->terms_conditions)
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Terms & Conditions</h4>
                    <div class="card">
                        <div class="card-body">
                            {!! nl2br(e($deal->terms_conditions)) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($deal->aiAnalysis || $deal->requires_admin_review)
            <div class="row mt-4">
                <div class="col-12">
                    <h4>AI Analysis</h4>
                    <div class="card">
                        <div class="card-body">
                            @if($deal->requires_admin_review)
                            <div class="alert alert-danger">
                                <strong>AI Flagged for Review</strong><br>
                                Reason: {{ $deal->admin_review_reason ?? 'Low quality score' }}
                            </div>
                            @endif
                            
                            @if($deal->aiAnalysis)
                                <div class="mb-3">
                                    <strong>Quality Score:</strong> 
                                    <span class="badge badge-{{ $deal->aiAnalysis->getScoreColor() }}">
                                        {{ $deal->aiAnalysis->score }}/100 - {{ $deal->aiAnalysis->getScoreLabel() }}
                                    </span>
                                    <small class="text-muted ml-2">Analyzed: {{ $deal->aiAnalysis->analyzed_at->format('M d, Y g:i A') }}</small>
                                </div>
                                
                                @if(count($deal->aiAnalysis->strengths) > 0)
                                <div class="mb-3">
                                    <strong>Strengths:</strong>
                                    <ul>
                                        @foreach($deal->aiAnalysis->strengths as $strength)
                                        <li>{{ $strength }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                
                                @if(count($deal->aiAnalysis->weaknesses) > 0)
                                <div class="mb-3">
                                    <strong>Weaknesses:</strong>
                                    <ul>
                                        @foreach($deal->aiAnalysis->weaknesses as $weakness)
                                        <li>{{ $weakness }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                
                                @if(count($deal->aiAnalysis->suggestions) > 0)
                                <div class="mb-3">
                                    <strong>Suggestions:</strong>
                                    <ol>
                                        @foreach($deal->aiAnalysis->suggestions as $suggestion)
                                        <li>{{ $suggestion }}</li>
                                        @endforeach
                                    </ol>
                                </div>
                                @endif
                                
                                @if($deal->aiAnalysis->competitive_analysis)
                                <div class="mb-3">
                                    <strong>Competitive Analysis:</strong>
                                    <p>{{ $deal->aiAnalysis->competitive_analysis }}</p>
                                </div>
                                @endif
                            @else
                                <p class="text-muted">No AI analysis available for this deal.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="row mt-4">
                <div class="col-12">
                    <h4>Actions</h4>
                    <div class="btn-group">
                        <a href="{{ route('deals.show', $deal->slug) }}" target="_blank" class="btn btn-info">
                            <i class="fa fa-eye"></i> View Public Page
                        </a>
                        @if($deal->status === 'pending_approval')
                            <form action="{{ route('admin.deals.approve', $deal) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-check"></i> Approve
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                <i class="fa fa-times"></i> Reject
                            </button>
                        @endif
                        @if($deal->status === 'active')
                            <form action="{{ route('admin.deals.pause', $deal) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning">
                                    <i class="fa fa-pause"></i> Pause
                                </button>
                            </form>
                        @endif
                        @if($deal->inventory_sold == 0)
                            <form action="{{ route('admin.deals.destroy', $deal) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this deal?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($deal->status === 'pending_approval')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.deals.reject', $deal) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Deal</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Please provide a reason for rejecting this deal:</p>
                    <textarea name="rejection_reason" class="form-control" rows="4" required minlength="10"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Deal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

