<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScheduleWorkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:work';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the schedule worker';

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
        $min = now()->minute;
        while (true) {
            if (now()->minute === $min) {

                // Call Command Kernel
                $this->call('schedule:run');

                // Minute Interval
                if (now()->minute === 0) {
                    $min = 0;
                } else {
                    $min = $min + 1;
                }
            }

            sleep(30);
            print_r(now()->minute);
        }
    }
}
