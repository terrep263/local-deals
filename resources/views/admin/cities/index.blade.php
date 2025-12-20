@extends("admin.admin_app")

@section("content")
@php use Illuminate\Support\Str; @endphp

<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">Cities / Locations</h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li><a href="{{ URL::to('admin/dashboard') }}">{{ trans('words.dashboard') }}</a></li>
                <li><a class="link-effect" href="#">Cities</a></li>
            </ol>
        </div>
    </div>
</div>

<div class="content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="block">
        <div class="block-header" style="padding-bottom:0;">
            <a class="pull-right btn btn-primary push-5-r push-10" href="{{ route('admin.cities.create') }}"><i class="fa fa-plus"></i> Add City</a>
        </div>
        <div class="block-content">
            <div class="row text-center mb-20">
                <div class="col-sm-3">
                    <div class="h2 font-w600">{{ $cities->count() }}</div>
                    <div class="text-muted">Total Cities</div>
                </div>
                <div class="col-sm-3">
                    <div class="h2 font-w600">{{ $cities->where('status',1)->count() }}</div>
                    <div class="text-muted">Active</div>
                </div>
                <div class="col-sm-3">
                    <div class="h2 font-w600">{{ $cities->where('is_featured',1)->count() }}</div>
                    <div class="text-muted">Featured</div>
                </div>
                <div class="col-sm-3">
                    <div class="h2 font-w600">{{ $cities->sum('population') ? number_format($cities->sum('population')) : '—' }}</div>
                    <div class="text-muted">Population</div>
                </div>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="60">Order</th>
                        <th>City</th>
                        <th>County / State</th>
                        <th>Population</th>
                        <th width="90">Status</th>
                        <th width="100">Featured</th>
                        <th width="90">Deals</th>
                        <th width="150" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cities as $city)
                    <tr>
                        <td><span class="badge badge-default">{{ $city->sort_order }}</span></td>
                        <td>
                            <strong>{{ $city->name }}</strong>
                            @if($city->description)
                                <div class="text-muted small">{{ Str::limit($city->description, 60) }}</div>
                            @endif
                        </td>
                        <td>
                            {{ $city->county ?? 'Lake County' }}, {{ $city->state ?? 'FL' }}
                            @if($city->zip_codes)
                                <div class="text-muted small">ZIP: {{ is_array($city->zip_codes) ? implode(', ', $city->zip_codes) : $city->zip_codes }}</div>
                            @endif
                        </td>
                        <td>{{ $city->population ? number_format($city->population) : '—' }}</td>
                        <td>
                            <form action="{{ route('admin.cities.toggle-status', $city) }}" method="POST">
                                @csrf
                                <button class="btn btn-xs {{ $city->status ? 'btn-success' : 'btn-default' }}" type="submit">
                                    {{ $city->status ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('admin.cities.toggle-featured', $city) }}" method="POST">
                                @csrf
                                <button class="btn btn-xs {{ $city->is_featured ? 'btn-warning' : 'btn-default' }}" type="submit">
                                    {{ $city->is_featured ? '⭐ Featured' : 'No' }}
                                </button>
                            </form>
                        </td>
                        <td><span class="badge badge-info">{{ $city->deals_count ?? 0 }}</span></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-xs btn-success" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete {{ $city->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-times"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No cities found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

