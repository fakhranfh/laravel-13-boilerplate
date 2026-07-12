<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\PermissionService;
use App\Services\RoleService;

class RoleController extends Controller
{
    public function __construct(
        protected RoleService $roleService,
        protected PermissionService $permissionService,
    ) {}

    private function foreignData(): array
    {
        return [
            'permissions' => $this->permissionService->getAll(),
        ];
    }

    public function index()
    {
        return view('app.role.index', [
            'roles' => $this->roleService->get([], ['permissions']),
        ]);
    }

    public function create()
    {
        return view('app.role.create', $this->foreignData());
    }

    public function store(StoreRoleRequest $request)
    {
        $role = $this->roleService->create($request->validated());

        return redirect()->route('roles.index')
            ->with('success', __('Role created successfully.'));
    }

    public function edit(int $id)
    {
        $role = $this->roleService->find($id);

        return view('app.role.edit', [
            'role' => $role,
        ] + $this->foreignData());
    }

    public function update(UpdateRoleRequest $request, int $id)
    {
        $this->roleService->update($id, $request->validated());

        return redirect()->route('roles.index')
            ->with('success', __('Role updated successfully.'));
    }

    public function destroy(int $id)
    {
        $this->roleService->delete($id);

        return redirect()->route('roles.index')
            ->with('success', __('Role deleted successfully.'));
    }
}
