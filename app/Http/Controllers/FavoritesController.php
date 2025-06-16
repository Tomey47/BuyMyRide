<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Car;

class FavoritesController extends Controller
{
    public function toggle(Request $request, Car $car)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('show.login');
        }

        $existingFavorite = Favorite::where('user_id', $user->id)
            ->where('car_id', $car->id)
            ->first();

        if ($existingFavorite) {
            // Remove from favorites
            $existingFavorite->delete();
            $message = 'Sludinājums noņemts no favorītiem';
        } else {
            // Add to favorites
            Favorite::create([
                'user_id' => $user->id,
                'car_id' => $car->id
            ]);
            $message = 'Sludinājums pievienots favorītiem';
        }

        return redirect()->back()->with('success', $message);
    }

    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favoriteCars()
            ->with(['images', 'user'])
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        return view('favorites.index', compact('favorites'));
    }
}
