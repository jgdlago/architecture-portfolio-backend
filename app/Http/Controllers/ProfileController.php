<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Response;
use App\Http\Requests\ProfileRequest;
use App\Http\Resources\ProfileResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileController extends Controller
{
    public function show(Profile $profile): JsonResource
    {
        return new ProfileResource($profile);
    }
    
    public function store(ProfileRequest $request): JsonResource
    {
        $profile = auth()->user()->profile()->create($request->validated());
        return new ProfileResource($profile, 201);
    }

    public function update(ProfileRequest $request, Profile $profile): JsonResource
    {
        $profile->update($request->validated());
        return new ProfileResource($profile);
    }

    public function destroy(Profile $profile): Response
    {
        $profile->delete();
        return response()->noContent(204);
    }
}
