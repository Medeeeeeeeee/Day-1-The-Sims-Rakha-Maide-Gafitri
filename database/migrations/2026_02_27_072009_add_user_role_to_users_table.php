<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // expand role enum to include regular users
            $table->enum('role', ['supervisor', 'asisten', 'user'])
                  ->default('user')
                  ->change();
        });
    }

    
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->enum('role', ['supervisor', 'asisten'])
                  ->default('asisten')
                  ->change();
        });
    }
};
