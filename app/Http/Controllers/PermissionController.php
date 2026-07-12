<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Services\PermissionService;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $permissionService) {}

    public function index()
    {
        return view('app.permission.index', [
            'permissions' => $this->permissionService->getAll(),
        ]);
    }

    public function create()
    {
        return view('app.permission.create');
    }

    public function store(StorePermissionRequest $request)
    {
        $this->permissionService->create($request->validated());

        return redirect()->route('permissions.index')
            ->with('success', __('Permission created successfully.'));
    }

    public function edit(int $id)
    {
        return view('app.permission.edit', [
            'permission' => $this->permissionService->find($id),
        ]);
    }

    public function update(UpdatePermissionRequest $request, int $id)
    {
        $this->permissionService->update($id, $request->validated());

        return redirect()->route('permissions.index')
            ->with('success', __('Permission updated successfully.'));
    }

    public function destroy(int $id)
    {
        $this->permissionService->delete($id);

        return redirect()->route('permissions.index')
            ->with('success', __('Permission deleted successfully.'));
    }
}
