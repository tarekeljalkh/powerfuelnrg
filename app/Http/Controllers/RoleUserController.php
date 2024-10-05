<?php

namespace App\Http\Controllers;

use App\DataTables\RoleUserDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleUserCreateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleUserController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware(['permission:access management index']);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index(RoleUserDataTable $dataTable)
    {
        return $dataTable->render('role-permission.role-user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('role-permission.role-user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleUserCreateRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $user->assignRole($request->role);

        toastr()->success('Created Successfully!');
        return to_route('role-user.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $roles = Role::all();
        $user = User::findOrFail($id);
        return view('role-permission.role-user.edit', compact('roles', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $request->filled('password') ?? $user->password = bcrypt($request->password);
        $user->save();

        $user->syncRoles($request->role);

        toastr()->success('Update Successfully!');
        return to_route('role-user.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            User::findOrFail($id)->delete();

            return response(['status' => 'success', 'message' => 'Deleted successfully!']);
        }catch(\Exception $e){
            logger($e);
            return response(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
