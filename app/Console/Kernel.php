<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Everyminute::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('products_insert:everyminute')
        //     ->everyMinute();

        // $schedule->command('Send:Email')
        // ->everyMinute();

        $schedule->call('App\Http\Controllers\LeadController@sendScheduleEmail')
        ->everyMinute();

  

        // $schedule->call(
        //     function () {
        //         DB::insert(
        //             'insert into products (name, description, price, status, file_path, created_at, updated_at) values (?,?,?,?,?,?,?)',
        //             array('Product One', 'Description', 590.0, 'true', 'products/iZ6pfYDEg2u2C5XFXIKQ9LdqVqndPy2EQHifIgw2.jpg', '2021-08-07 15:04:33', '2021-08-07 15:04:33')
        //         );
        //     }
        // )->everyMinute();

        //$schedule->call(function() {info('called every minute');})->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
