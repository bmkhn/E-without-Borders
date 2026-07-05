<?php

namespace App\Http\Middleware;

use App\Models\Certificate;
use App\Models\Club;
use App\Models\Member;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClubScopeMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // SUPER ADMIN: national-president bypasses all club scoping.
        if ($user->hasRole('national-president')) {
            return $next($request);
        }

        // SCOPED ADMIN: club-president can only access data within their club_id.
        if (!$user->hasRole('club-president')) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        $clubId = $user->club_id;

        foreach ($request->route()->parameters() as $param) {
            if (!$param) {
                continue;
            }

            // Route-bound Club model
            if ($param instanceof Club) {
                if ((int) $param->id !== (int) $clubId) {
                    return response()->json(['message' => 'Forbidden.'], 403);
                }

                continue;
            }

            // Route-bound Member model
            if ($param instanceof Member) {
                if ((int) $param->club_id !== (int) $clubId) {
                    return response()->json(['message' => 'Forbidden.'], 403);
                }

                continue;
            }

            // Route-bound Certificate model
            if ($param instanceof Certificate) {
                // Prefer already-loaded relation if present; otherwise load it.
                $member = $param->relationLoaded('member') ? $param->member : $param->member;

                if (!$member || (int) $member->club_id !== (int) $clubId) {
                    return response()->json(['message' => 'Forbidden.'], 403);
                }

                continue;
            }
        }

        return $next($request);
    }
}
