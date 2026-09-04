<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Postal Codes Workbench</title>
    <style>
        body { color: #1f2937; font-family: ui-sans-serif, system-ui, sans-serif; margin: 3rem auto; max-width: 64rem; padding: 0 1.5rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border-bottom: 1px solid #d1d5db; padding: .75rem; text-align: left; }
        code { background: #f3f4f6; padding: .15rem .3rem; }
    </style>
</head>
<body>
    <h1>Postal Codes Workbench</h1>
    <p>
        These records were created with <code>PostalCode::factory()</code> and loaded through
        <code>Awcodes\PostalCodes\Models\PostalCode</code>. Duplicate codes demonstrate why callers
        may need <code>get()</code> instead of <code>first()</code>.
    </p>

    <table>
        <thead>
            <tr>
                <th>Postal code</th>
                <th>Place</th>
                <th>State</th>
                <th>Coordinates</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($postalCodes as $postalCode)
                <tr>
                    <td>{{ $postalCode->postal_code }}</td>
                    <td>{{ $postalCode->place_name }}</td>
                    <td>{{ $postalCode->state_name }} ({{ $postalCode->state }})</td>
                    <td>{{ $postalCode->lat }}, {{ $postalCode->lng }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>To replace this local data with a GeoNames country export, run <code>php artisan postal-codes:seed COUNTRY</code>.</p>
</body>
</html>
