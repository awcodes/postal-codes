<?php

declare(strict_types=1);

namespace Awcodes\PostalCodes\Commands;

use Awcodes\PostalCodes\Imports\PostalCodeImport;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;
use ZipArchive;

class PostalCodesSeederCommand extends Command
{
    public $signature = 'postal-codes:seed {country=US : The country code to seed. Excepted values can be found at https://download.geonames.org/export/zip/}';

    public $description = 'Seed postal codes by country.';

    public function handle(): int
    {
        $countryCode = $this->argument('country');

        $disk = Storage::disk('local');

        if (! $disk->exists("{$countryCode}.zip")) {
            $zipFile = Http::get("https://download.geonames.org/export/zip/{$countryCode}.zip");

            if ($zipFile->failed()) {
                $this->error("Could not download zip file for {$countryCode}.");

                return self::FAILURE;
            }

            $disk->put("{$countryCode}.zip", $zipFile->body());
        }

        $zip = new ZipArchive;

        if ($zip->open($disk->path("{$countryCode}.zip")) === true) {
            $zip->extractTo($disk->path(''));
            $zip->close();
        } else {
            $this->error("Could not extract zip file for {$countryCode}.");

            return self::FAILURE;
        }

        $this->info("Extracted zip file for {$countryCode}.");

        if (! $disk->exists("{$countryCode}.txt")) {
            $this->error("The archive for {$countryCode} did not contain a {$countryCode}.txt data file.");

            return self::FAILURE;
        }

        $csvFilePath = $disk->path("{$countryCode}.txt");

        try {
            $this->info("Importing data for {$countryCode}.");

            // Emptied here rather than at the start of the command. The table holds
            // one country at a time, so the import does have to clear it — but doing
            // that before the archive has downloaded and extracted means a bad country
            // code or a network failure wipes the existing data and puts nothing back.
            DB::table('postal_codes')->truncate();

            (new PostalCodeImport)
                ->withOutput($this->output)
                ->import(
                    filePath: $csvFilePath,
                    readerType: Excel::CSV
                );

            $this->info("Imported data for {$countryCode}.");
        } catch (Exception) {
            $this->error("Could not import data for {$countryCode}.");

            return self::FAILURE;
        }

        $disk->delete([
            'readme.txt',
            "{$countryCode}.zip",
            "{$countryCode}.txt",
        ]);

        return self::SUCCESS;
    }
}
