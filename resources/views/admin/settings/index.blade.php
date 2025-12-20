@extends("admin.admin_app")

@section("content")

<!-- Page Header -->
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Platform Settings</h1>
        <h2 class="h5 text-white-op animated zoomIn">Configure platform-wide settings</h2>
    </div>
</div>
<!-- END Page Header -->

<div class="content content-narrow">
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.platform-settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- General Settings -->
        <div class="block">
            <div class="block-header">
                <h3 class="block-title">General Settings</h3>
            </div>
            <div class="block-content">
                <div class="form-group">
                    <label>Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="{{ $groups['general']['site_name']->value ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Site Tagline</label>
                    <input type="text" name="site_tagline" class="form-control" value="{{ $groups['general']['site_tagline']->value ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Contact Email</label>
                    <input type="email" name="contact_email" class="form-control" value="{{ $groups['general']['contact_email']->value ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Support Email</label>
                    <input type="email" name="support_email" class="form-control" value="{{ $groups['general']['support_email']->value ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Deal Settings -->
        <div class="block">
            <div class="block-header">
                <h3 class="block-title">Deal Settings</h3>
            </div>
            <div class="block-content">
                <div class="form-group">
                    <label>Max Deals Per Vendor (Free Tier)</label>
                    <input type="number" name="max_deals_free" class="form-control" value="{{ $groups['deal']['max_deals_free']->value ?? 1 }}">
                </div>
                <div class="form-group">
                    <label>Max Inventory Per Deal (Free Tier)</label>
                    <input type="number" name="max_inventory_free" class="form-control" value="{{ $groups['deal']['max_inventory_free']->value ?? 100 }}">
                </div>
                <div class="form-group">
                    <label>Default Deal Duration (Days)</label>
                    <input type="number" name="default_deal_duration" class="form-control" value="{{ $groups['deal']['default_deal_duration']->value ?? 30 }}">
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="manual_approval_required" value="1" {{ ($groups['deal']['manual_approval_required']->value ?? false) ? 'checked' : '' }}>
                            Manual Approval Required (Free Tier)
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="auto_close_sold_out" value="1" {{ ($groups['deal']['auto_close_sold_out']->value ?? true) ? 'checked' : '' }}>
                            Auto-Close Sold Out Deals
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="block">
            <div class="block-header">
                <h3 class="block-title">Email Settings</h3>
            </div>
            <div class="block-content">
                <div class="form-group">
                    <label>From Name</label>
                    <input type="text" name="email_from_name" class="form-control" value="{{ $groups['email']['email_from_name']->value ?? 'Lake County Local Deals' }}">
                </div>
                <div class="form-group">
                    <label>From Email</label>
                    <input type="email" name="email_from_email" class="form-control" value="{{ $groups['email']['email_from_email']->value ?? '' }}">
                </div>
                <div class="form-group">
                    <label>Email Signature</label>
                    <textarea name="email_signature" class="form-control" rows="3">{{ $groups['email']['email_signature']->value ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label>Test Email Address</label>
                    <div class="input-group">
                        <input type="email" name="test_email" class="form-control" placeholder="Enter email to test">
                        <div class="input-group-append">
                            <button type="submit" formaction="{{ route('admin.platform-settings.test-email') }}" class="btn btn-primary">Send Test</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="block">
            <div class="block-header">
                <h3 class="block-title">SEO Settings</h3>
            </div>
            <div class="block-content">
                <div class="form-group">
                    <label>Meta Title Template</label>
                    <input type="text" name="meta_title_template" class="form-control" value="{{ $groups['seo']['meta_title_template']->value ?? '{title} - Lake County Local Deals' }}">
                    <small class="text-muted">Use {title}, {category}, {vendor} as variables</small>
                </div>
                <div class="form-group">
                    <label>Meta Description Template</label>
                    <textarea name="meta_description_template" class="form-control" rows="2">{{ $groups['seo']['meta_description_template']->value ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <label>Google Analytics ID</label>
                    <input type="text" name="google_analytics_id" class="form-control" value="{{ $groups['seo']['google_analytics_id']->value ?? '' }}" placeholder="UA-XXXXXXXXX-X">
                </div>
                <div class="form-group">
                    <label>Facebook Pixel ID</label>
                    <input type="text" name="facebook_pixel_id" class="form-control" value="{{ $groups['seo']['facebook_pixel_id']->value ?? '' }}">
                </div>
            </div>
        </div>

        <!-- Maintenance Mode -->
        <div class="block">
            <div class="block-header">
                <h3 class="block-title">Maintenance Mode</h3>
            </div>
            <div class="block-content">
                <div class="form-group">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="maintenance_mode" value="1" {{ ($groups['maintenance']['maintenance_mode']->value ?? false) ? 'checked' : '' }}>
                            Enable Maintenance Mode
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Maintenance Message</label>
                    <textarea name="maintenance_message" class="form-control" rows="3">{{ $groups['maintenance']['maintenance_message']->value ?? 'We are currently performing maintenance. Please check back soon.' }}</textarea>
                </div>
                <div class="form-group">
                    <label>Allowed IPs (Admin Access During Maintenance)</label>
                    <textarea name="maintenance_allowed_ips" class="form-control" rows="2" placeholder="One IP per line">{{ $groups['maintenance']['maintenance_allowed_ips']->value ?? '' }}</textarea>
                    <small class="text-muted">Your IP: {{ request()->ip() }}</small>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="block-content">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </div>
    </form>
</div>

@endsection


