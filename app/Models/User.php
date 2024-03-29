<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
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
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function registered_members()
    {
        return $this->HasMany(RegisteredMember::class);
    }

    public function voided_members()
    {
        return $this->HasMany(VoidedMember::class);
    }

    public function votes()
    {
        return $this->HasMany(Vote::class);
    }

    public function printer()
    {
        return $this->HasOne(Printer::class);
    }

    public function getRoleNameAttribute()
    {
        $roleName = $this->roles->name;

        // Transform "staff" to "electoral officer"
        if ($roleName === 'staff') {
            return 'election officer';
        }

        return $roleName;
    }
}
