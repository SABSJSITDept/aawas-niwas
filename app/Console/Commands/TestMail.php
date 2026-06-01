<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackConfirmation;
use App\Models\Feedback;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality with feedback confirmation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Create a dummy feedback for testing
        $feedback = new Feedback([
            'name' => 'Test User',
            'email' => $email,
            'phone' => '1234567890',
            'message' => 'This is a test feedback message for email testing.',
        ]);
        
        // Set created_at manually for testing
        $feedback->created_at = now();

        try {
            Mail::to($email)->send(new FeedbackConfirmation($feedback));
            $this->info("✅ Test email sent successfully to: {$email}");
            $this->info("📧 Check your email inbox and spam folder.");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
        }

        // Show current mail configuration
        $this->info("\n📋 Current Mail Configuration:");
        $this->info("Driver: " . config('mail.default'));
        $this->info("Host: " . config('mail.mailers.smtp.host'));
        $this->info("Port: " . config('mail.mailers.smtp.port'));
        $this->info("From: " . config('mail.from.address'));
        
        return 0;
    }
}
