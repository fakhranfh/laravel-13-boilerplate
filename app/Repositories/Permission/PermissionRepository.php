<?php

namespace App\Repositories\Permission;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionRepositoryInterface
{
    public function query(array $filters = [])
    {
        $query = Permission::query();

        foreach ($filters as $key => $value) {
            if (is_null($value) || $value === '') {
                continue;
            }

            $query->where($key, $value);
        }

        return $query;
    }

    public function get(array $filters = [], array $with = []): Collection
    {
        return $this->query($filters)->with($with)->get();
    }

    public function getAll(): Collection
    {
        return Permission::all();
    }

    public function find(int $id): ?Permission
    {
        return Permission::find($id);
    }

    public function create(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
        ]);
    }

    public function update(int $id, array $data): Permission
    {
        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $data['name'],
        ]);

        return $permission;
    }

    public function delete(int $id): int
    {
        return Permission::destroy($id);
    }
}
