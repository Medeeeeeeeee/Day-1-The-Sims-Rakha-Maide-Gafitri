<?php

namespace Database\Seeders;

use App\Models\Lab;
use App\Models\Ticket;
use App\Models\User;
use App\Models\MaintenanceSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        /*
        |====================================================
        | USERS
        |====================================================
        */
        User::updateOrCreate(
            ['email' => 'supervisor@example.com'],
            [
                'nim' => 'NIM0000001',
                'nama' => 'Supervisor',
                'password' => bcrypt('password'),
                'role' => 'supervisor',
                'gender' => 'Laki-Laki',
            ]
        );

        $asisten = User::updateOrCreate(
            ['email' => 'asisten@example.com'],
            [
                'nim' => 'NIM0000002',
                'nama' => 'Asisten',
                'password' => bcrypt('password'),
                'role' => 'asisten',
                'gender' => 'Laki-Laki',
            ]
        );

        /*
        |====================================================
        | LABS
        |====================================================
        */
        $lab1 = Lab::updateOrCreate(
            ['nama_lab' => 'Lab 1'],
            ['pc_count' => 30]
        );

        $lab2 = Lab::updateOrCreate(
            ['nama_lab' => 'Lab 2'],
            ['pc_count' => 30]
        );

        $lab3 = Lab::updateOrCreate(
            ['nama_lab' => 'Lab 3'],
            ['pc_count' => 30]
        );

        /*
        |====================================================
        | TICKETS
        |====================================================
        */
        Ticket::updateOrCreate(
            [
                'nama_pelapor' => 'Udin',
                'judul' => 'Printer tidak menyala'
            ],
            [
                'deskripsi' => 'Printer di lab sering mati saat digunakan.',
                'status' => 'pending',
                'lab_id' => $lab1->id,
                'nomor_pc' => 5,
            ]
        );

        Ticket::updateOrCreate(
            [
                'nama_pelapor' => 'Agus',
                'judul' => 'Koneksi internet lambat'
            ],
            [
                'deskripsi' => 'Wi-fi lab sangat lambat pada siang hari.',
                'status' => 'pending',
                'lab_id' => $lab2->id,
                'nomor_pc' => 10,
            ]
        );

          MaintenanceSchedule::updateOrCreate(
            [
                'lab_id' => $lab1->id,
                'nomor_pc' => 5,
                'tanggal_jadwal' => '2026-04-05 09:00:00',
            ],
            [
                'asisten_nim' => $asisten->nim,
                'keterangan' => 'Pembersihan perangkat dan pengecekan koneksi jaringan.',
                'status_jadwal' => 'menunggu',
            ]
        );

        MaintenanceSchedule::updateOrCreate(
            [
                'lab_id' => $lab2->id,
                'nomor_pc' => 10,
                'tanggal_jadwal' => '2026-04-06 10:00:00',
            ],
            [
                'asisten_nim' => $asisten->nim,
                'keterangan' => 'Pemeriksaan monitor dan keyboard.',
                'status_jadwal' => 'menunggu',
            ]
        );

        /*
        |====================================================
        | FACTORY USER
        |====================================================
        */
        User::factory(5)->create();
    }
}