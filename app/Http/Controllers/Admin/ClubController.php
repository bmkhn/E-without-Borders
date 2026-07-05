<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClubStoreRequest;
use App\Http\Requests\Admin\ClubUpdateRequest;
use App\Models\Club;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClubController extends Controller
{
    public function index(): View
    {
        $q = request()->string('q')->trim()->toString();

        $clubsQuery = Club::query()->withCount('members')->orderBy('name');

        if ($q !== '') {
            $clubsQuery->where('name', 'like', '%' . $q . '%');
        }

        $clubs = $clubsQuery->paginate(10)->withQueryString();

        return view('admin.clubs.index', [
            'clubs' => $clubs,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        return view('admin.clubs.create', [
            'regions' => Region::query()->orderBy('name')->get(),
        ]);
    }

    public function store(ClubStoreRequest $request): RedirectResponse
    {
        Club::create($request->validated());

        return redirect()
            ->route('admin.clubs.index')
            ->with('success', 'Club created successfully.');
    }

    public function edit(Club $club): View
    {
        return view('admin.clubs.edit', [
            'club' => $club,
            'regions' => Region::query()->orderBy('name')->get(),
        ]);
    }

    public function update(ClubUpdateRequest $request, Club $club): RedirectResponse
    {
        $club->update($request->validated());

        return redirect()
            ->route('admin.clubs.index')
            ->with('success', 'Club updated successfully.');
    }

    public function destroy(Club $club): RedirectResponse
    {
        if ($club->members()->exists()) {
            return redirect()
                ->route('admin.clubs.index')
                ->with('error', 'Cannot delete club because it still contains members');
        }

        $club->delete();

        return redirect()
            ->route('admin.clubs.index')
            ->with('success', 'Club deleted successfully.');
    }
}
