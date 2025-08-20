<?php

namespace NoopStudios\FilamentEditProfile\Http\Controllers;

use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class EmailChangeController extends Controller
{
    /**
     * Verify and update the user's email address
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request)
    {
        Log::info('Email change verification request received', $request->all());
        $redirectRoute = config('filament-edit-profile.redirectUrl');

        // Validate that the URL signature is valid
        if (! $request->hasValidSignature()) {
            Log::error('Invalid signature for email verification');

            return Redirect::to($redirectRoute)
                ->with('error', __('filament-edit-profile::default.invalid_link'));
        }

        Log::info('Looking for user with ID: ' . ($request->id ?? 'null'));

        // Get user by ID
        $user = \App\Models\User::find($request->id);

        if (! $user) {
            Log::error('User not found with ID: ' . ($request->id ?? 'null'));

            return Redirect::to($redirectRoute)
                ->with('error', __('filament-edit-profile::default.unauthorized'));
        }

        Log::info('User found', ['user_id' => $user->id, 'email' => $request->email]);

        // Verify hash matches the email
        if (sha1($request->email) !== $request->hash) {
            Log::error('Invalid email verification hash', [
                'provided_hash' => $request->hash,
                'calculated_hash' => sha1($request->email),
                'email' => $request->email,
            ]);

            return Redirect::to($redirectRoute)
                ->with('error', __('filament-edit-profile::default.invalid_email_verification'));
        }

        // Update the user's email
        $user->email = $request->email;
        $user->save();

        Log::info('Email updated successfully', ['user_id' => $user->id, 'new_email' => $user->email]);

        // Send success notification
        Notification::make()
            ->success()
            ->title(__('filament-edit-profile::default.email_changed_successfully'))
            ->send();

        return Redirect::to($redirectRoute)
            ->with('success', __('filament-edit-profile::default.email_changed_successfully'));
    }
}
