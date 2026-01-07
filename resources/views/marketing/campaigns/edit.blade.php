<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-cyan-400 leading-tight">
            Edit Campaign: {{ $campaign->campaign_name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen text-gray-100">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-700">
                <div class="p-8">
                    
                    <form action="{{ route('campaigns.update', $campaign->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="mb-6">
                            <label for="campaign_name" class="block text-sm font-medium text-gray-400 mb-2">Campaign Name</label>
                            <input type="text" name="campaign_name" id="campaign_name" 
                                value="{{ old('campaign_name', $campaign->campaign_name) }}"
                                class="w-full bg-gray-900 border border-gray-600 rounded-lg text-white px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition">
                            @error('campaign_name') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-400 mb-2">Budget (USD)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                                    <input type="number" name="budget" id="budget" step="0.01"
                                        value="{{ old('budget', $campaign->budget) }}"
                                        class="w-full bg-gray-900 border border-gray-600 rounded-lg text-white pl-8 pr-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition">
                                </div>
                                @error('budget') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="target_audience" class="block text-sm font-medium text-gray-400 mb-2">Target Audience</label>
                                <input type="text" name="target_audience" id="target_audience" 
                                    value="{{ old('target_audience', $campaign->target_audience) }}"
                                    class="w-full bg-gray-900 border border-gray-600 rounded-lg text-white px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition">
                                @error('target_audience') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-400 mb-2">Start Date</label>
                                <input type="date" name="start_date" id="start_date" 
                                    value="{{ old('start_date', $campaign->start_date->format('Y-m-d')) }}"
                                    class="w-full bg-gray-900 border border-gray-600 rounded-lg text-white px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition [color-scheme:dark]">
                                @error('start_date') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-400 mb-2">End Date</label>
                                <input type="date" name="end_date" id="end_date" 
                                    value="{{ old('end_date', $campaign->end_date->format('Y-m-d')) }}"
                                    class="w-full bg-gray-900 border border-gray-600 rounded-lg text-white px-4 py-2 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition [color-scheme:dark]">
                                @error('end_date') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-700">
                            <a href="{{ route('campaigns.index') }}" class="text-gray-400 hover:text-white transition">Cancel</a>
                            
                            <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center gap-2 transition transform active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Update Campaign
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>