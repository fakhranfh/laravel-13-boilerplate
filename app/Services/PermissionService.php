<?php

namespace App\Services;

use App\Repositories\Permission\PermissionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;

class PermissionService
{
    public function __construct(protected PermissionRepositoryInterface $permissionRepository) {}

    public function get(array $filters = [], array $with = []): Collection
    {
        return $this->permissionRepository->get($filters, $with);
    }

    public function getAll(): Collection
    {
        return $this->permissionRepository->getAll();
    }

    public function find(int $id): ?Permission
    {
        return $this->permissionRepository->find($id);
    }

    public function create(array $data): Permission
    {
        return $this->permissionRepository->create($data);
    }

    public function update(int $id, array $data): Permission
    {
        return $this->permissionRepository->update($id, $data);
    }

    public function delete(int $id): int
    {
        return $this->permissionRepository->delete($id);
    }
}
