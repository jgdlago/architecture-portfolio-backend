<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactMessageController extends Controller
{
    public function index(): Response
    {
        $messages = ContactMessage::query()
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response($messages);
    }

    public function show(ContactMessage $contactMessage): Response
    {
        return response($contactMessage);
    }

    public function update(Request $request, ContactMessage $contactMessage): Response
    {
        $validated = $request->validate([
            'is_read' => ['sometimes', 'boolean'],
            'replied_at' => ['nullable', 'date'],
        ]);

        if (array_key_exists('is_read', $validated) && $validated['is_read']) {
            $validated['read_at'] = now();
        }

        $contactMessage->update($validated);

        return response($contactMessage);
    }

    public function destroy(ContactMessage $contactMessage): Response
    {
        $contactMessage->delete();

        return response()->noContent();
    }
}
