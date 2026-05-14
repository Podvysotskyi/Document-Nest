<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleCallbackController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function __invoke(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = $this->authService->findOrCreateFromGoogleUser($googleUser);

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
