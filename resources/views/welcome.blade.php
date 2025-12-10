<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>L0224022 - DBMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-[#0f1014] text-gray-100 font-sans antialiased">
    
    <div class="fixed top-0 left-0 w-full z-50 bg-gradient-to-b from-black/95 to-black/60 backdrop-blur-sm p-4 md:px-12 flex flex-col md:flex-row justify-between items-center transition-all duration-300 border-b border-white/5">
        
        <div class="flex items-center justify-between w-full md:w-auto mb-4 md:mb-0 gap-8">
            <a href="/" class="text-3xl font-black text-yellow-500 tracking-tighter cursor-pointer">
                IMDB<span class="text-white">TV</span>
            </a>

            <div class="relative w-64 lg:w-96 hidden md:block" id="search-container">
    
                <form action="/" method="GET" class="flex items-center bg-gray-800 rounded-full px-4 py-1.5 border border-gray-700 focus-within:border-yellow-500 transition w-full">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    
                    <input 
                        type="text" 
                        id="search-input" 
                        name="search" 
                        placeholder="Search titles, genres..." 
                        class="bg-transparent border-none focus:ring-0 text-sm text-white w-full placeholder-gray-500" 
                        value="{{ request('search') }}"
                        autocomplete="off"
                    >
                </form>

                <div 
                    id="search-results" 
                    class="absolute top-full left-0 w-full bg-gray-900 border border-gray-700 rounded-lg shadow-2xl mt-2 hidden z-50 overflow-hidden">
                </div>

             </div>
        </div>
        <div class="flex items-center gap-4">
            @if (Route::has('login'))
                @auth
                <img src="https://media1.tenor.com/m/PwQJPEN-ZZQAAAAd/marisa%27s-butt-touhou.gif" class="w-10 h-10">
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden lg:block">
                            <div class="text-gray-200 text-sm font-bold">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500 uppercase">{{ Auth::user()->role }}</div>
                        </div>
                        
                        @if(Auth::user()->role == 'marketing')
                            <a href="{{ route('marketing.dashboard') }}" class="px-3 py-1 bg-blue-600/20 text-blue-400 border border-blue-600/50 rounded text-xs font-bold hover:bg-blue-600 hover:text-white transition">Marketing</a>
                        @elseif(Auth::user()->role == 'executive')
                            <a href="{{ route('executive.dashboard') }}" class="px-3 py-1 bg-amber-600/20 text-amber-400 border border-amber-600/50 rounded text-xs font-bold hover:bg-amber-600 hover:text-white transition">Executive</a>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="p-2 bg-gray-800 rounded-full text-red-500 hover:bg-red-600 hover:text-white transition" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white font-medium transition text-sm">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-yellow-500 text-black rounded font-bold hover:bg-yellow-400 transition text-sm">Sign Up</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

    <div class="pt-32 md:pt-24 pb-10 px-6 md:px-12 min-h-screen">

        @if(isset($search_results))
            
            <div class="animate-fade-in-up">
                <h3 class="text-2xl font-bold mb-6 text-white flex items-center gap-2">
                    🔍 Search Results for: <span class="text-yellow-500">"{{ $search_query }}"</span>
                </h3>

                @if(count($search_results) > 0)
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                        @foreach($search_results as $item)
                        <div class="bg-[#1a1c24] rounded-lg overflow-hidden shadow-lg border border-gray-800 hover:scale-105 transition duration-300 group cursor-pointer">
                            <div class="h-64 bg-gradient-to-t from-black via-gray-800 to-gray-700 flex items-center justify-center p-4 relative">
                                <span class="text-center font-bold text-gray-400 group-hover:text-white transition">{{ $item->show_name }}</span>
                            </div>
                            <div class="p-3">
                                <div class="text-sm font-bold text-white truncate">{{ $item->show_name }}</div>
                                <div class="flex justify-between items-center mt-2 text-xs text-gray-400">
                                    <span>{{ $item->startYear ?? 'N/A' }}</span>
                                    <span class="text-green-400">{{ $item->rating ?? '-' }} ★</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-20 text-gray-500">
                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-xl">No results found for "{{ $search_query }}"</p>
                        <a href="/" class="mt-4 text-yellow-500 hover:underline">Back to Home</a>
                    </div>
                @endif
            </div>

        @else

            <div class="mb-12">
                <h3 class="text-2xl font-bold mb-6 text-white flex items-center gap-3">
                    <span class="w-1 h-8 bg-yellow-500 rounded-full"></span> Featured Catalog
                </h3>
                <div class="flex overflow-x-auto pb-8 gap-5 scrollbar-hide snap-x behavior-smooth">
                    @foreach($katalog as $item)
                    <div class="min-w-[200px] md:min-w-[240px] bg-[#1a1c24] rounded-xl overflow-hidden shadow-xl border border-gray-800 hover:scale-105 transition duration-300 snap-start relative group cursor-pointer">
                        <div class="h-80 w-full bg-gradient-to-br from-gray-800 to-black flex items-center justify-center p-4 relative">
                            <span class="text-center font-bold text-gray-500 text-lg group-hover:text-white transition px-2">{{ $item->show_name }}</span>
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                <button class="bg-yellow-500 text-black rounded-full w-12 h-12 flex items-center justify-center shadow-lg hover:scale-110 transition">▶</button>
                            </div>
                        </div>
                        <div class="p-4 bg-[#1a1c24] absolute bottom-0 w-full">
                            <h4 class="font-bold text-white truncate">{{ $item->show_name }}</h4>
                            <div class="flex justify-between items-center text-xs text-gray-400 mt-1">
                                <span class="text-green-400 font-bold">{{ $item->rating ?? '-' }} ★</span>
                                <span>{{ $item->startYear ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 pb-20">
                <div>
                    <h3 class="text-xl font-bold text-white mb-6 border-l-4 border-red-600 pl-3">Top 10 Popular</h3>
                    <div class="flex flex-col gap-3">
                        @foreach($popular as $item)
                        <div class="flex items-center gap-4 p-2 hover:bg-[#1f2129] rounded transition group cursor-pointer">
                            <span class="text-4xl font-black text-gray-800 group-hover:text-red-600 w-8 text-center">{{ $loop->iteration }}</span>
                            <div>
                                <div class="font-bold text-gray-200 group-hover:text-white">{{ $item->show_name }}</div>
                                <div class="text-xs text-gray-500">{{ number_format($item->popularity) }} views</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-white mb-6 border-l-4 border-blue-500 pl-3">New Releases</h3>
                    <div class="space-y-3">
                        @foreach($new_releases as $item)
                        <div class="bg-[#1f2129] p-3 rounded flex justify-between items-center border border-transparent hover:border-blue-500/30 cursor-pointer">
                            <div>
                                <div class="text-sm font-bold text-white">{{ $item->show_name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->genre_name }}</div>
                            </div>
                            <span class="text-[10px] bg-blue-900 text-blue-200 px-2 py-1 rounded font-bold">NEW</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-white mb-6 border-l-4 border-yellow-500 pl-3">Critically Acclaimed</h3>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($top_rated as $item)
                        <div class="bg-[#1f2129] p-3 rounded flex justify-between items-center hover:bg-[#2a2d36] transition cursor-pointer">
                            <div class="text-sm font-medium text-gray-300 w-3/4 truncate">{{ $item->show_name }}</div>
                            <div class="text-yellow-500 font-bold text-sm">★ {{ number_format($item->rating, 1) }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        @endif </div>
<script>
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    let timeout = null;

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value;
            clearTimeout(timeout);

            if (query.length < 2) {
                searchResults.innerHTML = '';
                searchResults.classList.add('hidden');
                return;
            }

            timeout = setTimeout(() => {
                fetch(`/search-preview?q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        let html = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                html += `
                                    <a href="/?search=${item.show_name}" class="block p-3 hover:bg-gray-800 border-b border-gray-800 last:border-0 transition flex items-center gap-3">
                                        <div class="w-8 h-10 bg-gray-700 rounded flex items-center justify-center text-xs text-gray-500 font-bold">IMG</div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-200">${item.show_name}</div>
                                            <div class="text-xs text-gray-500">${item.startYear} • <span class="text-yellow-500">★ ${Number(item.rating).toFixed(1)}</span></div>
                                        </div>
                                    </a>`;
                            });
                        } else {
                            html = `<div class="p-4 text-sm text-gray-500 text-center">No results found</div>`;
                        }
                        searchResults.innerHTML = html;
                        searchResults.classList.remove('hidden');
                    });
            }, 300);
        });

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    }
</script>
</body>
</html>