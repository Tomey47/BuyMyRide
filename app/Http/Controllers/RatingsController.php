<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;
use App\Models\User;

class RatingsController extends Controller
{
    public function store(Request $request, User $user)
    {
        // Prevent users from rating themselves
        if (Auth::id() === $user->id) {
            return redirect()->back()->withErrors(['rating' => 'Jūs nevarat novērtēt sevi.']);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Lūdzu, izvēlieties vērtējumu.',
            'rating.integer' => 'Vērtējumam jābūt skaitlim.',
            'rating.min' => 'Vērtējumam jābūt vismaz 1 zvaigzne.',
            'rating.max' => 'Vērtējumam nevar būt vairāk par 5 zvaigznēm.',
            'comment.max' => 'Komentārs nevar būt garāks par 1000 rakstzīmēm.',
        ]);

        // Check if user has already rated this user
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('rated_user_id', $user->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $message = 'Vērtējums veiksmīgi atjaunināts!';
        } else {
            // Create new rating
            Rating::create([
                'user_id' => Auth::id(),
                'rated_user_id' => $user->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $message = 'Vērtējums veiksmīgi pievienots!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function show(User $user)
    {
        $ratings = $user->ratingsReceived()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('ratings.show', compact('user', 'ratings'));
    }

    public function destroy(Rating $rating)
    {
        // Only the user who created the rating can delete it
        if (Auth::id() !== $rating->user_id) {
            abort(403);
        }

        $rating->delete();

        return redirect()->back()->with('success', 'Vērtējums veiksmīgi dzēsts!');
    }
}
