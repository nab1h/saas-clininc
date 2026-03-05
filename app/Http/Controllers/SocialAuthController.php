<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class SocialAuthController extends Controller
{
    public function redirectToProvider(string $provider)
    {
        $allowedProviders = ['google', 'facebook', 'apple'];
        if (!in_array($provider, $allowedProviders)) {
            abort(404, 'المزود غير مدعوم');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider)
    {
        $allowedProviders = ['google', 'facebook', 'apple'];
        if (!in_array($provider, $allowedProviders)) {
            abort(404, 'المزود غير مدعوم');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (InvalidStateException $e) {
            return redirect()->route('login')->with('error', 'حدث خطأ في المصادقة. يرجى المحاولة مرة أخرى.');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'حدث خطأ في الاتصال مع ' . $this->getProviderName($provider));
        }

        $user = $this->findOrCreateUser($socialUser, $provider);

        Auth::login($user, true);

        return redirect()->route('dashboard.index');
    }

    protected function findOrCreateUser($socialUser, string $provider): User
    {
        // Check if user exists by provider ID
        $existingUser = User::where('email', $socialUser->getEmail())->first();

        if ($existingUser) {
            // Link social account to existing user
            if ($this->providerNotLinked($existingUser, $provider)) {
                $this->linkSocialAccount($existingUser, $socialUser, $provider);
            }
            return $existingUser;
        }

        // Create new user
        return User::create([
            'name' => $socialUser->getName() ?? $socialUser->getNickname(),
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(uniqid()), // Random password for social users
        ]);
    }

    protected function providerNotLinked(User $user, string $provider): bool
    {
        // Check if provider is linked - you can add a social_accounts table for this
        return true;
    }

    protected function linkSocialAccount(User $user, $socialUser, string $provider): void
    {
        // Store social account info - you can add a social_accounts table
    }

    protected function getProviderName(string $provider): string
    {
        return match ($provider) {
            'google' => 'Google',
            'facebook' => 'Facebook',
            'apple' => 'Apple',
            default => $provider,
        };
    }
}
