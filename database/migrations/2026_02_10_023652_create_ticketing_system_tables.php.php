<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        /*
        |====================================================
        | USERS
        |====================================================
        */
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'role')) {
                    $table->enum('role', ['admin', 'asisten', 'user'])
                          ->default('user')
                          ->after('password');
                }
            });
        }

        /*
        |====================================================
        | LABS
        |====================================================
        */
        Schema::create('labs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lab');
            $table->integer('pc_count'); // jumlah PC (diinput admin)
            $table->timestamps();
        });

        /*
        |====================================================
        | TICKETS (PELAPOR TIDAK LOGIN)
        |====================================================
        */
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lab_id')
                  ->constrained('labs')
                  ->cascadeOnDelete();

            $table->integer('nomor_pc'); // 1 s/d pc_count
            $table->string('nama_pelapor')->nullable();
            $table->string('judul');
            $table->text('deskripsi');

            $table->enum('status', ['pending', 'proses', 'selesai'])
                  ->default('pending');

            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps(); // created_at = tanggal laporan
        });

        /*
        |====================================================
        | MAINTENANCE HISTORIES
        |====================================================
        */
        Schema::create('maintenance_histories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lab_id')
                  ->constrained('labs')
                  ->cascadeOnDelete();

            $table->integer('nomor_pc')->default(0);

            $table->foreignId('ticket_id')
                  ->nullable()
                  ->constrained('tickets')
                  ->nullOnDelete();

            
            $table->string('asisten_nim');

            $table->foreign('asisten_nim')
                  ->references('nim')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->text('tindakan');
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal_perawatan');
            $table->timestamps();
        });

        /*
        |====================================================
        | MAINTENANCE SCHEDULES
        |====================================================
        */
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lab_id')
                  ->constrained('labs')
                  ->cascadeOnDelete();

            $table->integer('nomor_pc')->default(0);

            $table->string('asisten_nim');

            $table->foreign('asisten_nim')
                  ->references('nim')
                  ->on('users')
                  ->cascadeOnDelete();

            $table->dateTime('tanggal_jadwal');
            $table->string('keterangan')->nullable();

            $table->enum('status_jadwal', ['menunggu', 'selesai'])
                  ->default('menunggu');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
        Schema::dropIfExists('maintenance_histories');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('labs');

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'role')) {
                    $table->dropColumn('role');
                }
            });
        }
    }
};
