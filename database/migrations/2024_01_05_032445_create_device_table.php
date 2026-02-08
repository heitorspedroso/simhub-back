<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeviceTable extends Migration
{
    public function up()
    {
        Schema::create('device', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('client_id');
            $table->unsignedBiginteger('device_type_id');
            $table->string('code');
            $table->string('name');
            $table->unsignedMediumInteger('alert_level_1')->nullable();
            $table->unsignedMediumInteger('alert_level_2')->nullable();
            $table->unsignedInteger('total_liter')->nullable();
            $table->unsignedInteger('ordem')->nullable();
            $table->string('classification_1')->nullable();
            $table->string('classification_2')->nullable();
            $table->string('classification_3')->nullable();
            $table->string('maintenance_last_1')->nullable();
            $table->string('maintenance_last_2')->nullable();
            $table->unsignedMediumInteger('maintenance_next_1')->nullable();
            $table->unsignedMediumInteger('maintenance_next_2')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('client_id')->references('id')
                ->on('client')->onDelete('cascade');

            $table->foreign('device_type_id')->references('id')
                ->on('device_type')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('device');
    }
}
