<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Menu;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UpdateMenuRequest;

class MenuController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus',
            'workbook' => 'required|string|max:255',
            'view' => 'required|string|max:255',
            'group' => 'required|array',
        ]);

        // Create a new menu record
        Menu::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'workbook' => $request->input('workbook'),
            'view' => $request->input('view'),
            'group' => json_encode($request->input('group')),
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Menu created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        // Return view with specific menu data
        return view('menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        // Return view for editing the specified menu
        return view('menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:menus,slug,' . $menu->id,
            'workbook' => 'required|string|max:255',
            'view' => 'required|string|max:255',
            'group' => 'required|array',
        ]);

        // Update the menu record
        $menu->update([
            'name' => $validatedData['name'],
            'slug' => $validatedData['slug'],
            'workbook' => $validatedData['workbook'],
            'view' => $validatedData['view'],
            'group' => $validatedData['group'], // Ensure group is properly encoded
        ]);


        // Redirect back with success message
        return redirect()->back()->with('success', 'Menu updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Menu deleted successfully.');
    }

    public function getMenusForUser(Request $request)
    {
        // Assuming user roles are the same as groups
        $userRoles = auth()->user()->roles->pluck('name')->toArray();

        // Fetch menus where the group matches the user's roles
        $menus = Menu::where(function ($query) use ($userRoles) {
            foreach ($userRoles as $role) {
                $query->orWhereJsonContains('group', $role);
            }
        })->get();

        return response()->json($menus);
    }
}
