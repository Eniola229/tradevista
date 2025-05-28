<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Contestant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContestantController extends Controller
{
    public function quickRegister(Request $request)
    {
        $user = auth()->user();

        $existing = \App\Models\Contestant::where('email', $user->email)->first();
        if ($existing) {
            return redirect()->back()->with('info', 'You are already registered.');
        }

        $uniqueLink = \Illuminate\Support\Str::uuid();

        \App\Models\Contestant::create([
            'name' => $user->name,
            'email' => $user->email,
            'unique_link' => $uniqueLink,
        ]);

        return redirect()->back()->with('message', 'You are now registered for the giveaway! Share your link to get votes.');
    }

}
