<?php

namespace Georg\ReferralToaster\Console;

use Illuminate\Console\Command;

class InstallReferralToaster extends Command
{
    protected $signature   = 'gtoast:install {--no-overwrite : Do not overwrite existing .env values}';
    protected $description = 'Install Referral Toaster, configure broadcasting, and append Echo setup to app.js';

    public function handle(): int
    {
        $this->info('🚀 Installing Referral Toaster...');


        // 2. Try to scaffold broadcasting (Laravel 12+)
        $this->call('install:broadcasting');
        $this->call('reverb:install');

        // 3. Publish package assets
        $this->call('vendor:publish', [
            '--provider' => "Georg\\ReferralToaster\\ReferralToasterServiceProvider",
            '--force'    => true,
        ]);

        // 4. Append Echo setup to resources/js/app.js
        $this->appendEchoSetup();

        $this->line('');
        $this->info('🎉 Referral Toaster installed successfully!');
        $this->info('👉 Next: run npm install && npm run build');
        return self::SUCCESS;
    }

    /**
     * Append Echo setup stub into app.js
     */
    protected function appendEchoSetup(): void
    {
        $targetPath = base_path('resources/js/app.js');
        $stubPath   = __DIR__ . '/../../stubs/app-echo.js';

        if (! file_exists($stubPath)) {
            $this->error("❌ Stub file not found: $stubPath");
            return;
        }

        if (! file_exists($targetPath)) {
            $this->warn("⚠️ app.js not found at $targetPath. Creating new one...");
            copy($stubPath, $targetPath);
            $this->info("✅ app.js created with Echo setup");
            return;
        }

        $echoCode = file_get_contents($stubPath);
        $targetContents = file_get_contents($targetPath);

        if (strpos($targetContents, 'Referral Toaster Echo Setup') === false) {
            file_put_contents(
                $targetPath,
                "\n\n// ==============================\n".
                "// Referral Toaster Echo Setup\n".
                "// ==============================\n".
                $echoCode,
                FILE_APPEND
            );
            $this->info("✅ Echo setup appended to app.js");
        } else {
            $this->warn("⚠️ Echo setup already exists in app.js. Skipping...");
        }
    }
}
