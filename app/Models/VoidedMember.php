<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoidedMember extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function registered_member()
    {
        return $this->belongsTo(RegisteredMember::class, 'registered_member_id');
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
