<?php

namespace Awcodes\PostalCodes;

use Awcodes\PostalCodes\Commands\PostalCodesSeederCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PostalCodesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('postal-codes')
            ->hasMigration('create_postal_codes_table')
            ->hasCommand(PostalCodesSeederCommand::class)
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishMigrations()
                    ->askToRunMigrations();
            });
    }
}
