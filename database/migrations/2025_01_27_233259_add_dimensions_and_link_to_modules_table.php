<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->integer('width');    // Adding width field
            $table->integer('height');   // Adding height field
            $table->string('color');     // Adding color field
            $table->string('link');      // Adding link field
        });
    }
    
    public function down()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('width');
            $table->dropColumn('height');
            $table->dropColumn('color');
            $table->dropColumn('link');
        });
    }
    
};
