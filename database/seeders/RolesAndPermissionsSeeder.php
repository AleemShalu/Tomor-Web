<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create admin role
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
        ], [
            'name_en' => 'Administrator',
            'name_ar' => 'مسؤول',
        ]);

        // create owner role
        $ownerRole = Role::firstOrCreate([
            'name' => 'owner',
        ], [
            'name_en' => 'Owner',
            'name_ar' => 'مالك',
        ]);

        // create worker role
        $workerRole = Role::firstOrCreate([
            'name' => 'worker',
        ], [
            'name_en' => 'Worker',
            'name_ar' => 'عامل',
        ]);

        // create worker supervisor role
        $workerSupervisorRole = Role::firstOrCreate([
            'name' => 'worker_supervisor',
        ], [
            'name_en' => 'Worker Supervisor',
            'name_ar' => 'مشرف عمال',
        ]);

        // create supervisor role
        $supervisorRole = Role::firstOrCreate([
            'name' => 'supervisor',
        ], [
            'name_en' => 'Supervisor',
            'name_ar' => 'مشرف',
        ]);

        // create customer role
        $customerRole = Role::firstOrCreate([
            'name' => 'customer',
        ], [
            'name_en' => 'Customer',
            'name_ar' => 'عميل',
        ]);

        // define permissions
        $DashboardPermission = Permission::firstOrCreate(['name' => 'dashboard']);
        $BranchPermission = Permission::firstOrCreate(['name' => 'branch']);
        $TasksPermission = Permission::firstOrCreate(['name' => 'tasks']);

        // assign permissions to roles
        // for supervisor
        $supervisorRole->givePermissionTo($DashboardPermission);
        $supervisorRole->givePermissionTo($BranchPermission);

        // for worker supervisor
        $workerSupervisorRole->givePermissionTo($DashboardPermission);
        $workerSupervisorRole->givePermissionTo($BranchPermission);
        $workerSupervisorRole->givePermissionTo($TasksPermission);

        // for normal worker
        $workerRole->givePermissionTo($TasksPermission);
    }
}
