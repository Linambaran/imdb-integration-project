<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $movie->imdb_title ?? $movie->show_name }} - IMDB TV</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0f1014] text-gray-100 font-sans antialiased">

    <div class="fixed top-0 left-0 w-full z-50 p-6 bg-gradient-to-b from-black/90 to-transparent">
        <a href="/" class="flex items-center gap-2 text-gray-300 hover:text-yellow-500 transition w-fit">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="font-bold tracking-wide">BACK TO BROWSE</span>
        </a>
    </div>

    <div class="relative w-full min-h-screen flex items-center">
        
        <div class="absolute inset-0 z-0 bg-[#0f1014]">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-black to-[#0f1014]"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f1014] via-[#0f1014]/80 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 w-full grid grid-cols-1 md:grid-cols-3 gap-12 items-end pb-12 pt-24">
            
            <div class="hidden md:block">
                <div class="rounded-xl overflow-hidden shadow-[0_0_50px_rgba(255,255,255,0.05)] border border-gray-800 transform rotate-[-2deg] bg-gray-900 aspect-[2/3] flex flex-col items-center justify-center p-6 text-center relative group">
                    
                    <svg class="absolute text-gray-800 w-32 h-32 opacity-20" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>

                    <h2 class="text-3xl font-black text-gray-700 uppercase leading-none relative z-10 break-words w-full">
                        {{ $movie->imdb_title ?? $movie->show_name }}
                    </h2>
                    
                    <div class="mt-4 w-12 h-1 bg-yellow-500 mx-auto rounded relative z-10"></div>
                </div>
            </div>

            <div class="md:col-span-2 space-y-6">
                
                <div>
                    <h1 class="text-5xl md:text-7xl font-black text-white leading-tight mb-2">
                        {{ $movie->imdb_title ?? $movie->show_name }}
                    </h1>
                    
                    @if($movie->originalTitle && $movie->originalTitle != $movie->imdb_title)
                        <p class="text-gray-400 text-lg italic mb-2">Original Title: "{{ $movie->originalTitle }}"</p>
                    @endif

                    <div class="flex flex-wrap items-center gap-4 text-sm md:text-base text-gray-400 font-medium">
                        <span class="text-white">{{ $movie->startYear }}</span>
                        <span class="w-1.5 h-1.5 bg-gray-600 rounded-full"></span>
                        <span>{{ $movie->runtimeMinutes ?? 'N/A' }} min</span>
                        <span class="w-1.5 h-1.5 bg-gray-600 rounded-full"></span>
                        <span class="border border-gray-600 px-2 py-0.5 rounded text-xs">HD</span>
                        <span class="w-1.5 h-1.5 bg-gray-600 rounded-full"></span>
                        <span class="text-blue-400 font-bold">{{ number_format($movie->numVotes ?? 0) }} Votes</span>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-2 bg-yellow-500/10 px-4 py-2 rounded-lg border border-yellow-500/20">
                        <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <div>
                            <span class="text-xl font-bold text-white">{{ $movie->rating ?? 'N/A' }}</span>
                            <span class="text-xs text-gray-500">/ 10</span>
                        </div>
                    </div>

                    <div class="flex gap-2 flex-wrap">
                        @if($movie->genre_name)
                            @foreach(explode(', ', $movie->genre_name) as $genre)
                                <a href="/?search={{ $genre }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-full text-sm font-semibold transition border border-gray-700">
                                    {{ $genre }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 pt-4">
                    <button class="px-8 py-3 bg-yellow-500 hover:bg-yellow-400 text-black font-bold text-lg rounded-full flex items-center gap-2 transition hover:scale-105 shadow-[0_0_20px_rgba(234,179,8,0.4)]">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                        Play Now
                    </button>
                    <button class="px-8 py-3 bg-gray-800/80 hover:bg-gray-700 text-white font-bold text-lg rounded-full flex items-center gap-2 transition border border-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        My List
                    </button>
                </div>

                <div class="pt-6 border-t border-gray-800">
                    <h3 class="text-gray-400 font-bold uppercase text-sm mb-2 tracking-widest">Plot Overview</h3>
                    <p class="text-gray-200 text-lg leading-relaxed max-w-3xl">
                        {{ $movie->overview ?? 'No synopsis available for this title yet.' }}
                    </p>
                </div>

            </div>
        </div>
    </div>

</body>
</html>