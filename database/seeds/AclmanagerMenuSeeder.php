<?php

use App\Models\Module;
use Illuminate\Database\Seeder;

class AclmanagerMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules['system'] = [
            'module' => ['name' => 'Sistema', 'slug' => 'system', 'icon' => 'fa fa-cog', 'order' => 10, 'header' => 1, 'core' => 0, 'installed' => 0],
            'children' => [
                ['name' => 'Roles',           'slug' => 'role',        'icon' => 'fa fa-lock',       'order' => 1, 'header' => 0, 'core' => 1, 'installed' => 0 ],
                ['name' => 'Usuarios',        'slug' => 'user',        'icon' => 'fa fa-users',      'order' => 2, 'header' => 0, 'core' => 1, 'installed' => 0 ],
            ],
        ];
        foreach($modules AS $module){
            $module['module']['parent_id'] = null;
            $tmp = Module::firstOrCreate($module['module']);
            if(count($module['children']) > 0){
                foreach($module['children'] AS $children){
                    $tmp->children()->firstOrCreate($children);
                }
            }
        }
    }
}
