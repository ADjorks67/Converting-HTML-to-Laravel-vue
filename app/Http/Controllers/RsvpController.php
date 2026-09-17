<?php

namespace App\Http\Controllers;

use App\Models\Rsvp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RsvpController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'attending' => ['required', 'in:yes,no,maybe'],
            'guests' => ['nullable', 'integer', 'min:0', 'max:20'],
            'dietary' => ['nullable', 'string', 'max:1000'],
        ]);

        Rsvp::create($validated);

        return back()->with('success', 'Your RSVP has been received.');
    }
}