<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function registered_member()
    {
        return $this->hasOne(RegisteredMember::class);
    }

    public function voided_members()
    {
        return $this->HasMany(VoidedMember::class);
    }
}
