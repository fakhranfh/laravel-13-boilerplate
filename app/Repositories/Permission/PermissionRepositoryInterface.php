<?php

namespace App\Repositories\Permission;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;

interface PermissionRepositoryInterface
{
    public function query(array $filters = []);

    public function get(array $filters = [], array $with = []): Collection;

    public function getAll(): Collection;

    public function find(int $id): ?Permission;

    public function create(array $data): Permission;

    public function update(int $id, array $data): Permission;

    public function delete(int $id): int;
}
