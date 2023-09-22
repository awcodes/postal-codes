<?php

namespace Awcodes\PostalCodes\Commands;

use Awcodes\PostalCodes\Imports\PostalCodeImport;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PostalCodesSeederCommand extends Command
{
    public $signature = 'postal-codes:seed {country=US : The country code to seed. Excepted values can be found at https://download.geonames.org/export/zip/}';

    public $description = 'Seed postal codes by country.';

    public function handle(): int
    {
        $countryCode = $this->argument('country');

        DB::table('postal_codes')->truncate();

        if (! Storage::disk('local')->exists("{$countryCode}.zip")) {
            $zipFile = Http::get("https://download.geonames.org/export/zip/{$countryCode}.zip");

            if ($zipFile->failed()) {
                $this->error("Could not download zip file for {$countryCode}.");

                return self::FAILURE;
            }

            $zipFilePath = storage_path("app/{$countryCode}.zip");

            file_put_contents($zipFilePath, $zipFile->body());

            $zip = new ZipArchive();

            if ($zip->open($zipFilePath) === true) {
                $zip->extractTo(storage_path('app'));
                $zip->close();
            } else {
                $this->error("Could not extract zip file for {$countryCode}.");

                return self::FAILURE;
            }

            $this->info("Extracted zip file for {$countryCode}.");
        }

        $csvFilePath = storage_path("app/{$countryCode}.txt");

        try {
            $this->info("Importing data for {$countryCode}.");

            (new PostalCodeImport)
                ->withOutput($this->output)
                ->import(
                    filePath: $csvFilePath,
                    disk: null,
                    readerType: \Maatwebsite\Excel\Excel::CSV
                );

            $this->info("Imported data for {$countryCode}.");
        } catch (Exception $e) {
            $this->error("Could not import data for {$countryCode}.");

            return self::FAILURE;
        }

        Storage::disk('local')->delete([
            'readme.txt',
            "{$countryCode}.zip",
            "{$countryCode}.txt",
        ]);

        return self::SUCCESS;
    }
}
