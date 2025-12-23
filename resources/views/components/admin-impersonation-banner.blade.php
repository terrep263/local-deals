@if(session('impersonating'))
<div class="alert alert-warning mb-0" style="border-radius: 0; position: sticky; top: 0; z-index: 9999; margin-bottom: 0 !important; border: none; border-bottom: 3px solid #f0ad4e;">
    <div class="container-fluid">
        <div class="row" style="align-items: center;">
            <div class="col-xs-12 col-sm-8">
                <i class="fa fa-exclamation-triangle"></i>
                <strong>Admin Preview Mode:</strong> 
                Viewing as <strong>{{ session('impersonated_vendor_name') }}</strong>
                <small class="text-muted">({{ session('impersonated_vendor_email') }})</small>
            </div>
            <div class="col-xs-12 col-sm-4 text-right" style="margin-top: 5px;">
                <form method="POST" action="{{ route('admin.impersonate.stop') }}" style="display: inline-block; margin: 0;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-danger" style="font-weight: bold;">
                        <i class="fa fa-sign-out"></i> Exit to Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
