<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoCheckoutScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-checkout-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new \App\Http\Controllers\Api\Registration\RegistrationController();
        $controller->autoCheckout();
        
        $this->info('Auto checkout process completed.');
    }
}
