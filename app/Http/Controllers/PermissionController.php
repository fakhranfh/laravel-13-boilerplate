<?php

namespace App\Http\Controllers;

use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Services\PermissionService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    public function __construct(protected PermissionService $permissionService) {}

    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:permissions.view', only: ['index', 'create', 'edit']),
            new Middleware('permission:permissions.create', only: ['create', 'store']),
            new Middleware('permission:permissions.update', only: ['edit', 'update']),
            new Middleware('permission:permissions.delete', only: ['destroy']),
        ];
    }

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
