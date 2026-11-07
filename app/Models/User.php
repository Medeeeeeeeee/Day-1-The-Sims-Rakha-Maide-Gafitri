<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\MaintenanceHistory;
use App\Models\MaintenanceSchedule;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * 🔥 FIX PRIMARY KEY (WAJIB)
     */
    protected $primaryKey = 'nim';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nim',
        'nama',
        'email',
        'gender',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 🔥 ROLE HELPERS
     */
    public function isAdmin(): bool
    {
        return $this->role === 'supervisor';
    }

    public function isAsisten(): bool
    {
        return $this->role === 'asisten';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * 🔥 DISPLAY NAME (optional)
     */
    public function displayName(): string
    {
        return $this->nama ?? $this->nim;
    }

    /**
     * 🔥 RELATIONS
     */
    public function maintenanceHistories()
    {
        return $this->hasMany(MaintenanceHistory::class, 'asisten_nim');
    }

    public function maintenanceSchedules()
    {
        return $this->hasMany(MaintenanceSchedule::class, 'asisten_nim');
    }
}