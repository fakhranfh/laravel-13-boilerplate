<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface UserRepositoryInterface
{
    public function update(User $user, array $data): User;

    public function updateProfilePhoto(User $user, UploadedFile $photo): string;

    public function removeProfilePhoto(User $user): void;
}
