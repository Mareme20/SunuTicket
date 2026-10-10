<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class PublicOrganizerController extends Controller
{
    public function show(User $user): View
    {
        $events = $user->events()
            ->where('is_published', true)
            ->orderBy('starts_at')
            ->get();

        return view('organizers.show', compact('user', 'events'));
    }
}
