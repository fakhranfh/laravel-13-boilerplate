<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateUserRolesRequest;
use App\Services\RoleService;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected RoleService $roleService,
    ) {}

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
