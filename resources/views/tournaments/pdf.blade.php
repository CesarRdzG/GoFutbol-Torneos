<!DOCTYPE html>
<html>
<head>
    <title>Reporte del Torneo</title>
    <style>
        body { font-family: sans-serif; }
        h1, h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { bg-color: #f2f2f2; }
        .winner { font-weight: bold; color: green; }
    </style>
</head>
<body>
    <h1>{{ $tournament->name }}</h1>
    <p style="text-align: center;"><strong>Formato:</strong> {{ $tournament->max_teams }} Equipos | <strong>Estado:</strong> {{ $tournament->is_started ? 'En curso' : 'Inscripciones' }}</p>

    <h3>Equipos Participantes</h3>
    <ul>
        @foreach($tournament->teams as $team)
            <li>{{ $team->name }}</li>
        @endforeach
    </ul>

    <h3>Rol de Juegos</h3>
    @if($tournament->matches->isEmpty())
        <p>El rol de juegos aún no se ha generado.</p>
    @else
        @php
            $matchesByRound = $tournament->matches->groupBy('round');
            $roundsOrder = ['Octavos de Final', 'Cuartos de Final', 'Semifinal', 'Final'];
        @endphp

        @foreach($roundsOrder as $round)
            @if(isset($matchesByRound[$round]))
                <div style="page-break-inside: avoid;">
                    <h4>{{ $round }}</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Local</th>
                                <th>vs</th>
                                <th>Visitante</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matchesByRound[$round] as $match)
                                <tr>
                                    <td class="{{ $match->winner_id == $match->home_team_id ? 'winner' : '' }}">
                                        {{ $match->homeTeam->name ?? 'TBD' }}
                                    </td>
                                    <td>vs</td>
                                    <td class="{{ $match->winner_id == $match->away_team_id ? 'winner' : '' }}">
                                        {{ $match->awayTeam->name ?? 'TBD' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach
    @endif
</body>
</html>