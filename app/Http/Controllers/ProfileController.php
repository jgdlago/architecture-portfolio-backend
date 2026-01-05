<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Response;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function show(Profile $profile): Response
    {
        return response($profile);
    }
    
    public function store(ProfileRequest $request): Response
    {
        $profile = auth()->user()->profile()->create($request->validated());
        return response($profile, 201);
    }

    public function update(ProfileRequest $request, Profile $profile): Response
    {
        $profile->update($request->validated());
        return response($profile);
    }

    public function destroy(Profile $profile): Response
    {
        $profile->delete();
        return response()->noContent(204);
    }
}
