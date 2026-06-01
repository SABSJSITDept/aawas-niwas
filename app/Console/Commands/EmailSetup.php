<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EmailSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:setup {type=gmail : Type of email setup (gmail|mailtrap|log)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick email configuration setup';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        
        $this->info("🚀 Setting up email configuration for: " . strtoupper($type));
        
        switch($type) {
            case 'gmail':
                $this->setupGmail();
                break;
            case 'mailtrap':
                $this->setupMailtrap();
                break;
            case 'log':
                $this->setupLog();
                break;
            default:
                $this->error("❌ Invalid type. Use: gmail, mailtrap, or log");
                return 1;
        }
        
        return 0;
    }
    
    private function setupGmail()
    {
        $this->warn("📧 Gmail SMTP Setup");
        $this->line("To use Gmail SMTP, you need:");
        $this->line("1. Enable 2-Factor Authentication on your Gmail");
        $this->line("2. Generate App Password from Google Account settings");
        $this->line("3. Update your .env file with these settings:");
        $this->line("");
        
        $email = $this->ask('Enter your Gmail address', 'your-email@gmail.com');
        $appPassword = $this->secret('Enter your Gmail App Password (will be hidden)');
        $adminEmail = $this->ask('Enter admin email for notifications', $email);
        
        $envContent = "
# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME={$email}
MAIL_PASSWORD={$appPassword}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=\"{$email}\"
MAIL_FROM_NAME=\"चातुर्मास आवास निवास\"
ADMIN_EMAIL={$adminEmail}
";
        
        file_put_contents(base_path('gmail_config.txt'), $envContent);
        
        $this->info("✅ Gmail configuration saved to gmail_config.txt");
        $this->line("📝 Copy the contents to your .env file");
        $this->line("🔄 Run: php artisan config:clear && php artisan config:cache");
        $this->line("🧪 Test: php artisan mail:test {$email}");
    }
    
    private function setupMailtrap()
    {
        $this->warn("📧 Mailtrap Setup");
        $this->line("Mailtrap is perfect for email testing without sending real emails.");
        $this->line("1. Sign up at https://mailtrap.io (free)");
        $this->line("2. Create an inbox");
        $this->line("3. Get SMTP credentials from inbox settings");
        $this->line("");
        
        $username = $this->ask('Enter Mailtrap username');
        $password = $this->secret('Enter Mailtrap password');
        
        $envContent = "
# Mailtrap SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME={$username}
MAIL_PASSWORD={$password}
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=\"noreply@chaturmas.com\"
MAIL_FROM_NAME=\"चातुर्मास आवास निवास\"
ADMIN_EMAIL=admin@chaturmas.com
";
        
        file_put_contents(base_path('mailtrap_config.txt'), $envContent);
        
        $this->info("✅ Mailtrap configuration saved to mailtrap_config.txt");
        $this->line("📝 Copy the contents to your .env file");
        $this->line("🔄 Run: php artisan config:clear && php artisan config:cache");
        $this->line("🧪 Test: php artisan mail:test test@example.com");
    }
    
    private function setupLog()
    {
        $this->info("📄 Log Driver Setup (Current)");
        $this->line("Emails will be saved to storage/logs/laravel.log");
        $this->line("Perfect for development and testing.");
        $this->line("");
        $this->line("Current configuration is already set to log mode.");
        $this->line("🧪 Test: php artisan mail:test test@example.com");
        $this->line("📄 Check: storage/logs/laravel.log for email content");
    }
}
