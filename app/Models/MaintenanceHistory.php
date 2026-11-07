<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lab;
use App\Models\Ticket;
use App\Models\User;

class MaintenanceHistory extends Model
{
    protected $fillable = [
        'lab_id',
        'nomor_pc',
        'ticket_id',
        'asisten_nim',
        'tindakan',
        'catatan',
        'tanggal_perawatan',
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function asisten()
    {
        return $this->belongsTo(User::class, 'asisten_id');
    }
}
