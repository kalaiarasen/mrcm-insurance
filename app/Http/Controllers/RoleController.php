<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('pages.role.index', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            // Get permission names from IDs
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully!',
            'data' => $role->load('permissions')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $role->load('permissions')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role): JsonResponse
    {
        // Prevent editing Super Admin role
        if ($role->name === 'Super Admin') {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin role cannot be modified!'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            // Get permission names from IDs
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            $role->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully!',
            'data' => $role->fresh()->load('permissions')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): JsonResponse
    {
        // Prevent deleting Super Admin role
        if ($role->name === 'Super Admin') {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin role cannot be deleted!'
            ], 403);
        }

        // Check if role is assigned to users
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete role that is assigned to users!'
            ], 400);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully!'
        ]);
    }
}
