@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Vendor Details</h1>
        <h2 class="h5 text-white-op animated zoomIn">{{ $vendor->first_name }} {{ $vendor->last_name }}</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    <div class="row">
        <div class="col-md-4">
            <!-- Vendor Info Card -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Vendor Information</h3>
                </div>
                <div class="block-content">
                    <p><strong>Name:</strong> {{ $vendor->first_name }} {{ $vendor->last_name }}</p>
                    <p><strong>Email:</strong> {{ $vendor->email }}</p>
                    <p><strong>Phone:</strong> {{ $vendor->mobile ?? 'N/A' }}</p>
                    <p><strong>Join Date:</strong> {{ $vendor->created_at->format('M d, Y') }}</p>
                    <p><strong>Status:</strong> 
                        @if($vendor->account_status == 'suspended')
                            <span class="badge badge-warning">Suspended</span>
                        @elseif($vendor->account_status == 'banned')
                            <span class="badge badge-danger">Banned</span>
                        @else
                            <span class="badge badge-success">Active</span>
                        @endif
                    </p>
                    
                    @if($vendor->suspended_at)
                    <p><strong>Suspended:</strong> {{ $vendor->suspended_at->format('M d, Y') }}</p>
                    <p><strong>Reason:</strong> {{ $vendor->suspension_reason ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>

            <!-- Subscription Info -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Subscription</h3>
                </div>
                <div class="block-content">
                    @if($vendor->activeSubscription)
                        <p><strong>Tier:</strong> {{ ucfirst($vendor->activeSubscription->package_tier) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($vendor->activeSubscription->status) }}</p>
                        <p><strong>Period:</strong> {{ $vendor->activeSubscription->current_period_start->format('M d') }} - {{ $vendor->activeSubscription->current_period_end->format('M d, Y') }}</p>
                    @else
                        <p class="text-muted">No active subscription</p>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Quick Stats</h3>
                </div>
                <div class="block-content">
                    <p><strong>Total Deals:</strong> {{ $stats['total_deals'] }}</p>
                    <p><strong>Active Deals:</strong> {{ $stats['active_deals'] }}</p>
                    <p><strong>Total Revenue:</strong> ${{ number_format($stats['total_revenue'], 2) }}</p>
                    <p><strong>Total Sales:</strong> {{ $stats['total_sales'] }}</p>
                    <p><strong>Approval Rate:</strong> {{ $stats['approval_rate'] }}%</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Tabs -->
            <div class="block">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#overview">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#deals">Deals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#activity">Activity</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#admin">Admin</a>
                    </li>
                </ul>

                <div class="block-content tab-content">
                    <!-- Overview Tab -->
                    <div class="tab-pane active" id="overview">
                        <h4>Vendor Overview</h4>
                        <p>Complete vendor information and statistics.</p>
                    </div>

                    <!-- Deals Tab -->
                    <div class="tab-pane" id="deals">
                        <h4>Recent Deals</h4>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentDeals as $deal)
                                    <tr>
                                        <td>{{ Str::limit($deal->title, 40) }}</td>
                                        <td><span class="badge badge-{{ $deal->status == 'active' ? 'success' : 'secondary' }}">{{ ucfirst($deal->status) }}</span></td>
                                        <td>{{ $deal->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.deals.show', $deal->id) }}" class="btn btn-xs btn-primary">View</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Activity Tab -->
                    <div class="tab-pane" id="activity">
                        <h4>Recent Activity</h4>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentActivity as $activity)
                                    <tr>
                                        <td>{{ $activity->created_at->format('M d, Y H:i') }}</td>
                                        <td>{{ $activity->action }}</td>
                                        <td>{{ $activity->description }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Admin Tab -->
                    <div class="tab-pane" id="admin">
                        <h4>Admin Actions</h4>
                        
                        <!-- Admin Notes -->
                        <form method="POST" action="{{ route('admin.vendors.update-notes', $vendor->id) }}">
                            @csrf
                            <div class="form-group">
                                <label>Admin Notes (Private)</label>
                                <textarea name="notes" class="form-control" rows="5">{{ $vendor->admin_notes }}</textarea>
                                <small class="text-muted">These notes are only visible to admins</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Notes</button>
                        </form>

                        <hr>

                        <!-- Account Actions -->
                        <h5>Account Actions</h5>
                        
                        @if($vendor->account_status == 'active')
                        <form method="POST" action="{{ route('admin.vendors.suspend', $vendor->id) }}" class="mb-2">
                            @csrf
                            <div class="form-group">
                                <label>Suspension Reason</label>
                                <textarea name="reason" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-warning">Suspend Account</button>
                        </form>

                        <form method="POST" action="{{ route('admin.vendors.ban', $vendor->id) }}">
                            @csrf
                            <div class="form-group">
                                <label>Ban Reason</label>
                                <textarea name="reason" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Ban Account</button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.vendors.activate', $vendor->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-success">Activate Account</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


