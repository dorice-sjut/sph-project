<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        $stats = match($user->role) {
            'farmer' => [
                'products' => $user->products()->count(),
                'sales' => $user->sales()->count(),
                'orders' => $user->sales()->count(),
                'rating' => 4.5,
            ],
            'buyer' => [
                'orders' => $user->orders()->count(),
                'spent' => $user->orders()->sum('total_price'),
            ],
            default => [],
        };

        return view('profile.show', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                $oldPath = str_replace('/storage/', '', $user->avatar);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = Storage::url($path);
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function settings()
    {
        return view('profile.settings');
    }
}
