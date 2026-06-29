<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function edit(): View
    {
        return view('edit-profile');
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $this->userService->updateProfilePhoto($user, $request->file('profile_photo'));
        }

        if (isset($data['remove_photo']) && $data['remove_photo']) {
            $this->userService->removeProfilePhoto($user);
        }

        unset($data['profile_photo'], $data['remove_photo']);

        $this->userService->updateProfile($user, $data);

        return redirect()->route('edit-profile')->with('success', 'Profile updated successfully.');
    }
}
