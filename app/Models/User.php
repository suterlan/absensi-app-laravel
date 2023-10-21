<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ADMIN_ROLE_ID = 1;
    const OPERATOR_ROLE_ID = 2;
    const GURU_ROLE_ID = 3;
    const SISWA_ROLE_ID = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $connection = 'mysql2';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'position_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function scopeOnlyEmployees($query)
    {
        return $query->where('role_id', self::SISWA_ROLE_ID)
                    ->orWhere('role_id', self::GURU_ROLE_ID);
    }

    public function isAdmin()
    {
        return $this->role_id === self::ADMIN_ROLE_ID;
    }

    public function isOperator()
    {
        return $this->role_id === self::OPERATOR_ROLE_ID;
    }

    public function isGuru()
    {
        return $this->role_id === self::GURU_ROLE_ID;
    }

    public function isSiswa()
    {
        return $this->role_id === self::SISWA_ROLE_ID;
    }
}
