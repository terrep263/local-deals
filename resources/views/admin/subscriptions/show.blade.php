@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Subscription Details</h1>
        <h2 class="h5 text-white-op animated zoomIn">{{ $subscription->user->first_name }} {{ $subscription->user->last_name }}</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    @if(Session::has('flash_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('flash_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(Session::has('error_flash_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error_flash_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Subscription Information</h3>
            <div class="block-options">
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">User</th>
                            <td>{{ $subscription->user->first_name }} {{ $subscription->user->last_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $subscription->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Package Tier</th>
                            <td>
                                <span class="badge badge-primary">{{ ucfirst($subscription->package_tier) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @php
                                    $statusBadge = [
                                        'active' => ['class' => 'badge-success', 'text' => 'Active'],
                                        'canceled' => ['class' => 'badge-danger', 'text' => 'Canceled'],
                                        'past_due' => ['class' => 'badge-warning', 'text' => 'Past Due'],
                                        'trialing' => ['class' => 'badge-info', 'text' => 'Trialing'],
                                    ];
                                    $status = $statusBadge[$subscription->status] ?? ['class' => 'badge-secondary', 'text' => ucfirst($subscription->status)];
                                @endphp
                                <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Stripe Customer ID</th>
                            <td>
                                <code>{{ $subscription->stripe_customer_id }}</code>
                                <button class="btn btn-sm btn-outline-secondary ml-2" onclick="copyToClipboard('{{ $subscription->stripe_customer_id }}')">
                                    <i class="fa fa-copy"></i> Copy
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th>Stripe Subscription ID</th>
                            <td>
                                <code>{{ $subscription->stripe_subscription_id }}</code>
                                <button class="btn btn-sm btn-outline-secondary ml-2" onclick="copyToClipboard('{{ $subscription->stripe_subscription_id }}')">
                                    <i class="fa fa-copy"></i> Copy
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th>Stripe Price ID</th>
                            <td><code>{{ $subscription->stripe_price_id }}</code></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Current Period Start</th>
                            <td>{{ $subscription->current_period_start ? $subscription->current_period_start->format('M d, Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Current Period End</th>
                            <td>{{ $subscription->current_period_end ? $subscription->current_period_end->format('M d, Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Cancel at Period End</th>
                            <td>
                                @if($subscription->cancel_at_period_end)
                                    <span class="badge badge-warning">Yes</span>
                                @else
                                    <span class="badge badge-success">No</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Trial Ends At</th>
                            <td>{{ $subscription->trial_ends_at ? $subscription->trial_ends_at->format('M d, Y H:i') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $subscription->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $subscription->updated_at->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <h4>Actions</h4>
                <div class="btn-group">
                    <a href="https://dashboard.stripe.com/subscriptions/{{ $subscription->stripe_subscription_id }}" target="_blank" class="btn btn-secondary">
                        <i class="fa fa-external-link"></i> View in Stripe Dashboard
                    </a>
                    @if($subscription->status === 'active')
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#cancelModal">
                            <i class="fa fa-times"></i> Cancel Subscription
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.subscriptions.cancel', $subscription) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Cancel Subscription</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to cancel this subscription?</p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="immediately" id="immediately" value="1">
                            <label class="form-check-label" for="immediately">
                                Cancel immediately (instead of at period end)
                            </label>
                        </div>
                        <p class="text-danger mt-2">
                            <strong>Warning:</strong> This will immediately cancel the subscription and downgrade the user to Starter (free) tier.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Cancel Subscription</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Copied to clipboard!');
    });
}
</script>

@endsection


