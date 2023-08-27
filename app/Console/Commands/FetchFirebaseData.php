<?php

namespace App\Console\Commands;

use App\Http\Controllers\ApiFirebaseController;
use Illuminate\Console\Command;

class FetchFirebaseData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-firebase-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch data from Firebase and insert into users table';

    /**
     * Execute the console command.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $controller = new ApiFirebaseController();
        $controller->index();
        $this->info('Data fetched and inserted successfully.');
    }
}
