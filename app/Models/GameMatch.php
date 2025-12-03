<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GameMatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tournament_id',
        'home_team_id',
        'away_team_id',
        'winner_id',
        'round',
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam()
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
