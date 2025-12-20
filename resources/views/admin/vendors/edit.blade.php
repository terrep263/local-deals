@extends('admin.admin_app')

@section('content')
<div class="content bg-image overflow-hidden" style="background-image: url('{{ URL::asset('admin_assets/img/photos/bg.jpg') }}');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Edit Vendor</h1>
        <h2 class="h5 text-white-op animated zoomIn">{{ $vendor->business_name }}</h2>
    </div>
</div>

<div class="content content-narrow">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('generated_password'))
        <div class="alert alert-info">
            <strong>New Password:</strong> {{ session('generated_password') }}<br>
            Share once with the vendor; it has also been emailed.
        </div>
    @endif

    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Vendor Details</h3>
        </div>
        <div class="block-content">
            <form method="POST" action="{{ route('admin.vendors.update', $vendor->id) }}" class="form-horizontal">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" value="{{ $vendor->user->email ?? '' }}" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Business Phone<span class="text-danger">*</span></label>
                            <input type="text" name="business_phone" class="form-control" value="{{ old('business_phone', $vendor->business_phone) }}" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Business Name<span class="text-danger">*</span></label>
                    <input type="text" name="business_name" class="form-control" value="{{ old('business_name', $vendor->business_name) }}" maxlength="255" required>
                </div>

                <div class="form-group">
                    <label>Business Address<span class="text-danger">*</span></label>
                    <textarea name="business_address" class="form-control" rows="2" required>{{ old('business_address', $vendor->business_address) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="business_city" class="form-control" value="{{ old('business_city', $vendor->business_city) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="business_state" class="form-control" value="{{ old('business_state', $vendor->business_state) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>ZIP</label>
                            <input type="text" name="business_zip" class="form-control" value="{{ old('business_zip', $vendor->business_zip) }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Business Category<span class="text-danger">*</span></label>
                            <select name="business_category" class="form-control" required>
                                @foreach($categories as $value => $label)
                                    <option value="{{ $value }}" {{ old('business_category', $vendor->business_category) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="css-input switch switch-sm switch-primary" style="margin-top:30px;">
                                <input type="checkbox" name="is_founder" id="is_founder" value="1" {{ old('is_founder', $vendor->is_founder) ? 'checked' : '' }}><span></span> Is Founder
                            </label>
                            <p class="help-block">Changing founder status may impact their limits.</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Subscription Tier<span class="text-danger">*</span></label>
                            <select name="subscription_tier" id="subscription_tier" class="form-control" required>
                                <optgroup label="Founder Tiers" data-group="founder">
                                    @foreach($founderTiers as $value => $label)
                                        <option value="{{ $value }}" {{ old('subscription_tier', $vendor->subscription_tier) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </optgroup>
                                <optgroup label="Standard Tiers" data-group="standard">
                                    @foreach($standardTiers as $value => $label)
                                        <option value="{{ $value }}" {{ old('subscription_tier', $vendor->subscription_tier) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="css-input switch switch-sm switch-warning">
                        <input type="checkbox" name="reset_password" id="reset_password" value="1"><span></span> Generate new password & email the vendor
                    </label>
                    <p class="help-block">Use this to reset their password and send a fresh welcome email.</p>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Vendor</button>
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-default">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function() {
        const founderToggle = document.getElementById('is_founder');
        const tierSelect = document.getElementById('subscription_tier');

        function updateTierOptions() {
            const isFounder = founderToggle.checked;
            const options = tierSelect.querySelectorAll('optgroup');
            options.forEach(group => {
                const showGroup = (group.dataset.group === 'founder' && isFounder) ||
                                  (group.dataset.group === 'standard' && !isFounder);
                group.disabled = !showGroup;
                group.style.display = showGroup ? 'block' : 'none';
            });

            const visibleOptions = tierSelect.querySelectorAll('optgroup:not([disabled]) option');
            if (visibleOptions.length && !visibleOptions[0].selected) {
                visibleOptions[0].selected = true;
            }
        }

        founderToggle.addEventListener('change', updateTierOptions);
        updateTierOptions();
    })();
</script>
@endpush
@endsection

