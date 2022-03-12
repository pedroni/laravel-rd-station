<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rd_station_config', function (Blueprint $table) {
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->text('code')->nullable();

            $table->timestamp('expires_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }
};
