<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Inertia\Inertia;
use App\Helpers\Tableau;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userRoles = $request->user()->roles->pluck('name')->toArray();
        if (in_array('admin', $userRoles)) {
            $menus = Menu::all();
        } else {
            $menus = Menu::where(function ($query) use ($userRoles) {
                foreach ($userRoles as $role) {
                    $query->orWhereJsonContains('group', $role);
                }
            })->get();
        }
        $menu = $menus->first();
        return $this->renderView($menu, 'Dashboard');
    }

    public function users(Request $request)
    {
        $users = User::where('id', '!=', auth()->id())->get()->map(function ($user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
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

    public function menus(Request $request, $slug = null)
    {
        if ($slug) {
            $menu = Menu::where('slug', $slug)->first();
            return $this->renderView($menu, 'Dashboard');
        } else {
            $userRoles = $request->user()->roles->pluck('name')->toArray();

            if (in_array('admin', $userRoles)) {
                $menus = Menu::all();
            } else {
                $menus = Menu::where(function ($query) use ($userRoles) {
                    foreach ($userRoles as $role) {
                        $query->orWhereJsonContains('group', $role);
                    }
                })->get();
            }

            return Inertia::render('Menus', [
                'menus' => $menus,
                'roles' => Role::get()->pluck('name'),
            ]);
        }
    }

    private function setTable($workbook, $view)
    {
        $tableauSrc = env('TABLEAU_VIEW') . $workbook . '/' . $view;
        return $tableauSrc;
    }

    private function getToken()
    {
        $userName = 'gani.ilham@daas.co.id';
        $secretId = env('TABLEAU_SECRET_ID');
        $secretValue = env('TABLEAU_SECRET');
        $clientId = env('TABLEAU_CLIENT_ID');
        $scope = "tableau:views:embed";
        $token = Tableau::createToken($userName, $secretId, $secretValue, $clientId, $scope);

        return $token;
    }

    private function renderView($menu, $component)
    {
        $tableauSrc = $this->setTable($menu->workbook, $menu->view);
        $token = $this->getToken();
        return Inertia::render($component, [
            'token' => $token,
            'tableauSrc' => $tableauSrc,
            'roles' => auth()->user()->roles,
            'view' => $menu->view,
            'menus' => Menu::all(),
        ]);
    }
}
