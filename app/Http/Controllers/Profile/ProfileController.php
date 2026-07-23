<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfilePasswordRequest;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('profile.show');
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();
        $removeAvatar = (bool) ($data['remove_avatar'] ?? false);
        unset($data['remove_avatar']);

        $oldAvatar = $user->avatar;
        $newAvatar = null;

        if ($request->hasFile('avatar')) {
            $newAvatar = $request->file('avatar')->store('profile-photos', 'public');
            $data['avatar'] = $newAvatar;
        } elseif ($removeAvatar) {
            $data['avatar'] = null;
        } else {
            unset($data['avatar']);
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        try {
            $user->save();
        } catch (Throwable $exception) {
            $this->deleteStoredAvatar($newAvatar);
            throw $exception;
        }

        if ($newAvatar || $removeAvatar) {
            $this->deleteStoredAvatar($oldAvatar);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(ProfilePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    private function deleteStoredAvatar(?string $avatar): void
    {
        if ($avatar && Str::startsWith($avatar, 'profile-photos/')) {
            Storage::disk('public')->delete($avatar);
        }
    }
}
