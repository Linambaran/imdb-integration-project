<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-cyan-400 leading-tight flex items-center gap-2">
            Marketing Command Center
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="bg-gray-800 border border-gray-700 shadow-lg rounded-xl overflow-hidden">
                    <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                        <h3 class="font-bold text-cyan-400 flex items-center gap-2">
                            Trending Now
                        </h3>
                        <span class="text-xs text-gray-500">Top Votes</span>
                    </div>
                    <table class="w-full text-sm text-gray-300">
                        <tbody class="divide-y divide-gray-700">
                            @forelse($trending as $row)
                            <tr class="hover:bg-gray-700/50 transition group">
                                <td class="p-4">
                                    <div class="font-bold text-white group-hover:text-cyan-300 transition">{{ $row->show_name }}</div>
                                    <div class="text-xs text-gray-500 mt-1 flex gap-2">
                                        <span class="bg-gray-700 px-1.5 rounded">{{ $row->network_name ?? 'N/A' }}</span>
                                        <span>{{ $row->genre_name }}</span>
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end gap-1 text-yellow-400 font-bold">
                                        <span>★</span> {{ number_format($row->rating, 1) }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ number_format($row->vote_count) }} votes</div>
                                </td>
                            </tr>
                            @empty
                            <tr><td class="p-4 text-center text-gray-500">No Data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-800 border border-gray-700 shadow-lg rounded-xl overflow-hidden">
                    <div class="p-5 border-b border-gray-700 flex justify-between items-center">
                        <h3 class="font-bold text-rose-400 flex items-center gap-2">
                            Campaign Priority
                        </h3>
                        <span class="text-xs bg-rose-500/10 text-rose-400 px-2 py-1 rounded border border-rose-500/20">Action Required</span>
                    </div>
                    <div class="p-5 space-y-4">
                        @forelse($priority as $row)
                        <div class="flex flex-col bg-gray-900/50 p-3 rounded-lg border-l-4 {{ $row->campaign_priority == 'Premium Priority' ? 'border-rose-500' : 'border-amber-500' }}">
                            <div class="flex justify-between items-start">
                                <span class="font-bold text-gray-200">{{ $row->show_name }}</span>
                                <span class="text-[10px] uppercase tracking-wider font-bold {{ $row->campaign_priority == 'Premium Priority' ? 'text-rose-400' : 'text-amber-400' }}">
                                    {{ $row->campaign_priority }}
                                </span>
                            </div>
                            <div class="mt-2 text-xs text-gray-400">
                                <span class="text-gray-500">Strategy:</span> 
                                <span class="text-gray-300 italic">{{ $row->recommended_strategy ?? 'General Promotion' }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-gray-500 text-center">No Priority Campaigns</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 shadow-lg rounded-xl overflow-hidden">
                    <div class="p-5 border-b border-gray-700">
                        <h3 class="font-bold text-emerald-400 flex items-center gap-2">
                            Content Quality Tiers
                        </h3>
                    </div>
                    <div class="p-5 grid grid-cols-1 gap-3">
                        @forelse($tier as $row)
                        <div class="flex items-center justify-between p-2 rounded hover:bg-gray-700/30 transition">
                            <span class="text-gray-300">{{ $row->show_name }}</span>
                            <div class="flex items-center gap-2">
                                @if($row->quality_category == 'Good Quality')
                                    <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 text-xs rounded border border-emerald-500/30">Good</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-600/20 text-gray-400 text-xs rounded border border-gray-600/30">Avg</span>
                                @endif
                                
                                <span class="text-xs font-mono text-gray-500">{{ $row->content_tier }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="text-gray-500 text-center">No Data</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-800 border border-gray-700 shadow-lg rounded-xl overflow-hidden">
                    <div class="p-5 border-b border-gray-700">
                        <h3 class="font-bold text-violet-400 flex items-center gap-2">
                            Genre Market Insights
                        </h3>
                    </div>
                    <div class="p-5 flex flex-wrap gap-3">
                        @forelse($insights as $row)
                        <div class="relative group cursor-help p-3 rounded-lg border bg-gray-700/20 
                            {{ $row->market_status == 'Niche Genre' ? 'border-violet-500/30' : 'border-blue-500/30' }}">
                            
                            <div class="flex justify-between items-center gap-4">
                                <span class="font-bold text-gray-200">{{ $row->genre_name }}</span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded uppercase 
                                    {{ $row->market_status == 'Niche Genre' ? 'bg-violet-500 text-white' : 'bg-blue-500 text-white' }}">
                                    {{ $row->market_status == 'Niche Genre' ? 'Niche' : 'Grow' }}
                                </span>
                            </div>
                            
                            <div class="text-xs text-gray-400 mt-2 opacity-70 group-hover:opacity-100 transition">
                                {{ Str::limit($row->targeting_recommendation, 25) }}
                            </div>
                        </div>
                        @empty
                        <div class="text-gray-500 text-center w-full">No Insights</div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>