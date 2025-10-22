<?php

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
            $table->float('lat', 10, 8);
            $table->float('lng', 11, 8);
            $table->integer('accuracy')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postal_codes');
    }
};
