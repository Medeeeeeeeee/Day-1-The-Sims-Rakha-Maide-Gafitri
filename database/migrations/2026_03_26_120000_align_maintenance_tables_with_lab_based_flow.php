<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE maintenance_schedules MODIFY nomor_pc INT NULL DEFAULT 0");
            DB::statement("UPDATE maintenance_schedules SET nomor_pc = 0 WHERE nomor_pc IS NULL");

            DB::statement("ALTER TABLE maintenance_histories MODIFY nomor_pc INT NULL DEFAULT 0");
            DB::statement("UPDATE maintenance_histories SET nomor_pc = 0 WHERE nomor_pc IS NULL");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("UPDATE maintenance_schedules SET nomor_pc = 0 WHERE nomor_pc IS NULL");
            DB::statement("ALTER TABLE maintenance_schedules MODIFY nomor_pc INT NOT NULL DEFAULT 0");

            DB::statement("UPDATE maintenance_histories SET nomor_pc = 0 WHERE nomor_pc IS NULL");
            DB::statement("ALTER TABLE maintenance_histories MODIFY nomor_pc INT NOT NULL DEFAULT 0");
        }
    }
};
