<?php

namespace Awcodes\PostalCodes;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Awcodes\PostalCodes\Commands\PostalCodesCommand;

class PostalCodesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('postal-codes')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_postal-codes_table')
            ->hasCommand(PostalCodesCommand::class);
    }
}
