<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Ticket;
use App\Models\MaintenanceHistory;
use App\Models\MaintenanceSchedule;

class Lab extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lab',
        'pc_count',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function maintenanceHistories()
    {
        return $this->hasMany(MaintenanceHistory::class);
    }

    public function maintenanceSchedules()
    {
        return $this->hasMany(MaintenanceSchedule::class);
    }
}
