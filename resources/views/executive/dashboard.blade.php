<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-amber-500 leading-tight flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Executive Command Center
            </h2>
            <span class="text-xs text-gray-400 font-mono">System Status: ONLINE</span>
        </div>
    </x-slot>

    <div class="py-12 bg-black min-h-screen text-gray-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div>
                <div class="flex justify-between items-end border-b border-gray-800 pb-4 mb-4">
                    <div>
                        <h3 class="text-2xl font-bold text-white">
                            @if(request('search'))
                                Search Results: "<span class="text-amber-500">{{ request('search') }}</span>"
                            @else
                                Global Content Database
                            @endif
                        </h3>
                        <p class="text-xs text-gray-500 mt-1">Real-time access to content performance metrics.</p>
                    </div>
                    
                    <form action="{{ url()->current() }}" method="GET" class="flex gap-2">
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search content portfolio..." 
                            class="bg-gray-900 text-white border border-gray-700 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 outline-none w-64 text-sm font-mono"
                        >
                        <button type="submit" class="bg-amber-600 hover:bg-amber-500 text-white font-bold px-4 py-2 rounded-lg transition text-sm">
                            Search
                        </button>
                        
                        @if(request('search'))
                            <a href="{{ url()->current() }}" class="bg-gray-800 text-gray-400 px-4 py-2 rounded-lg hover:bg-gray-700 text-sm border border-gray-700">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                @if(request('search'))
                <div class="bg-gray-900 rounded-lg overflow-hidden border border-gray-800 mb-8 animate-fade-in-up">
                    <table class="w-full text-left text-gray-300 text-sm">
                        <thead class="bg-gray-800 text-white uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">Title</th>
                                <th class="px-6 py-3">Year</th>
                                <th class="px-6 py-3">Rating</th>
                                <th class="px-6 py-3">Popularity</th>
                                <th class="px-6 py-3 text-right">Deep Dive</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($items as $item)
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 font-bold text-white">{{ $item->show_name }}</td>
                                <td class="px-6 py-4 font-mono text-gray-400">{{ $item->startYear }}</td>
                                <td class="px-6 py-4 text-amber-500 font-bold">★ {{ number_format($item->rating, 1) }}</td>
                                <td class="px-6 py-4 font-mono">{{ number_format($item->popularity) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('executive.show', $item->tconst) }}" class="text-amber-500 hover:text-amber-400 hover:underline">Analyze</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    No assets found matching "{{ request('search') }}"
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-4 border-t border-gray-800">
                        {{ $items->links() }}
                    </div>
                </div>
                @endif
            </div>

            @if(!request('search') && $kpi)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <svg class="w-16 h-16 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/></svg>
                    </div>
                    <p class="text-gray-500 text-xs uppercase tracking-widest font-bold">Total Content Library</p>
                    <h3 class="text-3xl font-extrabold text-white mt-1">{{ number_format($kpi->total_shows) }}</h3>
                    <p class="text-xs text-amber-500 mt-2">Active: {{ number_format($kpi->active_shows) }}</p>
                </div>

                <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl shadow-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-widest font-bold">In Production</p>
                    <h3 class="text-3xl font-extrabold text-white mt-1">{{ number_format($kpi->in_production_count) }}</h3>
                    <div class="flex items-center gap-2 mt-2 text-xs">
                        <span class="text-gray-500">- {{ $kpi->shows_this_year }} New Release(s) This Year</span>
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl shadow-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-widest font-bold">Avg Quality Rating</p>
                    <h3 class="text-3xl font-extrabold text-white mt-1">{{ number_format($kpi->avg_rating, 2) }} <span class="text-sm text-gray-500">/ 10</span></h3>
                    <p class="text-xs text-gray-500 mt-2">Global Average</p>
                </div>

                <div class="bg-gray-900 border border-gray-800 p-6 rounded-xl shadow-lg">
                    <p class="text-gray-500 text-xs uppercase tracking-widest font-bold">Avg Popularity Index</p>
                    <h3 class="text-3xl font-extrabold text-amber-400 mt-1">{{ number_format($kpi->avg_popularity, 1) }}</h3>
                    <p class="text-xs text-gray-500 mt-2">Engagement Score</p>
                </div>
            </div>
            @endif

            @if(!request('search'))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2">Yearly Growth Performance</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-gray-500 text-xs uppercase">
                                <tr>
                                    <th class="py-2">Year</th>
                                    <th class="py-2 text-right">New Shows</th>
                                    <th class="py-2 text-right">Growth</th>
                                    <th class="py-2 text-right">Pop. Change</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @foreach($trends as $row)
                                <tr class="hover:bg-gray-800/50">
                                    <td class="py-3 font-mono text-amber-500">{{ $row->startYear }}</td>
                                    <td class="py-3 text-right text-white">{{ number_format($row->new_shows) }}</td>
                                    <td class="py-3 text-right font-bold {{ $row->show_growth >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        {{ $row->show_growth > 0 ? '+' : '' }}{{ number_format($row->show_growth) }}
                                    </td>
                                    <td class="py-3 text-right {{ $row->popularity_change >= 0 ? 'text-green-500' : 'text-red-500' }}">
                                        {{ number_format($row->popularity_change, 1) }}%
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 shadow-lg">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2">Top Genres by ROI Estimate</h3>
                    <div class="space-y-4">
                        @foreach($genres as $row)
                        <div>
                            <div class="flex justify-between items-end mb-1">
                                <span class="text-white font-medium">{{ $row->genre_name }}</span>
                                <span class="text-amber-400 font-mono text-sm">ROI: {{ number_format($row->roi_estimate, 1) }}x</span>
                            </div>
                            <div class="w-full bg-gray-800 rounded-full h-2">
                                <div class="bg-gradient-to-r from-amber-600 to-amber-400 h-2 rounded-full" 
                                     style="width: {{ min(($row->roi_estimate / 35) * 100, 100) }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>Vol: {{ number_format($row->show_count) }}</span>
                                <span>Quality Score: {{ number_format($row->content_quality_score, 1) }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 shadow-lg">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-white">Highest ROI Assets</h3>
                    <span class="bg-amber-900/30 text-amber-500 text-xs px-2 py-1 rounded border border-amber-500/30">Strategic Priority</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="bg-gray-800 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="p-3 rounded-l-lg">Show Name</th>
                                <th class="p-3">Network</th>
                                <th class="p-3">Genre</th>
                                <th class="p-3 text-right">Rating</th>
                                <th class="p-3 text-right">Market Perf.</th>
                                <th class="p-3 text-right rounded-r-lg">ROI Indicator</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @foreach($assets as $row)
                            <tr class="hover:bg-gray-800/50 transition group">
                                <td class="p-3 font-bold text-white group-hover:text-amber-400 transition">
                                    {{ $row->show_name }}
                                    <span class="block text-xs text-gray-600 font-normal">{{ $row->production_status ?? 'Unknown' }}</span>
                                </td>
                                <td class="p-3">{{ $row->network_name }}</td>
                                <td class="p-3">
                                    <span class="px-2 py-1 bg-gray-800 rounded text-xs border border-gray-700">{{ $row->genre_name }}</span>
                                </td>
                                <td class="p-3 text-right">
                                    <span class="text-white font-bold">{{number_format($row->rating, 1)}}</span>
                                </td>
                                <td class="p-3 text-right font-mono text-gray-300">
                                    {{ number_format($row->market_performance_index, 0) }}
                                </td>
                                <td class="p-3 text-right font-mono text-amber-400 font-bold">
                                    {{ number_format($row->roi_indicator, 0) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-gray-900 border border-gray-800 rounded-xl p-6 shadow-lg">
                    <div class="flex justify-between items-center mb-4 border-b border-gray-800 pb-2">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="relative flex h-3 w-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                            Pending Content Reviews
                        </h3>
                        <span class="text-xs text-gray-500">Action Required</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-gray-500 text-xs uppercase bg-gray-800/50 rounded">
                                <tr>
                                    <th class="px-4 py-3 rounded-l">Title</th>
                                    <th class="px-4 py-3">Year</th>
                                    <th class="px-4 py-3 text-right">Rating</th>
                                    <th class="px-4 py-3 text-right rounded-r">Decision</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse($approvalList as $item)
                                <tr class="hover:bg-gray-800/50 transition group">
                                    <td class="px-4 py-3 font-bold text-white">
                                        <a href="{{ route('executive.show', $item->tconst) }}" class="group-hover:text-amber-400 group-hover:underline transition">
                                            {{ $item->primaryTitle }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3 text-gray-400 font-mono">{{ $item->startYear }}</td>
                                    <td class="px-4 py-3 text-right font-mono text-amber-400 font-bold">
                                        {{ number_format($item->averageRating, 1) }}
                                    </td>
                                    <td class="px-4 py-3 text-right flex justify-end gap-2">
                                        <form action="{{ route('executive.approval', $item->tconst) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="2">
                                            <button type="submit" class="px-3 py-1 bg-green-900/20 text-green-400 border border-green-900/50 hover:bg-green-600 hover:text-white rounded text-xs font-bold transition shadow-sm">
                                                Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('executive.approval', $item->tconst) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="3">
                                            <button type="submit" class="px-3 py-1 bg-red-900/20 text-red-400 border border-red-900/50 hover:bg-red-600 hover:text-white rounded text-xs font-bold transition shadow-sm">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-gray-500 italic">
                                        No pending reviews at this moment.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 shadow-lg h-fit">
                    <h3 class="text-lg font-bold text-white mb-4 border-b border-gray-800 pb-2 flex justify-between items-center">
                        Audit Logs
                        <span class="text-[10px] bg-gray-800 px-2 py-0.5 rounded text-gray-400 border border-gray-700">Recent</span>
                    </h3>
                    <div class="space-y-4">
                        @foreach($systemLogs as $log)
                        <div class="flex items-start gap-3 text-xs border-b border-gray-800 pb-3 last:border-0 last:pb-0 group">
                            <div class="mt-1.5 w-2 h-2 rounded-full flex-shrink-0 {{ $log->success ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]' : 'bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.4)]' }}"></div>
                            <div class="w-full">
                                <div class="flex justify-between">
                                    <span class="text-gray-200 font-bold group-hover:text-white transition">{{ $log->username }}</span>
                                    <span class="text-gray-600 font-mono text-[10px]">{{ \Carbon\Carbon::parse($log->access_time)->diffForHumans() }}</span>
                                </div>
                                <div class="text-gray-500 mt-0.5">
                                    {{ $log->action_type }} on <span class="text-amber-500/90 font-medium">{{ $log->table_accessed }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>