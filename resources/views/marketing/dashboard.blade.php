<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <a href="{{ url('/') }}" class="group flex items-center gap-2 text-gray-500 hover:text-white transition duration-200">
                    <div class="p-1 rounded-full group-hover:bg-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium hidden sm:block">Back to Home</span>
                </a>
                <div class="h-6 w-px bg-gray-700 hidden sm:block"></div>
                <h2 class="font-semibold text-xl text-cyan-400 leading-tight">
                    Marketing Command Center
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="flex justify-between items-end border-b border-gray-700 pb-4">
                <div>
                    <h3 class="text-2xl font-bold text-white">
                        @if(request('search'))
                            Search Results for "<span class="text-yellow-500">{{ request('search') }}</span>"
                        @else
                            Performance Overview
                        @endif
                    </h3>
                    <p class="text-gray-400 text-sm mt-1">Real-time market insights and campaign tracking.</p>
                </div>
                
                <form action="{{ url()->current() }}" method="GET" class="flex gap-2">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search movie/show..." 
                            class="bg-gray-900 text-white border border-gray-700 rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-cyan-500 outline-none w-64 text-sm"
                        >
                        <svg class="w-4 h-4 text-gray-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold px-4 py-2 rounded-lg transition text-sm">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ url()->current() }}" class="bg-gray-700 text-white px-3 py-2 rounded-lg hover:bg-gray-600 text-sm">Clear</a>
                    @endif
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                    <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-cyan-400">Trending Now</h4>
                        <span class="text-xs bg-gray-700 text-white px-2 py-1 rounded">Top Vote Counts</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-300">
                            <thead class="bg-gray-900 text-gray-400 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Title</th>
                                    <th class="px-4 py-3 text-center">Votes</th>
                                    <th class="px-4 py-3 text-center">Rating</th>
                                    <th class="px-4 py-3 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($trending as $item)
                                <tr class="hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3 font-medium text-white truncate max-w-[150px]" title="{{ $item->show_name }}">
                                        {{ $item->show_name }}
                                        <div class="text-xs text-gray-500">{{ $item->genre_name }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ number_format($item->vote_count) }}</td>
                                    <td class="px-4 py-3 text-center text-yellow-500 font-bold">{{ number_format($item->rating, 1) }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="#" class="text-cyan-400 hover:text-cyan-300">Analyze</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-500">No trending data found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                    <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-purple-400">Content Tiers</h4>
                        <span class="text-xs bg-gray-700 text-white px-2 py-1 rounded">Quality Matrix</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-300">
                            <thead class="bg-gray-900 text-gray-400 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3">Title</th>
                                    <th class="px-4 py-3">Tier</th>
                                    <th class="px-4 py-3 text-center">Quality</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                @forelse($tier as $item)
                                <tr class="hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3 font-medium text-white truncate max-w-[150px]">{{ $item->show_name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded text-xs font-bold 
                                            {{ $item->content_tier == 'S-Tier' ? 'bg-yellow-500/20 text-yellow-500 border border-yellow-500/50' : 
                                              ($item->content_tier == 'A-Tier' ? 'bg-green-500/20 text-green-500 border border-green-500/50' : 'bg-gray-700 text-gray-300') }}">
                                            {{ $item->content_tier }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-center">{{ $item->quality_category }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-gray-500">No data available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <div class="p-6 border-b border-gray-700">
                    <h4 class="text-lg font-bold text-red-400">High Priority Campaigns</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300">
                        <thead class="bg-gray-900 text-gray-400 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Campaign Title</th>
                                <th class="px-6 py-3">Priority</th>
                                <th class="px-6 py-3">Strategy</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($priority as $item)
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 font-bold text-white">{{ $item->show_name }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-red-400 font-bold flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z"/></svg>
                                        {{ $item->campaign_priority }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $item->recommended_strategy }}</td>
                                <td class="px-6 py-4">
                                    <span class="bg-green-900 text-green-300 px-2 py-1 rounded text-xs">{{ $item->status_name }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded text-xs">Launch</button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No high priority campaigns found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($insights->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($insights as $insight)
                <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
                    <h5 class="text-gray-400 text-xs uppercase font-bold">{{ $insight->genre_name }}</h5>
                    <div class="text-2xl font-bold text-white my-2">{{ number_format($insight->avg_popularity) }} <span class="text-xs text-gray-500 font-normal">Avg Pop</span></div>
                    <div class="text-xs text-cyan-400 mt-2">{{ $insight->targeting_recommendation }}</div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>
</x-app-layout>