<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function positions()
    {
        return $this->HasMany(Position::class);
    }

    public function candidates()
    {
        return $this->HasMany(Candidate::class);
    }
}
