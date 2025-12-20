@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Deal Management</h1>
        <h2 class="h5 text-white-op animated zoomIn">Moderate and manage all deals</h2>
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

    @if(Session::has('error_flash_message'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ Session::get('error_flash_message') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'pending' ? 'active' : '' }}" href="{{ route('admin.deals.index', ['tab' => 'pending']) }}">
                Pending Approval
                @if($counts['pending'] > 0)
                <span class="badge badge-warning">{{ $counts['pending'] }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'ai_flagged' ? 'active' : '' }}" href="{{ route('admin.deals.index', ['tab' => 'ai_flagged']) }}">
                AI Flagged
                @if($counts['ai_flagged'] > 0)
                <span class="badge badge-danger">{{ $counts['ai_flagged'] }}</span>
                @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'active' ? 'active' : '' }}" href="{{ route('admin.deals.index', ['tab' => 'active']) }}">
                Active Deals
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'all' ? 'active' : '' }}" href="{{ route('admin.deals.index', ['tab' => 'all']) }}">
                All Deals
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'sold_out' ? 'active' : '' }}" href="{{ route('admin.deals.index', ['tab' => 'sold_out']) }}">
                Sold Out
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'expired' ? 'active' : '' }}" href="{{ route('admin.deals.index', ['tab' => 'expired']) }}">
                Expired
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $tab == 'rejected' ? 'active' : '' }}" href="{{ route('admin.deals.index', ['tab' => 'rejected']) }}">
                Rejected
            </a>
        </li>
    </ul>

    <!-- Filters -->
    <div class="block mb-3">
        <div class="block-content">
            <form method="GET" action="{{ route('admin.deals.index') }}" class="form-inline">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="text" name="vendor_search" class="form-control mr-2 mb-2" placeholder="Search vendor (name/email)" value="{{ request('vendor_search') }}">
                <select name="category_id" class="form-control mr-2 mb-2">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="date_from" class="form-control mr-2 mb-2" value="{{ request('date_from') }}" placeholder="From Date">
                <input type="date" name="date_to" class="form-control mr-2 mb-2" value="{{ request('date_to') }}" placeholder="To Date">
                <button type="submit" class="btn btn-primary mb-2">Filter</button>
                <a href="{{ route('admin.deals.index', ['tab' => $tab]) }}" class="btn btn-secondary mb-2">Clear</a>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="block mb-3" id="bulkActionsPanel" style="display: none;">
        <div class="block-content">
            <form id="bulkActionForm" method="POST">
                @csrf
                <div class="d-flex align-items-center">
                    <span class="mr-3"><strong id="selectedCount">0</strong> deal(s) selected</span>
                    <select name="action" id="bulkActionSelect" class="form-control mr-2" style="width: auto;" required>
                        <option value="">Choose Action...</option>
                        <option value="approve">Approve</option>
                        <option value="reject">Reject</option>
                        <option value="pause">Pause</option>
                        <option value="feature">Feature</option>
                        <option value="unfeature">Unfeature</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <button type="button" class="btn btn-secondary ml-2" onclick="clearSelection()">Clear Selection</button>
                </div>
                <div id="bulkActionMessage" class="mt-2" style="display: none;">
                    <textarea name="message" class="form-control" rows="3" placeholder="Reason (required for reject)"></textarea>
                </div>
            </form>
        </div>
    </div>

    <!-- Deals Table -->
    <div class="block">
        <div class="block-content">
            <table class="table table-bordered table-striped table-vcenter">
                <thead>
                    <tr>
                        <th width="50">
                            <input type="checkbox" id="selectAll" onchange="toggleAll(this)">
                        </th>
                        <th>ID</th>
                        <th>Vendor</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Inventory</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deals as $deal)
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
                        <tr>
                            <td>
                                <input type="checkbox" class="deal-checkbox" value="{{ $deal->id }}" onchange="updateBulkActions()">
                            </td>
                            <td>{{ $deal->id }}</td>
                            <td>
                                <strong>{{ $deal->vendor->first_name }} {{ $deal->vendor->last_name }}</strong><br>
                                <small>{{ $deal->vendor->email }}</small>
                            </td>
                            <td>
                                <strong>{{ Str::limit($deal->title, 40) }}</strong>
                            </td>
                            <td>{{ $deal->category->category_name ?? 'N/A' }}</td>
                            <td>
                                <span style="text-decoration: line-through; color: #999;">${{ number_format($deal->regular_price, 2) }}</span><br>
                                <strong class="text-success">${{ number_format($deal->deal_price, 2) }}</strong>
                            </td>
                            <td>
                                {{ $deal->inventory_sold }} / {{ $deal->inventory_total }}<br>
                                <small class="text-muted">{{ $deal->inventory_remaining }} remaining</small>
                            </td>
                            <td>
                                <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                                @if($deal->requires_admin_review)
                                <br><small class="badge badge-danger">AI Flagged</small>
                                @endif
                                @if($deal->ai_quality_score)
                                <br><small class="text-muted">AI Score: {{ $deal->ai_quality_score }}/100</small>
                                @endif
                            </td>
                            <td>{{ $deal->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.deals.show', $deal) }}" class="btn btn-info" title="View">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if($deal->status === 'pending_approval')
                                        <form action="{{ route('admin.deals.approve', $deal) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success" title="Approve">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal{{ $deal->id }}" title="Reject">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                    @if($deal->status === 'active')
                                        <form action="{{ route('admin.deals.pause', $deal) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-warning" title="Pause">
                                                <i class="fa fa-pause"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($deal->inventory_sold == 0)
                                        <form action="{{ route('admin.deals.destroy', $deal) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this deal?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Delete">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Reject Modal -->
                        @if($deal->status === 'pending_approval')
                        <div class="modal fade" id="rejectModal{{ $deal->id }}" tabindex="-1">
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
                                            <textarea name="rejection_reason" class="form-control" rows="4" required minlength="10" placeholder="e.g. Pricing is too low, description needs more detail, etc."></textarea>
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
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No deals found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $deals->links() }}
            </div>
        </div>
    </div>
</div>

<script>
let selectedDeals = [];

function toggleAll(checkbox) {
    const checkboxes = document.querySelectorAll('.deal-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        updateBulkActions();
    });
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.deal-checkbox:checked');
    selectedDeals = Array.from(checkboxes).map(cb => cb.value);
    
    const panel = document.getElementById('bulkActionsPanel');
    const count = document.getElementById('selectedCount');
    const form = document.getElementById('bulkActionForm');
    const actionSelect = form.querySelector('select[name="action"]');
    const messageDiv = document.getElementById('bulkActionMessage');
    
    if (selectedDeals.length > 0) {
        panel.style.display = 'block';
        count.textContent = selectedDeals.length;
        
        // Add hidden inputs for selected deals
        form.querySelectorAll('input[name="deal_ids[]"]').forEach(input => input.remove());
        selectedDeals.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'deal_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        // Show message field for reject action
        actionSelect.onchange = function() {
            if (this.value === 'reject') {
                messageDiv.style.display = 'block';
                messageDiv.querySelector('textarea').required = true;
            } else {
                messageDiv.style.display = 'none';
                messageDiv.querySelector('textarea').required = false;
            }
        };
        
        // Set form action based on selected action
        form.onsubmit = function(e) {
            e.preventDefault();
            const action = actionSelect.value;
            if (!action) {
                alert('Please select an action');
                return;
            }
            
            if (action === 'reject' && !messageDiv.querySelector('textarea').value.trim()) {
                alert('Please provide a rejection reason');
                return;
            }
            
            form.action = '{{ url("admin/deals/bulk") }}/' + action;
            form.submit();
        };
    } else {
        panel.style.display = 'none';
    }
}

function clearSelection() {
    document.querySelectorAll('.deal-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActions();
}
</script>

@endsection

