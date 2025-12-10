<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-amber-500 leading-tight flex items-center gap-2">
            Executive Command Center
        </h2>
    </x-slot>

    <div class="py-12 bg-black min-h-screen text-gray-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($kpi)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
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
                    <h3 class="text-lg font-bold text-white">Highest ROI</h3>
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

        </div>
    </div>
</x-app-layout>