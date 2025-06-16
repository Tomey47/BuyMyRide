<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function show()
    {
        $countryCodes = json_decode(file_get_contents(storage_path('app/country_codes.json')), true);
        $cars = Auth::user()->cars()->with('images')->latest()->paginate(3);
        $user = Auth::user();

        if (request()->has('success_message')) {
            session()->flash('success', request('success_message'));
            return redirect()->route('profile');
        }

        return view('profile', compact('countryCodes', 'cars', 'user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $messages = [
            'username.required' => 'Lūdzu, ievadiet lietotājvārdu.',
            'username.string' => 'Lietotājvārds jābūt tekstam.',
            'username.min' => 'Lietotājvārdam jābūt vismaz :min rakstzīmes garam.',
            'username.max' => 'Lietotājvārdam nevar būt vairāk par :max rakstzīmēm.',
            'username.regex' => 'Lietotājvārdā atļauti tikai burti, cipari, pasvītras un domuzīmes.',
            'username.unique' => 'Šāds lietotājvārds jau eksistē.',

            'email.required' => 'Lūdzu, ievadiet e-pastu.',
            'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
            'email.unique' => 'Šāds e-pasts jau ir reģistrēts.',

            'country_code.string' => 'Valsts kodam jābūt tekstam.',

            'phone_number.max' => 'Tālruņa numuram nevar būt vairāk par :max rakstzīmēm.',
            'phone_number.string' => 'Tālruņa numuram jābūt tekstam.',
        ];

        $validated = $request->validate([
            'username' => 'required|string|min:3|max:20|regex:/^[A-Za-z0-9_-]+$/|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'country_code' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
        ], $messages);

        $user->update($validated);

        return redirect()->route('profile');
    }

    public function updateAvatar(Request $request)
    {
        $user = Auth::user();

        $messages = [
            'avatar.required' => 'Lūdzu, izvēlieties attēlu.',
            'avatar.image' => 'Fails jābūt attēla formātā.',
            'avatar.mimes' => 'Atbalstītie formāti: jpeg, png, jpg, gif, webp.',
            'avatar.max' => 'Attēla izmērs nedrīkst pārsniegt 4MB.',
        ];

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ], $messages);

        try {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            
            // Optimize the image
            $this->imageService->compressAndStore($request->file('avatar'), 'avatars/' . $user->id);

            $user->update(['avatar' => $avatarPath]);

            return redirect()->route('profile')->with('success', 'Profila foto veiksmīgi atjaunināts!');
        } catch (\Exception $e) {
            return redirect()->route('profile')->withErrors(['avatar' => 'Neizdevās saglabāt attēlu. Lūdzu, mēģiniet vēlreiz.']);
        }
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $messages = [
            'current_password.required' => 'Lūdzu, ievadiet pašreizējo paroli.',
            'current_password.current_password' => 'Pašreizējā parole nav pareiza.',
            'password.required' => 'Lūdzu, ievadiet jauno paroli.',
            'password.confirmed' => 'Paroles nesakrīt.',
            'password.min' => 'Parolei jābūt vismaz :min rakstzīmes garai.',
            'password.mixed' => 'Parolē jābūt gan lielajiem, gan mazajiem burtiem.',
            'password.letters' => 'Parolē jābūt vismaz vienam burtam.',
            'password.numbers' => 'Parolē jābūt vismaz vienam ciparam.',
            'password.symbols' => 'Parolē jābūt vismaz vienam simbolam.',
            'password.uncompromised' => 'Šī parole ir parādījusies datu noplūdēs. Lūdzu, izvēlieties citu paroli.',
        ];

        $request->validate([
            'current_password' => 'required|current_password',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ], $messages);

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        return redirect()->route('profile')->with('success', 'Parole veiksmīgi nomainīta!');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        Auth::logout();

        // Optionally, delete related data here

        $user->delete();

        return redirect('/')->with('success', 'Jūsu konts ir dzēsts.');
    }
}
