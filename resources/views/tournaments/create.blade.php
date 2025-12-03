<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Crear Nuevo Torneo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('tournaments.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Nombre del Torneo')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="max_teams" :value="__('Formato (Cantidad de Equipos)')" />
                        <select id="max_teams" name="max_teams" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="4">4 Equipos (Semifinal y Final)</option>
                            <option value="8">8 Equipos (Cuartos, Semifinal y Final)</option>
                            <option value="16">16 Equipos (Octavos a Final)</option>
                        </select>
                        <x-input-error :messages="$errors->get('max_teams')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 underline text-sm mr-4">Cancelar</a>
                        <x-primary-button>
                            {{ __('Crear Torneo') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>