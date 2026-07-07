<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClubStoreRequest;
use App\Http\Requests\Admin\ClubUpdateRequest;
use App\Models\Club;
use App\Models\Region;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ClubController extends Controller
{
    public function index(): View
    {
        $q = request()->string('q')->trim()->toString();

        $clubsQuery = Club::query()
            ->with('clubPresident')
            ->withCount('members')
            ->orderBy('name');

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
        $club = Club::create($request->safe()->only(['region_id', 'name']));

        // Create the club president user
        $user = User::create([
            'name' => $request->cp_name,
            'email' => $request->cp_email,
            'password' => Hash::make($request->cp_password),
            'club_id' => $club->id,
        ]);

        $user->syncRoles(['club-president']);

        return redirect()
            ->route('admin.clubs.index')
            ->with('success', 'Club created successfully with club president account.');
    }

    public function edit(Club $club): View
    {
        $club->load('clubPresident');

        return view('admin.clubs.edit', [
            'club' => $club,
            'regions' => Region::query()->orderBy('name')->get(),
        ]);
    }

    public function update(ClubUpdateRequest $request, Club $club): RedirectResponse
    {
        $club->update($request->safe()->only(['region_id', 'name']));

        // Update club president account if provided
        if ($request->filled('cp_name') || $request->filled('cp_email') || $request->filled('cp_password')) {
            $cpUser = $club->clubPresident;

            if ($cpUser) {
                $data = [];
                if ($request->filled('cp_name')) {
                    $data['name'] = $request->cp_name;
                }
                if ($request->filled('cp_email')) {
                    $data['email'] = $request->cp_email;
                }
                if ($request->filled('cp_password')) {
                    $data['password'] = Hash::make($request->cp_password);
                }
                $cpUser->update($data);
            } else {
                // No existing club president — create one
                $cpUser = User::create([
                    'name' => $request->cp_name,
                    'email' => $request->cp_email,
                    'password' => Hash::make($request->cp_password),
                    'club_id' => $club->id,
                ]);
                $cpUser->syncRoles(['club-president']);
            }
        }

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

        // Remove the club president account as it's tied to the club
        $club->load('clubPresident');
        if ($club->clubPresident) {
            $club->clubPresident->delete();
        }

        $club->delete();

        return redirect()
            ->route('admin.clubs.index')
            ->with('success', 'Club deleted successfully.');
    }
}
