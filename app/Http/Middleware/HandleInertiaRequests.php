<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use Inertia\Middleware;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $userRoles = $request->user() ? $request->user()->getRoleNames()->toArray() : [];
        if (in_array('admin', $userRoles)) {
            $menus = Menu::all();
        } else {
            $menus = Menu::where(function ($query) use ($userRoles) {
                foreach ($userRoles as $role) {
                    $query->orWhereJsonContains('group', $role);
                }
            })->get();
        }
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() ? array_merge($request->user()->only('id', 'name', 'email'), [
                    'roles' => $request->user()->getRoleNames(),
                    'permissions' => $request->user()->getAllPermissions()->pluck('name'),
                    'menus' => $menus
                ]) : null,
            ],
        ]);
    }
}
