<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationDuration extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function registered_member()
    {
        return $this->belongsTo(RegisteredMember::class, 'registered_member_id');
    }
}
