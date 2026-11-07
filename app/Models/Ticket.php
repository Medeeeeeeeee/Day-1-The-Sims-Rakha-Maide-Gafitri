<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Lab;
use App\Models\MaintenanceHistory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_id',
        'nomor_pc',
        'nama_pelapor',
        'judul',
        'deskripsi',
        'status',
        'tanggal_selesai',
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function histories()
    {
        return $this->hasMany(MaintenanceHistory::class);
    }
}
