<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Password as PasswordFacade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function showRegister()
    {
        $countryCodes = json_decode(file_get_contents(storage_path('app/country_codes.json')), true);
        return view('auth.register', compact('countryCodes'));
    }
    public function showLogin()
    {
        return view('auth.login');
    }
    public function register(Request $request)
    {
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

            'country_code.required' => 'Lūdzu, izvēlieties valsts kodu.',
            'country_code.string' => 'Valsts kodam jābūt tekstam.',

            'phone_number.max' => 'Tālruņa numuram nevar būt vairāk par :max rakstzīmēm.',
            'phone_number.string' => 'Tālruņa numuram jābūt tekstam.',

            'password.required' => 'Lūdzu, ievadiet paroli.',
            'password.confirmed' => 'Paroles nesakrīt.',
            'password.min' => 'Parolei jābūt vismaz :min rakstzīmes garai.',
            'password.mixed' => 'Parolē jābūt gan lielajiem, gan mazajiem burtiem.',
            'password.letters' => 'Parolē jābūt vismaz vienam burtam.',
            'password.numbers' => 'Parolē jābūt vismaz vienam ciparam.',
            'password.symbols' => 'Parolē jābūt vismaz vienam simbolam.',
            'password.uncompromised' => 'Šī parole ir parādījusies datu noplūdēs. Lūdzu, izvēlieties citu paroli.',

            'g-recaptcha-response.required' => 'Lūdzu, apstipriniet, ka neesat robots.',
            'g-recaptcha-response.captcha' => 'Nepareiza captcha atbilde. Lūdzu, mēģiniet vēlreiz.',
        ];

        $validated = $request->validate([
            'username' => 'required|string|min:3|max:20|regex:/^[A-Za-z0-9_-]+$/|unique:users,username',
            'email' => 'required|email|unique:users',
            'country_code' => 'nullable|string',
            'phone_number' => 'nullable|string|max:20',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
            'g-recaptcha-response' => 'required|captcha',
        ], $messages);

        // Handle phone number concatenation safely
        if (!empty($validated['phone_number'])) {
            if (!empty($validated['country_code'])) {
                $validated['phone_number'] = $validated['country_code'] . ' ' . $validated['phone_number'];
            }
            // else: keep phone_number as is
        } else {
            $validated['phone_number'] = null;
        }
        
        unset($validated['country_code']);

        $user = User::create($validated);

        // Send email verification
        try {
            $user->sendEmailVerificationNotification();
            Log::info("Email verification sent for user: " . $user->email);
        } catch (Exception $e) {
            Log::error("Failed to send email verification for user: " . $user->email . " - " . $e->getMessage());
        }

        return redirect()->route('show.login')->with('success', 'Reģistrācija veiksmīga! Lūdzu, pārbaudiet savu e-pastu, lai apstiprinātu kontu.');
    }
    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'Lūdzu, ievadiet e-pastu.',
            'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',

            'password.required' => 'Lūdzu, ievadiet paroli.',
            'password.string' => 'Parolei jābūt tekstam.',
        ];

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ], $messages);

        if (Auth::attempt($validated)) {
            $user = Auth::user();
            
            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('show.login')
                    ->withErrors(['email' => 'Lūdzu, apstipriniet savu e-pastu, pirms ienākšanas sistēmā.']);
            }

            $request->session()->regenerate();

            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('home');
        }

        throw ValidationException::withMessages([
            'credentials' => 'Nepareizs e-pasts vai parole.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('show.login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $messages = [
            'email.required' => 'Lūdzu, ievadiet e-pastu.',
            'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
        ];

        $request->validate([
            'email' => 'required|email',
        ], $messages);

        $status = PasswordFacade::sendResetLink(
            $request->only('email')
        );

        return $status === PasswordFacade::RESET_LINK_SENT
            ? back()->with(['status' => 'Paroles atiestatīšanas saite nosūtīta uz jūsu e-pastu!'])
            : back()->withErrors(['email' => 'Nevarējām atrast lietotāju ar šādu e-pasta adresi.']);
    }

    public function showResetPassword(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $messages = [
            'token.required' => 'Nederīgs atiestatīšanas tokens.',
            'email.required' => 'Lūdzu, ievadiet e-pastu.',
            'email.email' => 'Lūdzu, ievadiet derīgu e-pasta adresi.',
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
            'token' => 'required',
            'email' => 'required|email',
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

        $status = PasswordFacade::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === PasswordFacade::PASSWORD_RESET
            ? redirect()->route('show.login')->with('success', 'Jūsu parole ir veiksmīgi atiestatīta! Tagad varat ienākt ar jauno paroli.')
            : back()->withErrors(['email' => 'Nevarējām atiestatīt paroli. Lūdzu, mēģiniet vēlreiz.']);
    }
}
