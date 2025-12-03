<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="col-span-1 md:col-span-3 mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Mis Torneos Activos</h3>
                    <p class="text-sm text-gray-500">Selecciona un torneo para gestionar sus equipos y partidos.</p>
                </div>

                @forelse ($tournaments as $tournament)
                    <a href="{{ route('tournaments.show', $tournament) }}" class="block group">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition duration-200 border-l-4 {{ $tournament->is_started ? 'border-green-500' : 'border-yellow-500' }}">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800 group-hover:text-blue-600">{{ $tournament->name }}</h3>
                                    <div class="mt-2 flex flex-col text-sm text-gray-600">
                                        <span>🏆 Formato: {{ $tournament->max_teams }} Equipos</span>
                                    </div>
                                </div>
                                @if($tournament->is_started)
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">En Juego</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full font-bold">Inscripciones Abiertas</span>
                                @endif
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                                <span>Equipos: {{ $tournament->teams->count() }} / {{ $tournament->max_teams }}</span>
                                <span>Creado: {{ $tournament->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-1 md:col-span-3 text-center py-16 bg-white rounded-lg shadow-sm border border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay torneos</h3>
                        <p class="mt-1 text-sm text-gray-500">Comienza creando tu primer torneo de fútbol.</p>
                        <div class="mt-6">
                            <a href="{{ route('tournaments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Crear Torneo
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>