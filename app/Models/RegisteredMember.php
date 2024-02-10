<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredMember extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function votes()
    {
        return $this->HasMany(Vote::class);
    }

    public function vote()
    {
        return $this->hasOne(Vote::class);
    }

    public function voided_members()
    {
        return $this->HasMany(VoidedMember::class);
    }

    public function registration_duration()
    {
        return $this->hasOne(RegistrationDuration::class);
    }
}
