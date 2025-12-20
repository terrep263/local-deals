@extends("admin.admin_app")

@section("content")
<div class="content bg-gray-lighter">
    <div class="row items-push">
        <div class="col-sm-7">
            <h1 class="page-heading">Add City</h1>
        </div>
        <div class="col-sm-5 text-right hidden-xs">
            <ol class="breadcrumb push-10-t">
                <li><a href="{{ URL::to('admin/dashboard') }}">{{ trans('words.dashboard') }}</a></li>
                <li><a class="link-effect" href="{{ route('admin.cities.index') }}">Cities</a></li>
                <li><a class="link-effect" href="#">Add</a></li>
            </ol>
        </div>
    </div>
</div>

<div class="content content-boxed">
    <div class="row">
        <div class="col-sm-12 col-lg-12">
            <div class="block">
                <div class="block-content block-content-narrow">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.cities.store') }}" class="form-horizontal padding-15" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="col-sm-3 control-label">City Name *</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Sort Order</label>
                            <div class="col-sm-9">
                                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">County</label>
                            <div class="col-sm-9">
                                <input type="text" name="county" value="{{ old('county','Lake County') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">State</label>
                            <div class="col-sm-9">
                                <input type="text" name="state" value="{{ old('state','FL') }}" class="form-control" maxlength="2">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Population</label>
                            <div class="col-sm-9">
                                <input type="number" name="population" value="{{ old('population') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">ZIP Codes <small>(comma separated)</small></label>
                            <div class="col-sm-9">
                                <input type="text" name="zip_codes" value="{{ old('zip_codes') }}" class="form-control" placeholder="34748, 34749">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Latitude</label>
                            <div class="col-sm-9">
                                <input type="text" name="latitude" value="{{ old('latitude') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Longitude</label>
                            <div class="col-sm-9">
                                <input type="text" name="longitude" value="{{ old('longitude') }}" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-9">
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Image</label>
                            <div class="col-sm-9">
                                <input type="file" name="image" class="filestyle">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-9">
                                <label class="css-input switch switch-sm switch-success">
                                    <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}><span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Featured</label>
                            <div class="col-sm-9">
                                <label class="css-input switch switch-sm switch-warning">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}><span></span>
                                </label>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ route('admin.cities.index') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

