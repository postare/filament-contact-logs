<?php

namespace Postare\FilamentContactLogs;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentContactLogsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-contact-logs';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            Resources\ContactLogResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
