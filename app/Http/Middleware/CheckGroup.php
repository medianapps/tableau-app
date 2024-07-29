<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Menu;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            abort(403, 'Unauthorized action.');
        }
        $roles = $user->getRoleNames()->toArray();
        $menuSlug = $request->route('menu');
        if ($request->is('dashboard')) {
            if (in_array('admin', $roles)) {
                return $next($request);
            }

            $menu = Menu::where(function ($query) use ($roles) {
                foreach ($roles as $role) {
                    $query->orWhereJsonContains('group', $role);
                }
            })->first();

            if ($menu) {
                return redirect()->route('menus.view', $menu->slug);
            } else {
                abort(403, 'Unauthorized action.');
            }
        }

        if (in_array('admin', $roles)) {
            return $next($request);
        }

        $menu = Menu::where('slug', $menuSlug)->first();
        if (!$menu || !array_intersect($roles, json_decode($menu->group, true))) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
