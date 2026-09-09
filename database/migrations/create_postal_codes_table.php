<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postal_codes', function (Blueprint $table) {
            $table->id();

            $table->string('country_code')->nullable();
            $table->string('postal_code')->index();
            $table->string('place_name')->nullable();
            $table->string('state_name')->nullable();
            $table->string('state')->nullable();
            $table->string('county_name')->nullable();
            $table->string('county_code')->nullable();
            $table->string('community_name')->nullable();
            $table->string('community_code')->nullable();
            // Laravel 11 narrowed Blueprint::float() to float($column, $precision = 53).
            // The old (total, places) arguments are silently discarded, so `float('lat', 10, 8)`
            // set precision 10 -- and MySQL maps FLOAT(p) with p <= 23 to a 4-byte single
            // precision column, about 7 significant digits. Coordinates need 10 (e.g.
            // 40.71277800), so they were being rounded on write. The default precision of 53
            // maps to DOUBLE, which stores them exactly.
            $table->float('lat');
            $table->float('lng');
            $table->integer('accuracy')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postal_codes');
    }
};
