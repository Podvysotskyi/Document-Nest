<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleCallbackController extends Controller
{
    public function __construct(private AuthRepository $authRepository) {}

    public function __invoke(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = $this->authRepository->findOrCreateFromGoogleUser($googleUser);

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->intended('/');
    }
}
