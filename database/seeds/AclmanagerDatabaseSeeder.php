<?php

use App\Traits\Seedable;
use Illuminate\Database\Seeder;

class AclmanagerDatabaseSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seed(AclmanagerPermissionSeeder::class);
        $this->seed(AclmanagerMenuSeeder::class);
    }
}
