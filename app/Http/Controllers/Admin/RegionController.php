<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RegionStoreRequest;
use App\Http\Requests\Admin\RegionUpdateRequest;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:national-president');
    }

    public function index(): View
    {
        $q = request()->string('q')->trim()->toString();

        $regionsQuery = Region::query()->orderBy('name');

        if ($q !== '') {
            $regionsQuery->where('name', 'like', '%' . $q . '%');
        }

        $regions = $regionsQuery->paginate(10)->withQueryString();

        return view('admin.regions.index', [
            'regions' => $regions,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        return view('admin.regions.create');
    }

    public function store(RegionStoreRequest $request): RedirectResponse
    {
        Region::create($request->validated());

        return redirect()
            ->route('admin.regions.index')
            ->with('success', 'Region created successfully.');
    }

    public function edit(Region $region): View
    {
        return view('admin.regions.edit', [
            'region' => $region,
        ]);
    }

    public function update(RegionUpdateRequest $request, Region $region): RedirectResponse
    {
        $region->update($request->validated());

        return redirect()
            ->route('admin.regions.index')
            ->with('success', 'Region updated successfully.');
    }

    public function destroy(Region $region): RedirectResponse
    {
        if ($region->clubs()->exists()) {
            return redirect()
                ->route('admin.regions.index')
                ->with('error', 'Cannot delete region because it still contains clubs');
        }

        $region->delete();

        return redirect()
            ->route('admin.regions.index')
            ->with('success', 'Region deleted successfully.');
    }
}
