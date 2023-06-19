<?php

namespace Awcodes\PostalCodes\Imports;

use Awcodes\PostalCodes\Models\PostalCode;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithProgressBar;

class PostalCodeImport implements ToModel, WithCustomCsvSettings, WithBatchInserts, WithProgressBar
{
    use Importable;

    public function model(array $row): PostalCode
    {
        return new PostalCode([
            'country_code' => $row[0],
            'postal_code' => $row[1],
            'place_name' => $row[2],
            'state_name' => $row[3],
            'state' => $row[4],
            'county_name' => $row[5],
            'county_code' => $row[6],
            'community_name' => $row[7],
            'community_code' => $row[8],
            'lat' => $row[9],
            'lng' => $row[10],
            'accuracy' => $row[11],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => "\t",
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }
}
