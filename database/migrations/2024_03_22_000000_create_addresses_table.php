<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('province');
            $table->string('district');
            $table->string('municipality');
            $table->integer('ward');
            $table->string('street_address');
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            // Ensure a user can only have one default address
            $table->unique(['user_id', 'is_default'], 'unique_default_address');
        });
    }

    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}; 