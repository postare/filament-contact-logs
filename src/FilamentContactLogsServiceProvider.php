<?php

namespace Postare\FilamentContactLogs;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Postare\FilamentContactLogs\Commands\FilamentContactLogsCommand;
use Postare\FilamentContactLogs\Testing\TestsFilamentContactLogs;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentContactLogsServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-contact-logs';

    public static string $viewNamespace = 'filament-contact-logs';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('postare/filament-contact-logs');
            });

        $package->hasConfigFile('contact-logs');

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        //        // Asset Registration
        //        FilamentAsset::register(
        //            $this->getAssets(),
        //            $this->getAssetPackageName()
        //        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-contact-logs/{$file->getFilename()}"),
                ], 'filament-contact-logs-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentContactLogs());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'postare/filament-contact-logs';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-contact-logs', __DIR__ . '/../resources/dist/components/filament-contact-logs.js'),
            Css::make('filament-contact-logs-styles', __DIR__ . '/../resources/dist/filament-contact-logs.css'),
            Js::make('filament-contact-logs-scripts', __DIR__ . '/../resources/dist/filament-contact-logs.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentContactLogsCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_contact_logs_table',
        ];
    }
}
