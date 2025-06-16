<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Car;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function users(Request $request)
    {
        $search = $request->input('search');
        $users = User::query()
            ->when($search, function($query, $search) {
                $query->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone_number', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $search]);
        return view('admin.users', compact('users', 'search'));
    }

    public function makeAdmin(User $user)
    {
        $user->update(['is_admin' => true]);
        return redirect()->back()->with('success', 'Lietotājs tagad ir administrators.');
    }

    public function removeAdmin(User $user)
    {
        $user->update(['is_admin' => false]);
        return redirect()->back()->with('success', 'Administratora tiesības noņemtas.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Lietotājs veiksmīgi dzēsts.');
    }

    public function listings()
    {
        $cars = Car::with(['user', 'images'])
            ->where('is_approved', true)
            ->where('status', 'published')
            ->latest()
            ->paginate(10);
        return view('admin.listings', compact('cars'));
    }

    public function pendingListings()
    {
        $cars = Car::with(['user', 'images'])
            ->where('is_approved', false)
            ->where('status', 'published')
            ->latest()
            ->paginate(10);
        return view('admin.listings', compact('cars'));
    }
    public function reportedListings()
    {
        $cars = Car::with(['user', 'images'])
            ->where('is_reported', true)
            ->latest()
            ->paginate(10);
        return view('admin.listings', compact('cars'));
    }

    public function ignoreListing(Car $car)
    {
        $car->update(['is_reported' => false]);
        return redirect()->back()->with('success', 'Sūdzība ignorēta.');
    }
    public function approveListing(Car $car)
    {
        $car->update(['is_approved' => true]);
        return redirect()->back()->with('success', 'Sludinājums apstiprināts.');
    }

    public function destroyListing(Car $car)
    {
        $car->delete();
        return redirect()->back()->with('success', 'Sludinājums veiksmīgi dzēsts.');
    }

    public function markAllNotificationsAsRead()
    {
        auth()->user()->notifications()->delete();
        return redirect()->back();
    }
} 