<?php

namespace App\Http\Controllers;

use App\Models\CompanyUser;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private $modules = [
        'party_master',
        'item_master',
        'process_master',
        'item_process',
        'employee_master',
        'machine_master',
        'sales_order',
        'supplier_PO',
        'grn',
    ];

    public function index($userId)
    {
        $user = CompanyUser::findOrFail($userId);
        $permissions = Permission::where('user_id', $userId)->get()->keyBy('module');

        return view('admin.users.permission', [
            'user' => $user,
            'modules' => $this->modules,
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request, $userId)
    {
        foreach ($this->modules as $module) {
            $permData = $request->permissions[$module] ?? null;

            $hasPermission = $permData &&
                (isset($permData['view']) || isset($permData['add']) || isset($permData['edit']) || isset($permData['delete']));

            if ($hasPermission) {
                Permission::updateOrCreate(
                    ['user_id' => $userId, 'module' => $module],
                    [
                        'view'   => isset($permData['view']) ? 1 : 0,
                        'add'    => isset($permData['add']) ? 1 : 0,
                        'edit'   => isset($permData['edit']) ? 1 : 0,
                        'delete' => isset($permData['delete']) ? 1 : 0,
                    ]
                );
            } else {
                Permission::where('user_id', $userId)->where('module', $module)->delete();
            }
        }

        return back()->with('success', 'Permissions updated successfully');
    }
}
