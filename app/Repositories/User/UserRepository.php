<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserRepository implements UserRepositoryInterface
{
    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    public function updateProfilePhoto(User $user, UploadedFile $photo): string
    {
        $this->removeProfilePhotoFile($user);

        $path = $photo->store('profile-photos', 'public');

        return Storage::url($path);
    }

    public function removeProfilePhoto(User $user): void
    {
        $this->removeProfilePhotoFile($user);
        $user->update(['profile_photo_path' => null]);
    }

    private function removeProfilePhotoFile(User $user): void
    {
        if ($user->profile_photo_path) {
            $path = str_replace('/storage/', '', $user->profile_photo_path);
            Storage::disk('public')->delete($path);
        }
    }
}
