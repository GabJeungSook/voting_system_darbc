<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    public function candidates()
    {
        return $this->HasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->HasMany(Vote::class);
    }
}
