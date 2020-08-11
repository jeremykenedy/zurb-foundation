<?php

namespace LaravelFrontendPresets\ZurbFoundationPreset;

use Illuminate\Support\ServiceProvider;
use Laravel\Ui\UiCommand;

class ZurbFoundationPresetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the preset.
     *
     * @return void
     */
    public function boot()
    {
        UiCommand::macro('foundation', function ($command) {
            ZurbFoundationPreset::install();

            $command->info('Foundation scaffolding installed successfully.');

            if ($command->option('auth')) {
                ZurbFoundationPreset::installAuth();

                $command->info('Foundation auth scaffolding installed successfully.');
            }

            $command->comment('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
        });
    }
}
