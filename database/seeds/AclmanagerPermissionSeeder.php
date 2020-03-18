<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class AclmanagerPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        // Role permissions
        $permissions[] = ['name' => 'browse-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'add-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'read-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'edit-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'delete-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'force-delete-role', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'restore-role', 'guard_name' => 'web' ];
        // User permissions
        $permissions[] = ['name' => 'browse-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'add-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'read-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'edit-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'delete-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'force-delete-user', 'guard_name' => 'web' ];
        $permissions[] = ['name' => 'restore-user', 'guard_name' => 'web' ];
        
        foreach($permissions AS $permission){
            Permission::firstOrCreate($permission);
        }
    }
}
