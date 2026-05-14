<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::query()
            ->where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if ($user === null) {
            $user = User::query()->create([
                'google_id' => $googleUser->id,
                'name' => $googleUser->name ?? $googleUser->nickname ?? $googleUser->email,
                'email' => $googleUser->email,
                'avatar_url' => $googleUser->avatar,
            ]);
        }

        $user->forceFill([
            'google_id' => $googleUser->id,
            'name' => $googleUser->name ?? $user->name,
            'avatar_url' => $googleUser->avatar,
        ])->save();

        Auth::login($user, remember: true);

        request()->session()->regenerate();

        return redirect()->intended('/');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
