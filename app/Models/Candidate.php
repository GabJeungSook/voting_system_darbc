<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function votes()
    {
        return $this->HasMany(Vote::class);
    }
}
