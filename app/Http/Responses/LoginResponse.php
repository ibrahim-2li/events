<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as Responsable;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginResponse implements Responsable
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        $user = Auth::user();

        // Redirect based on user role
        if ($user && $user->isUser()) {
            return redirect()->intended('/dashboard/my-attendance');
        }

        // Default redirect for admin and scanner users
        return redirect()->intended(Filament::getUrl());
    }
}
