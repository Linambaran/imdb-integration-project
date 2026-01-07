<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-amber-500 leading-tight">
                Review Content: {{ $movie->primaryTitle }}
            </h2>
            <a href="{{ route('executive.dashboard') }}" class="text-gray-400 hover:text-white text-sm">
                &larr; Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-[#0f1014] min-h-screen text-gray-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-gray-800 rounded-lg shadow-2xl overflow-hidden border border-gray-700 flex flex-col md:flex-row">
                
                <div class="w-full md:w-1/3 bg-black p-6 flex flex-col items-center justify-center border-r border-gray-700">
                    <div class="w-64 h-96 bg-gray-700 rounded-lg flex items-center justify-center mb-6 overflow-hidden relative">
                        @if(!empty($movie->poster_url))
                            <img src="{{ $movie->poster_url }}" alt="Poster" class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-500 font-bold">NO POSTER</span>
                        @endif

                        <div class="absolute top-2 right-2 px-3 py-1 rounded font-bold text-xs uppercase shadow-lg
                            {{ $movie->id_status == 2 ? 'bg-green-600 text-white' : ($movie->id_status == 3 ? 'bg-red-600 text-white' : 'bg-yellow-500 text-black') }}">
                            {{ $movie->id_status == 2 ? 'Approved' : ($movie->id_status == 3 ? 'Rejected' : 'Pending') }}
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 w-full text-center">
                        <div class="bg-gray-900 p-3 rounded border border-gray-700">
                            <div class="text-2xl font-bold text-amber-500">★ {{ number_format($movie->averageRating, 1) ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">IMDb Rating</div>
                        </div>
                        <div class="bg-gray-900 p-3 rounded border border-gray-700">
                            <div class="text-xl font-bold text-blue-400">{{ number_format($movie->numVotes ?? 0) }}</div>
                            <div class="text-xs text-gray-500">Total Votes</div>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-2/3 p-8 flex flex-col justify-between">
                    
                    <div>
                        <h1 class="text-4xl font-black text-white mb-2">{{ $movie->primaryTitle }} <span class="text-gray-500 text-2xl">({{ $movie->startYear }})</span></h1>
                        <p class="text-gray-400 text-sm mb-6 italic">{{ $movie->originalTitle }}</p>

                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach(explode(',', $movie->genre) as $genre)
                                <span class="px-3 py-1 bg-gray-700 text-gray-300 rounded-full text-xs font-bold">{{ $genre }}</span>
                            @endforeach
                            <span class="px-3 py-1 bg-gray-700 text-gray-300 rounded-full text-xs">{{ $movie->runtimeMinutes }} mins</span>
                        </div>

                        <h3 class="text-amber-500 font-bold uppercase text-sm mb-2">Synopsis</h3>
                        <p class="text-gray-300 leading-relaxed mb-8">
                            {{ $movie->plot_description ?? 'No plot description available for this title.' }}
                        </p>
                    </div>

                    <div class="border-t border-gray-700 pt-6">
                        <h3 class="text-gray-400 text-sm font-bold uppercase mb-4">Executive Decision</h3>
                        
                        <div class="flex gap-4">
                            <form action="{{ route('executive.approval', $movie->tconst) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="2">
                                <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-500 text-white font-bold rounded shadow-lg transition flex items-center justify-center gap-2 {{ $movie->id_status == 2 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $movie->id_status == 2 ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    APPROVE FOR BROADCAST
                                </button>
                            </form>

                            <form action="{{ route('executive.approval', $movie->tconst) }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="status" value="3">
                                <button type="submit" class="w-full py-4 bg-red-600 hover:bg-red-500 text-white font-bold rounded shadow-lg transition flex items-center justify-center gap-2 {{ $movie->id_status == 3 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $movie->id_status == 3 ? 'disabled' : '' }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                    REJECT CONTENT
                                </button>
                            </form>
                        </div>
                        <p class="text-xs text-gray-500 mt-4 text-center">
                            *Approving allows this content to be viewed by Native Users. Rejecting hides it from the public catalog.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>