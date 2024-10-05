<?php

namespace App\Http\Controllers;

use App\DataTables\RolePermissionDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolePermissionController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware(['permission:access management index']);
    // }

    /**
     * Display a listing of the resource.
     */
    public function index(RolePermissionDataTable $dataTable)
    {
        return $dataTable->render('role-permission.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy('group_name');
        return view('role-permission.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => ['required', 'max:40', 'unique:roles,name'],
            'permissions' => ['required']
        ]);

        $role = Role::create(['name' => $request->role_name]);
        $role->syncPermissions($request->permissions);

        toastr()->success('Created Successfully!');

        return to_route('role.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        $permissions = Permission::all()->groupBy('group_name');
        return view('role-permission.edit', compact('permissions', 'role', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'role_name' => ['required', 'max:40', 'unique:roles,name,' . $id],
            'permissions' => ['required']
        ]);

        $role = Role::findOrFail($id);
        if($role->name === 'Super Admin'){
            abort(403);
        }
        $role->name = $request->role_name;
        $role->save();

        $role->syncPermissions($request->permissions);

        toastr()->success('Created Successfully!');

        return to_route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = Role::findOrFail($id);
            if($role->name === 'Super Admin'){
                abort(403);
            }
            $role->delete();
            return response(['status' => 'success', 'message' => 'Deleted successfully!']);
        } catch (\Exception $e) {
            logger($e);
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
