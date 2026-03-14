<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactMessageController extends Controller
{
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:4000'],
            'project_interest' => ['nullable', 'string', 'max:255'],
        ]);

        ContactMessage::create([
            ...$validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response([
            'message' => 'Mensagem enviada com sucesso.',
        ], 201);
    }
}
