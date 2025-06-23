<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('addresses', function (Blueprint $table) {
            if (Schema::hasColumn('addresses', 'province')) {
                $table->dropColumn('province');
            }
            if (Schema::hasColumn('addresses', 'district')) {
                $table->dropColumn('district');
            }
            if (Schema::hasColumn('addresses', 'municipality')) {
                $table->dropColumn('municipality');
            }
            if (Schema::hasColumn('addresses', 'ward')) {
                $table->dropColumn('ward');
            }
            if (Schema::hasColumn('addresses', 'street_address')) {
                $table->dropColumn('street_address');
            }
        });
    }

    public function down()
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('province')->nullable();
            $table->string('district')->nullable();
            $table->string('municipality')->nullable();
            $table->integer('ward')->nullable();
            $table->string('street_address')->nullable();
        });
    }
}; 