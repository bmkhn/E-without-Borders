<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MemberStoreRequest;
use App\Http\Requests\Admin\MemberUpdateRequest;
use App\Models\Certificate;
use App\Models\Club;
use App\Models\Member;
use App\Models\Position;
use App\Models\Region;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class MemberController extends Controller
{
    public function index(): View
    {
        $user = request()->user();

        $q = request()->string('q')->trim()->toString();
        $filterRegionId = request()->integer('region_id');
        $filterClubId = request()->integer('club_id');
        $filterStatus = request()->string('status')->trim()->toString();
        $filterPositionId = request()->integer('position_id');

        $isSuperAdmin = $user->hasRole('super-admin');
        $isNationalAdmin = $user->hasRole('national-admin');
        $isRegionalAdmin = $user->hasRole('regional-admin') && $user->region_id;
        $isClubAdmin = $user->hasRole('club-admin') && $user->club_id;

        $membersQuery = Member::query()
            ->with(['club.region', 'position']);

        if ($isClubAdmin) {
            $membersQuery->where('club_id', $user->club_id);
        }

        if ($isRegionalAdmin) {
            $membersQuery->whereHas('club', function ($q) use ($user) {
                $q->where('region_id', $user->region_id);
            });
        }

        if ($filterRegionId && ($isSuperAdmin || $isNationalAdmin)) {
            $membersQuery->whereHas('club', function ($q) use ($filterRegionId) {
                $q->where('region_id', $filterRegionId);
            });
        }

        if ($filterClubId) {
            $membersQuery->where('club_id', $filterClubId);
        }

        if ($filterStatus !== '' && in_array($filterStatus, ['active', 'inactive'])) {
            $membersQuery->where('status', $filterStatus);
        }

        if ($filterPositionId) {
            $membersQuery->where('position_id', $filterPositionId);
        }

        if ($q !== '') {
            $membersQuery->where(function ($query) use ($q) {
                $query->where('first_name', 'like', '%' . $q . '%')
                    ->orWhere('last_name', 'like', '%' . $q . '%')
                    ->orWhere('contact_number', 'like', '%' . $q . '%')
                    ->orWhere('slug', 'like', '%' . $q . '%');
            });
        }

        $totalCount = (clone $membersQuery)->count();

        // Unfiltered total (role-scoped but without ad-hoc filters)
        $unfilteredQuery = Member::query();
        if ($isClubAdmin) {
            $unfilteredQuery->where('club_id', $user->club_id);
        }
        if ($isRegionalAdmin) {
            $unfilteredQuery->whereHas('club', function ($q) use ($user) {
                $q->where('region_id', $user->region_id);
            });
        }
        $unfilteredTotal = (clone $unfilteredQuery)->count();

        $members = $membersQuery->orderBy('last_name')->orderBy('first_name')
            ->paginate(10)->withQueryString();

        $regions = ($isSuperAdmin || $isNationalAdmin) ? Region::query()->orderBy('name')->get() : collect();
        $clubsQuery = Club::query()->orderBy('name');

        if ($isRegionalAdmin) {
            $clubsQuery->where('region_id', $user->region_id);
        }

        if ($filterRegionId && ($isSuperAdmin || $isNationalAdmin)) {
            $clubsQuery->where('region_id', $filterRegionId);
        }
        if ($isClubAdmin) {
            $clubsQuery->where('id', $user->club_id);
        }
        $clubs = $clubsQuery->get();

        $positionsQuery = Position::query()->orderBy('name');

        if ($isClubAdmin || $isRegionalAdmin) {
            $positionsQuery->where('name', '!=', 'National President');
        }

        $positions = $positionsQuery->get();

        return view('admin.members.index', [
            'members' => $members,
            'q' => $q,
            'filterRegionId' => $filterRegionId,
            'filterClubId' => $filterClubId,
            'filterStatus' => $filterStatus,
            'filterPositionId' => $filterPositionId,
            'regions' => $regions,
            'clubs' => $clubs,
            'positions' => $positions,
            'totalCount' => $totalCount,
            'unfilteredTotal' => $unfilteredTotal,
            'isClubAdmin' => $isClubAdmin,
            'isSuperAdmin' => $isSuperAdmin,
            'isNationalAdmin' => $isNationalAdmin,
            'isRegionalAdmin' => $isRegionalAdmin,
        ]);
    }

    public function create(): View
    {
        $user = request()->user();

        if ($user->hasRole('club-admin') && $user->club_id) {
            $clubs = Club::query()->where('id', $user->club_id)->get();
        } elseif ($user->hasRole('regional-admin') && $user->region_id) {
            $clubs = Club::query()->where('region_id', $user->region_id)->get();
        } else {
            $clubs = Club::query()->orderBy('name')->get();
        }

        $positionsQuery = Position::query()->orderBy('name');
        if ($user->hasRole('club-admin') || $user->hasRole('regional-admin')) {
            $positionsQuery->where('name', '!=', 'National President');
        }

        return view('admin.members.create', [
            'clubs' => $clubs,
            'positions' => $positionsQuery->get(),
        ]);
    }

    public function store(MemberStoreRequest $request): RedirectResponse
    {
        $user = request()->user();

        $data = $request->safe()->except(['profile_picture', 'certificates']);

        if ($user->hasRole('club-admin') && $user->club_id) {
            $data['club_id'] = $user->club_id;
        }

        $member = new Member($data);
        $member->applySlugFromName();
        $member->status = $member->status ?? 'active';

        if ($request->hasFile('profile_picture')) {
            $member->profile_picture = $this->storeProfilePicture($request->file('profile_picture'));
        }

        $member->save();

        if ($request->has('certificates')) {
            $this->syncCertificates($member, $request);
        }

        activity()
            ->performedOn($member)
            ->causedBy(auth()->user())
            ->log('created');

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member created successfully.');
    }

    public function edit(Member $member): View
    {
        $user = request()->user();

        if ($user->hasRole('club-admin') && $user->club_id) {
            $clubs = Club::query()->where('id', $user->club_id)->get();
        } elseif ($user->hasRole('regional-admin') && $user->region_id) {
            $clubs = Club::query()->where('region_id', $user->region_id)->get();
        } else {
            $clubs = Club::query()->orderBy('name')->get();
        }

        $positionsQuery = Position::query()->orderBy('name');
        if ($user->hasRole('club-admin') || $user->hasRole('regional-admin')) {
            $positionsQuery->where('name', '!=', 'National President');
        }

        return view('admin.members.edit', [
            'member' => $member->load(['club', 'position', 'certificates']),
            'clubs' => $clubs,
            'positions' => $positionsQuery->get(),
        ]);
    }

    public function update(MemberUpdateRequest $request, Member $member): RedirectResponse
    {
        $user = request()->user();

        $data = $request->safe()->except(['profile_picture', 'remove_photo', 'certificates']);

        if ($user->hasRole('club-admin') && $user->club_id) {
            $data['club_id'] = $user->club_id;
        }

        $member->fill($data);
        $member->applySlugFromName();

        if ($request->hasFile('profile_picture')) {
            if ($member->profile_picture) {
                Storage::disk('public')->delete($member->profile_picture);
            }
            $member->profile_picture = $this->storeProfilePicture($request->file('profile_picture'));
        } elseif ($request->boolean('remove_photo') && $member->profile_picture) {
            Storage::disk('public')->delete($member->profile_picture);
            $member->profile_picture = null;
        }

        $member->save();

        if ($request->has('certificates') || $request->boolean('certificates_managed')) {
            $this->syncCertificates($member, $request);
        }

        activity()
            ->performedOn($member)
            ->causedBy(auth()->user())
            ->log('updated');

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member): RedirectResponse
    {
        if ($member->profile_picture) {
            Storage::disk('public')->delete($member->profile_picture);
        }

        foreach ($member->certificates as $cert) {
            if ($cert->file) {
                Storage::disk('public')->delete($cert->file);
            }
        }

        activity()
            ->performedOn($member)
            ->causedBy(auth()->user())
            ->log('deleted');

        $member->delete();

        return redirect()
            ->route('admin.members.index')
            ->with('success', 'Member deleted successfully.');
    }

    /**
     * Store a profile picture with aggressive optimization: 300×300, WebP at 60% quality.
     */
    private function storeProfilePicture(UploadedFile $file): string
    {
        return $this->optimizeAndStoreImage($file, 'profile-pictures', 300, 300, 60);
    }

    /**
     * Store and optimize an uploaded file.
     */
    private function optimizeAndStoreImage(UploadedFile $file, string $directory, int $maxWidth = 1200, int $maxHeight = 1200, int $quality = 70): string
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->decode($file);

        $image->scale(width: $maxWidth, height: $maxHeight);

        $filename = uniqid('img_') . '.webp';
        $path = $directory . '/' . $filename;

        $encoded = $image->encode(new WebpEncoder(quality: $quality));
        Storage::disk('public')->put($path, $encoded);

        return $path;
    }

    /**
     * Store a certificate file with optimization.
     */
    private function storeCertificateFile(UploadedFile $file): string
    {
        $mime = $file->getMimeType();

        if (str_starts_with($mime, 'image/')) {
            return $this->optimizeAndStoreImage($file, 'certificates', 1200, 1200, 70);
        }

        $extension = $file->getClientOriginalExtension() ?: 'pdf';
        $filename = uniqid('cert_') . '.' . $extension;

        return $file->storeAs('certificates', $filename, 'public');
    }

    private function syncCertificates(Member $member, MemberStoreRequest|MemberUpdateRequest $request): void
    {
        $certificates = $request->input('certificates', []);
        $existingIds = [];
        $memberCertIds = $member->certificates()->pluck('id')->all();

        foreach ($certificates as $index => $certData) {
            $certId = $certData['id'] ?? null;

            if (empty($certData['name']) && !$request->hasFile("certificates.{$index}.file")) {
                continue;
            }

            $data = [
                'name' => $certData['name'] ?? '',
                'issued_at' => $certData['issued_at'] ?? null,
            ];

            if ($certId && in_array($certId, $memberCertIds)) {
                $cert = Certificate::find($certId);
                if ($cert) {
                    if ($request->hasFile("certificates.{$index}.file")) {
                        if ($cert->file) {
                            Storage::disk('public')->delete($cert->file);
                        }
                        $data['file'] = $this->storeCertificateFile($request->file("certificates.{$index}.file"));
                    }
                    $cert->update($data);
                    $existingIds[] = $cert->id;
                }
            } else {
                $data['member_id'] = $member->id;
                if ($request->hasFile("certificates.{$index}.file")) {
                    $data['file'] = $this->storeCertificateFile($request->file("certificates.{$index}.file"));
                }
                $cert = Certificate::create($data);
                $existingIds[] = $cert->id;
            }
        }

        $member->certificates()
            ->whereNotIn('id', $existingIds)
            ->each(function ($cert) {
                if ($cert->file) {
                    Storage::disk('public')->delete($cert->file);
                }
                $cert->delete();
            });
    }
}
