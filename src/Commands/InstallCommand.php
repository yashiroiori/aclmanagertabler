<?php

namespace Yashiroiori\AclManagerTabler\Commands;

use App\Traits\Seedable;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../database/seeds/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:module-aclmanager:install { --force }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install module Aclmanager Tabler';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Seeding data into the database');
        $this->seed('AclmanagerDatabaseSeeder');
        $this->info('Finish!!');
    }
}
