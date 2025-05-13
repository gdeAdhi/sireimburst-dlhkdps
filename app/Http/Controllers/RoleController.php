<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use DataTables;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['roles'] = Role::with('permissions')->get();
        $data['permissions'] = Permission::all();
        if ($request->ajax()) {
            return DataTables::of($data['roles'])
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<button type="button" class="btn btn-info btn-sm edit-btn" data-id="'.$row->id.'">Edit</button>';
                    $btn .= ' <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$row->id.'">Delete</button>';
                    return $btn;
                })
                ->addColumn('permissions', function($row){
                    $permissions = $row->permissions->pluck('name')->toArray();
                    $all = implode(', ', $permissions);
                    $display = implode(', ', array_slice($permissions, 0, 3));
                    if (count($permissions) > 3) {
                        $display .= '...';
                    }
                    return '<span title="'.e($all).'">'.$display.'</span>';
                })
                ->rawColumns(['action','permissions'])
                ->make(true);
        }
        return view('roles.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes = [
            'name' => "Nama Role",
        ];

        $validate = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name'
        ], [], $attributes)->validate();

        $data = Role::create([
            'name' => $validate['name'],
        ]);

        return response()->json([
            'status' => "success",
            'message' => 'Role berhasil ditambahkan!',
            'role' => [
                'id' => $data->id,
                'name' => $data->name,
            ],
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);

        return response()->json([
            'id' => $role->id,
            'name' => $role->name,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $attributes = [
            'name' => "Nama Role",
        ];

        $validate = Validator::make($request->all(), [
            'name' => ['required', 'string', Rule::unique('roles')->ignore($role->id)],
        ], [], $attributes)->validate();

        $role->name = $validate['name'];

        $role->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil diperbarui!',
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil dihapus!',
            'role_id' => $id,
        ], 200);
    }

    public function assignPermissions(Request $request) {
        $validate = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);
    
        $role = Role::findOrFail($validate['role_id']);
        \Log::info($role);
        try {
            if (!empty($validate['permissions'])) {
                $permissionNames = Permission::whereIn('id', $validate['permissions'])->pluck('name')->toArray();
                $role->syncPermissions($permissionNames);
    
                \Log::info("Permissions synced successfully for role: {$role->name}", [
                    'role_id' => $role->id,
                    'permissions' => $permissionNames,
                ]);
            } else {
                $role->syncPermissions([]);
                \Log::info("All permissions cleared for role: {$role->name}", [
                    'role_id' => $role->id,
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Sukses menambahkan permission ke role',
            ], 201);
        } catch (\Exception $e) {
            \Log::error("Failed to sync permissions for role: {$role->name}", [
                'role_id' => $role->id,
                'error' => $e->getMessage(),
            ]);
    
            return redirect()->back()->with('error', 'Failed to assign permissions.');
        }
    }

    public function getRolePermissions(Role $role)
    {
        $permissions = $role->permissions()->pluck('id');
        return response()->json($permissions);
    }

}
