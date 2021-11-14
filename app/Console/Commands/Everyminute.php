<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Everyminute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products_insert:everyminute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This inserts product in products table everyminute';

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
     * @return int
     */
    public function handle()
    {
        DB::insert(
            'insert into products (name, description, price, status, file_path, created_at, updated_at) values (?,?,?,?,?,?,?)',
             array('Product One', 'Description', 590.0, 'true', 'products/iZ6pfYDEg2u2C5XFXIKQ9LdqVqndPy2EQHifIgw2.jpg', '2021-08-07 15:04:33', '2021-08-07 15:04:33')
            );



    }
}
