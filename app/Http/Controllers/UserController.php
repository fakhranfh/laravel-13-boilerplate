<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRolesRequest;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService,
    ) {}

    /**
     * @return array<int, Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.view', only: ['index']),
            new Middleware('permission:users.assign-roles', only: ['editRoles', 'updateRoles']),
        ];
    }

    public function index()
    {
        return view('app.user.index', [
            'users' => $this->userService->getAllWithRoles(),
        ]);
    }

    public function editRoles(int $id)
    {
        return view('app.user.roles', [
            'user' => $this->userService->find($id),
            'roles' => $this->roleService->getAll(),
        ]);
    }

    public function updateRoles(UpdateUserRolesRequest $request, int $id)
    {
        $user = $this->userService->find($id);

        $this->userService->syncRoles($user, $request->validated('roles', []));

        return redirect()->route('users.index')
            ->with('success', __('Roles updated successfully.'));
    }
}
