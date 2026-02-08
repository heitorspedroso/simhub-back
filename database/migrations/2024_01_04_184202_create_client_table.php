<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientTable extends Migration
{
    public function up()
    {
        Schema::create('client', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nick')->nullable();
            $table->string('zip');
            $table->string('state');
            $table->string('city');
            $table->string('address');
            $table->string('number');
            $table->string('district');
            $table->string('complement')->nullable();
            $table->unsignedBigInteger('phone');
            $table->string('contact_name')->nullable();
            $table->string('status')->default(0);
            $table->string('observation')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('client');
    }
}
