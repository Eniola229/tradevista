<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contestant;
use App\Models\Vote;

class VoteController extends Controller
{

    public function showVotePage($link)
    {
        $contestant = Contestant::where('unique_link', $link)->firstOrFail();
        $allContestants = Contestant::withCount('votes')->orderByDesc('votes_count')->get();

        return view('vote', compact('contestant', 'allContestants'));
    }


    public function castVote(Request $request, $link)
    {
        $contestant = Contestant::where('unique_link', $link)->firstOrFail();
        $ipPlusAgent = $request->ip() . ' | ' . $request->header('User-Agent');

        $existingVote = Vote::where('contestant_id', $contestant->id)
            ->where('ip_address', $ipPlusAgent)
            ->first();

        if ($existingVote) {
            return back()->with('error', 'You have already voted for this contestant.');
        }

        Vote::create([
            'contestant_id' => $contestant->id,
            'ip_address' => $ipPlusAgent
        ]);

        return back()->with('status', 'Your vote has been cast successfully!');
    }
    
    public function results()
    {
        $allContestants = Contestant::withCount('votes')->orderByDesc('votes_count')->get();
        return view('vote-results', compact('allContestants'));
    }
}
