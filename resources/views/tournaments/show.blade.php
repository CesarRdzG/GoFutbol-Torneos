<x-app-layout>
    <x-slot name="header">
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Torneo: {{ $tournament->name }}
        </h2>
        
        @if($tournament->is_started)
            <a href="{{ route('tournaments.pdf', $tournament) }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Descargar Rol (PDF)
            </a>
        @endif
    </div>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="md:col-span-1 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Estado del Torneo</h3>
                        @if($tournament->is_started)
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">En Juego</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Inscripciones Abiertas</span>
                        @endif
                        <p class="mt-2 text-gray-600">Equipos: {{ $tournament->teams->count() }} / {{ $tournament->max_teams }}</p>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Equipos Inscritos</h3>
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            @forelse($tournament->teams as $team)
                                <li>{{ $team->name }}</li>
                            @empty
                                <li class="text-gray-400 italic">Aún no hay equipos inscritos.</li>
                            @endforelse
                        </ul>
                    </div>

                    @if(!$tournament->is_started)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-bold mb-4">Inscribir Equipo</h3>
                        
                        @if(session('success'))
                            <div class="mb-4 text-green-600 text-sm">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="mb-4 text-red-600 text-sm">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('tournaments.add-team', $tournament) }}" method="POST">
                            @csrf
                            <div>
                                <x-input-label for="name" :value="__('Nombre del Equipo')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" required/>
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div class="mt-4">
                                <x-primary-button class="w-full justify-center">
                                    {{ __('Registrar Equipo') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 min-h-[500px]">
                        <h3 class="text-lg font-bold mb-6 border-b pb-2">Cuadro del Torneo</h3>

                        @if(!$tournament->is_started)
                            <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p>El bracket se generará automáticamente cuando se inscriban {{ $tournament->max_teams }} equipos.</p>
                            </div>
                        @else
                            <div class="flex flex-col space-y-8">
                                @php
                                    $matchesByRound = $tournament->matches->groupBy('round');
                                    $roundsOrder = ['Octavos de Final', 'Cuartos de Final', 'Semifinal', 'Final', 'Ronda 1'];
                                @endphp

                                @foreach($roundsOrder as $roundName)
                                    @if(isset($matchesByRound[$roundName]))
                                        <div class="bracket-round">
                                            <h4 class="text-md font-semibold text-blue-600 mb-3 uppercase tracking-wider">{{ $roundName }}</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                @foreach($matchesByRound[$roundName] as $match)
                                                    <div class="border rounded-lg p-3 bg-gray-50 flex justify-between items-center relative">
                                                        <div class="flex flex-col w-full">
                                                            <div class="flex justify-between py-1 border-b border-gray-200 {{ $match->winner_id == $match->home_team_id ? 'font-bold text-green-700' : '' }}">
                                                                <span>{{ $match->homeTeam->name ?? 'TBD' }}</span>
                                                                </div>
                                                            <div class="flex justify-between py-1 {{ $match->winner_id == $match->away_team_id ? 'font-bold text-green-700' : '' }}">
                                                                <span>{{ $match->awayTeam->name ?? 'TBD' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>