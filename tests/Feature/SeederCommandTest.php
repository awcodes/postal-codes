<?php

declare(strict_types=1);

use Awcodes\PostalCodes\Models\PostalCode;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
});

/**
 * Builds a zip in the shape GeoNames serves: the country's tab-delimited
 * export alongside a readme, both at the archive root.
 */
function geonamesZip(string $countryCode = 'US', ?string $contents = null): string
{
    $contents ??= implode("\t", [
        'US', '31405', 'Savannah', 'Georgia', 'GA',
        'Chatham', '051', '', '',
        '32.0203', '-81.1188', '4',
    ]);

    $path = tempnam(sys_get_temp_dir(), 'geonames') . '.zip';

    $zip = new ZipArchive;
    $zip->open($path, ZipArchive::CREATE);
    $zip->addFromString("{$countryCode}.txt", $contents . "\n");
    $zip->addFromString('readme.txt', 'GeoNames postal code files');
    $zip->close();

    $binary = file_get_contents($path);
    unlink($path);

    return $binary;
}

it('downloads, extracts and imports a country', function () {
    Http::fake([
        'download.geonames.org/*' => Http::response(geonamesZip()),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'US'])
        ->assertSuccessful();

    expect(PostalCode::count())->toBe(1);

    expect(PostalCode::first())
        ->country_code->toBe('US')
        ->postal_code->toBe('31405')
        ->place_name->toBe('Savannah')
        ->state->toBe('GA');
});

it('defaults to seeding US', function () {
    Http::fake([
        'download.geonames.org/*' => Http::response(geonamesZip()),
    ]);

    $this->artisan('postal-codes:seed')->assertSuccessful();

    Http::assertSent(fn ($request) => $request->url() === 'https://download.geonames.org/export/zip/US.zip');
});

it('requests the archive for the given country', function () {
    Http::fake([
        'download.geonames.org/*' => Http::response(geonamesZip('CA')),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'CA'])->assertSuccessful();

    Http::assertSent(fn ($request) => $request->url() === 'https://download.geonames.org/export/zip/CA.zip');
});

it('cleans up the archive and extracted files when finished', function () {
    Http::fake([
        'download.geonames.org/*' => Http::response(geonamesZip()),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'US'])->assertSuccessful();

    Storage::disk('local')->assertMissing('US.zip');
    Storage::disk('local')->assertMissing('US.txt');
    Storage::disk('local')->assertMissing('readme.txt');

    // Every path has to resolve through the disk. Reaching for storage_path()
    // instead lands in storage/app while the local disk points at
    // storage/app/private on Laravel 11+, so cleanup silently misses.
    expect(glob(storage_path('app/*.zip')))->toBeEmpty();
    expect(glob(storage_path('app/*.txt')))->toBeEmpty();
});

it('replaces existing codes rather than appending to them', function () {
    PostalCode::factory()->count(3)->create();

    Http::fake([
        'download.geonames.org/*' => Http::response(geonamesZip()),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'US'])->assertSuccessful();

    expect(PostalCode::count())->toBe(1);
});

it('imports every row in the export', function () {
    $rows = collect(['31405', '31401', '31404'])
        ->map(fn (string $code) => implode("\t", [
            'US', $code, 'Savannah', 'Georgia', 'GA',
            'Chatham', '051', '', '',
            '32.0203', '-81.1188', '4',
        ]))
        ->implode("\n");

    Http::fake([
        'download.geonames.org/*' => Http::response(geonamesZip('US', $rows)),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'US'])->assertSuccessful();

    expect(PostalCode::pluck('postal_code')->all())
        ->toEqualCanonicalizing(['31405', '31401', '31404']);
});

it('fails when the download fails', function () {
    Http::fake([
        'download.geonames.org/*' => Http::response('Not Found', 404),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'ZZ'])
        ->expectsOutputToContain('Could not download zip file for ZZ.')
        ->assertFailed();

    expect(PostalCode::count())->toBe(0);
});

it('fails when the archive is not a readable zip', function () {
    Http::fake([
        'download.geonames.org/*' => Http::response('this is not a zip'),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'US'])
        ->expectsOutputToContain('Could not extract zip file for US.')
        ->assertFailed();
});

it('fails when the archive does not contain the expected data file', function () {
    $path = tempnam(sys_get_temp_dir(), 'geonames') . '.zip';

    $zip = new ZipArchive;
    $zip->open($path, ZipArchive::CREATE);
    $zip->addFromString('readme.txt', 'GeoNames postal code files');
    $zip->close();

    $binary = file_get_contents($path);
    unlink($path);

    Http::fake([
        'download.geonames.org/*' => Http::response($binary),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'US'])
        ->expectsOutputToContain('The archive for US did not contain a US.txt data file.')
        ->assertFailed();
});

it('leaves existing codes alone when the download fails', function () {
    PostalCode::factory()->count(3)->create();

    Http::fake([
        'download.geonames.org/*' => Http::response('Not Found', 404),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'ZZ'])->assertFailed();

    expect(PostalCode::count())->toBe(3);
});

it('leaves existing codes alone when the archive cannot be extracted', function () {
    PostalCode::factory()->count(3)->create();

    Http::fake([
        'download.geonames.org/*' => Http::response('this is not a zip'),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'US'])->assertFailed();

    expect(PostalCode::count())->toBe(3);
});

it('leaves existing codes alone when the archive has no data file', function () {
    PostalCode::factory()->count(3)->create();

    $path = tempnam(sys_get_temp_dir(), 'geonames') . '.zip';

    $zip = new ZipArchive;
    $zip->open($path, ZipArchive::CREATE);
    $zip->addFromString('readme.txt', 'GeoNames postal code files');
    $zip->close();

    $binary = file_get_contents($path);
    unlink($path);

    Http::fake([
        'download.geonames.org/*' => Http::response($binary),
    ]);

    $this->artisan('postal-codes:seed', ['country' => 'US'])->assertFailed();

    expect(PostalCode::count())->toBe(3);
});

it('reuses an already downloaded archive instead of downloading again', function () {
    Storage::disk('local')->put('US.zip', geonamesZip());

    Http::fake();

    $this->artisan('postal-codes:seed', ['country' => 'US'])->assertSuccessful();

    Http::assertNothingSent();
});
