<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age')->nullable();
            $table->time('target_wake_up_time')->nullable();
            $table->text('introduction')->nullable();
            $table->string('profile_image_path')->nullable();
            $table->integer('wake_up_achievements')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'age', 
                'target_wake_up_time', 
                'introduction', 
                'profile_image_path', 
                'wake_up_achievements'
            ]);
        });
    }
};
