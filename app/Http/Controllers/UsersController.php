<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersController extends Controller
{
    public function show(Request $request)
    {
        $users = User::where('id', '!=', auth()->id())->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first(),
                'permissions' => $user->getPermissionsViaRoles()
            ];
        });


        $permissions = Permission::all();
        $roles = Role::all();

        return Inertia::render('Users', [
            'users' => $users,
            'role_list' => $roles,
            'permission_list' => $permissions
        ]);
    }


    public function save(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);


        $user->name = $request->input('name');
        $user->email = $request->input('email');

        $roleId = $request->input('role_id');
        $user->syncRoles($roleId);

        $permissions = $request->input('permissions');
        $user->syncPermissions($permissions);

        $user->save();
        return redirect()->route('users');
    }


    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found!'], 404);
        }
        $user->delete();
        return redirect()->route('users');
    }
}
