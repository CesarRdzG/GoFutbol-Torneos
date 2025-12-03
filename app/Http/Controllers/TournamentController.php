<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Team;
use App\Models\GameMatch;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\BracketGeneratedMail;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::all();
        return view('dashboard', compact('tournaments'));
    }

    public function create()
    {
        return view('tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'max_teams' => 'required|integer|in:4,8,16',
        ]);

        Tournament::create([
            'name' => $request->name,
            'max_teams' => $request->max_teams,
            'is_started' => false,
        ]);

        return redirect()->route('tournaments.index')->with('success', 'Torneo creado correctamente.');
    }

    public function show(Tournament $tournament)
    {
        $tournament->load(['teams', 'matches']);
        
        return view('tournaments.show', compact('tournament'));
    }

    public function addTeam(Request $request, Tournament $tournament)
    {
        if ($tournament->is_started) {
            return back()->with('error', 'El torneo ya ha comenzado, no se pueden inscribir más equipos.');
        }

        if ($tournament->teams()->count() >= $tournament->max_teams) {
            return back()->with('error', 'El torneo está lleno.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tournament->teams()->create([
            'name' => $request->name
        ]);

        if ($tournament->teams()->count() === $tournament->max_teams) {
            $this->generateBracket($tournament);
            Mail::to($request->user())->send(new BracketGeneratedMail($tournament));
            return back()->with('success', 'Equipo registrado. ¡El torneo está lleno y el rol de juegos ha sido generado!');
        }

        return back()->with('success', 'Equipo registrado correctamente.');
    }

    private function generateBracket(Tournament $tournament)
    {
        $teams = $tournament->teams->shuffle();
        
        $totalTeams = $teams->count();
        $roundName = match($totalTeams) {
            16 => 'Octavos de Final',
            8 => 'Cuartos de Final',
            4 => 'Semifinal',
            2 => 'Final',
            default => 'Ronda 1'
        };

        for ($i = 0; $i < $totalTeams; $i += 2) {
            GameMatch::create([
                'tournament_id' => $tournament->id,
                'home_team_id' => $teams[$i]->id,
                'away_team_id' => $teams[$i+1]->id,
                'round' => $roundName,
            ]);
        }

        $tournament->update(['is_started' => true]);
    }

    public function downloadPdf(Tournament $tournament)
    {
        $tournament->load(['teams', 'matches.homeTeam', 'matches.awayTeam']);
        
        $pdf = Pdf::loadView('tournaments.pdf', compact('tournament'));
        
        return $pdf->download(''.$tournament->name.'.pdf');
    }
}