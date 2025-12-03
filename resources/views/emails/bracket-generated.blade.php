<x-mail::message>
# ¡El Torneo {{ $tournament->name }} está completo!

Se han inscrito los {{ $tournament->max_teams }} equipos y el sistema ha generado los cruces automáticamente.

A continuación, te presentamos los primeros enfrentamientos:

@php
    $matches = $tournament->matches->where('round', '!=', null); // Obtener partidos
@endphp

<x-mail::table>
| Local       | vs  | Visitante    | Ronda           |
| :---------- | :-: | :----------- | :-------------- |
@foreach($matches as $match)
| **{{ $match->homeTeam->name }}** | vs | **{{ $match->awayTeam->name }}** | {{ $match->round }} |
@endforeach
</x-mail::table>

<x-mail::button :url="route('tournaments.show', $tournament)">
Ver Torneo Completo
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>