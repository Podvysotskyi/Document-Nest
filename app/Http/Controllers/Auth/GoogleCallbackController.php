<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleCallbackController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

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

        $user->ensureDefaultCategories();

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->intended('/');
    }
}
