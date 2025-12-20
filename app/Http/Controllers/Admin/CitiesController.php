<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CitiesController extends Controller
{
    public function index()
    {
        $cities = City::ordered()->get();
        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'county' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'zip_codes' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'population' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['status'] = $request->has('status') ? 1 : 0;

        if (!empty($validated['zip_codes'])) {
            $zips = array_map('trim', explode(',', $validated['zip_codes']));
            $validated['zip_codes'] = json_encode($zips);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('cities', 'public');
        }

        City::create($validated);

        return redirect()->route('admin.cities.index')->with('success', 'City created successfully.');
    }

    public function edit(City $city)
    {
        if ($city->zip_codes) {
            $city->zip_codes_string = is_array($city->zip_codes) ? implode(', ', $city->zip_codes) : $city->zip_codes;
        }
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'county' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'zip_codes' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'population' => 'nullable|integer',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['status'] = $request->has('status') ? 1 : 0;

        if (!empty($validated['zip_codes'])) {
            $zips = array_map('trim', explode(',', $validated['zip_codes']));
            $validated['zip_codes'] = json_encode($zips);
        }

        if ($request->hasFile('image')) {
            if ($city->image) {
                Storage::disk('public')->delete($city->image);
            }
            $validated['image'] = $request->file('image')->store('cities', 'public');
        }

        $city->update($validated);

        return redirect()->route('admin.cities.index')->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        if ($city->listings()->count() > 0) {
            return back()->with('error', 'Cannot delete city with existing listings. Reassign listings first.');
        }

        if ($city->image) {
            Storage::disk('public')->delete($city->image);
        }

        $city->delete();

        return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully.');
    }

    public function toggleFeatured(City $city)
    {
        $city->update(['is_featured' => !$city->is_featured]);
        return back()->with('success', 'Featured status updated.');
    }

    public function toggleStatus(City $city)
    {
        $city->update(['status' => !$city->status]);
        return back()->with('success', 'Status updated.');
    }
}

