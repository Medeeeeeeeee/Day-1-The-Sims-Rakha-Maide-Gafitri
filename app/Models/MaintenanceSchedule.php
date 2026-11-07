<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lab;
use App\Models\User;

class MaintenanceSchedule extends Model
{
    protected $fillable = [
        'lab_id',
        'nomor_pc',
        'asisten_nim',
        'tanggal_jadwal',
        'keterangan',
        'status_jadwal',
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function asisten()
    {
        return $this->belongsTo(User::class, 'asisten_nim', 'nim');
    }
}
