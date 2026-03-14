<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show(Profile $profile): JsonResource
    {
        $this->authorizeOwner($profile->user_id);

        return new ProfileResource($profile);
    }

    public function store(ProfileRequest $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $request->validated(),
        );

        return (new ProfileResource($profile))->response()->setStatusCode(201);
    }

    public function update(ProfileRequest $request, Profile $profile): JsonResource
    {
        $this->authorizeOwner($profile->user_id);

        $profile->update($request->validated());

        return new ProfileResource($profile);
    }

    public function destroy(Profile $profile): Response
    {
        $this->authorizeOwner($profile->user_id);

        $profile->delete();

        return response()->noContent(204);
    }

    private function authorizeOwner(int $ownerId): void
    {
        abort_unless(Auth::id() === $ownerId, 403);
    }
}
